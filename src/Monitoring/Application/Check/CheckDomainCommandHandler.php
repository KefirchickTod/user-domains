<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\Check;

use App\Mail\DomainDownMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;
use Src\Monitoring\Domain\CheckLog;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;
use Src\Monitoring\Infrastructure\Http\DomainCheckerService;

final readonly class CheckDomainCommandHandler
{
    public function __construct(
        private DomainCheckerService        $checker,
        private CheckLogRepositoryInterface $log_repository,
        private LoggerInterface             $logger
    )
    {
    }

    public function handle(CheckDomainCommand $command): void
    {
        $this->logger->info('Checking domain', [
            'domain_id' => $command->domain_id,
            'url' => $command->url,
        ]);

        $result = $this->checker->check(
            url: $command->url,
            method: $command->method,
            timeout_seconds: $command->timeout_seconds
        );

        $log = CheckLog::record(
            domain_id: $command->domain_id,
            status_code: $result['status_code'],
            response_time_ms: $result['response_time_ms'],
            is_successful: $result['is_successful'],
            error_message: $result['error_message']
        );

        $last_log = $this->log_repository->findLastByDomainId($command->domain_id);

        $this->log_repository->save($log);

        $this->logger->info('Domain check saved', [
            'domain_id' => $command->domain_id,
            'is_successful' => $result['is_successful'],
        ]);

        $this->notifyIfWentDown(
            command: $command,
            is_successful: $result['is_successful'],
            last_log: $last_log
        );
    }

    private function notifyIfWentDown(
        CheckDomainCommand $command,
        bool               $is_successful,
        ?CheckLog          $last_log
    ): void
    {
        $was_up = $last_log === null || $last_log->isSuccessful();
        $is_now_down = !$is_successful;

        if (!$was_up || !$is_now_down) {
            return;
        }

        $user = $this->findDomainOwner($command->domain_id);

        if ($user === null) {
            return;
        }

        Mail::to($user->email)->send(new DomainDownMail($command->url));

        $this->logger->info('Domain down notification sent', [
            'domain_id' => $command->domain_id,
            'user_email' => $user->email,
        ]);
    }

    private function findDomainOwner(int $domain_id): ?User
    {
        return User::query()
            ->whereHas('domains', fn($q) => $q->where('id', $domain_id))
            ->first();
    }
}
