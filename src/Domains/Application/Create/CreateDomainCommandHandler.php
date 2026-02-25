<?php

declare(strict_types=1);

namespace Src\Domains\Application\Create;

use Psr\Log\LoggerInterface;
use Src\Domains\Domain\Domain;
use Src\Domains\Domain\DomainName;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Domains\Domain\DomainUrl;
use Src\Monitoring\Domain\CheckSettings;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;

final readonly class CreateDomainCommandHandler
{
    public function __construct(
        private DomainRepositoryInterface        $domain_repository,
        private CheckSettingsRepositoryInterface $settings_repository,
    ) {}

    public function handle(CreateDomainCommand $command): int
    {
        $domain = Domain::create(
            user_id: $command->user_id,
            name: new DomainName($command->name),
            url: new DomainUrl($command->url)
        );

        $saved_domain = $this->domain_repository->save($domain);

        $settings = CheckSettings::createDefault($saved_domain->id()->value());
        $this->settings_repository->save($settings);

        return $saved_domain->id()->value();
    }
}
