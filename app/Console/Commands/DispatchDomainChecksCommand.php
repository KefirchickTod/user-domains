<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CheckDomainJob;
use Illuminate\Console\Command;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;

final class DispatchDomainChecksCommand extends Command
{
    protected $signature = 'domains:dispatch-checks';

    protected $description = 'Dispatch check jobs for all domains';

    public function handle(CheckLogRepositoryInterface $log_repository): int
    {
        $domains_due = $log_repository->findDomainsDueForCheck();

        if (empty($domains_due)) {
            $this->info('No domains due for check.');

            return self::SUCCESS;
        }

        foreach ($domains_due as $item) {
            CheckDomainJob::dispatch(
                domain_id: $item['domain_id'],
                url: $item['url'],
                method: $item['settings']->method()->value,
                timeout_seconds: $item['settings']->timeoutSeconds()
            );
        }

        $this->info(sprintf('Dispatched %d domain check jobs.', count($domains_due)));

        return self::SUCCESS;
    }
}
