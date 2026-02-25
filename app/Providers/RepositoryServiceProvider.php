<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Auth\Domain\UserRepositoryInterface;
use Src\Auth\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Domains\Infrastructure\Persistence\Eloquent\EloquentDomainRepository;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;
use Src\Monitoring\Infrastructure\Persistence\Eloquent\EloquentCheckLogRepository;
use Src\Monitoring\Infrastructure\Persistence\Eloquent\EloquentCheckSettingsRepository;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            DomainRepositoryInterface::class,
            EloquentDomainRepository::class
        );

        $this->app->bind(
            CheckSettingsRepositoryInterface::class,
            EloquentCheckSettingsRepository::class
        );

        $this->app->bind(
            CheckLogRepositoryInterface::class,
            EloquentCheckLogRepository::class
        );
    }
}
