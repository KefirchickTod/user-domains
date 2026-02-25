<?php

declare(strict_types=1);

namespace Src\Domains\Infrastructure\Persistence\Eloquent;

use App\Models\DomainModel;
use Psr\Log\LoggerInterface;
use Src\Domains\Domain\Domain;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainName;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Domains\Domain\DomainUrl;

final class EloquentDomainRepository implements DomainRepositoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function save(Domain $domain): Domain
    {
        $this->logger->info('Saving domain', ['url' => $domain->url()->value()]);

        $data = [
            'user_id'   => $domain->userId(),
            'name'      => $domain->name()->value(),
            'url'       => $domain->url()->value(),
            'is_active' => $domain->isActive(),
        ];

        $is_new = $domain->id()->value() === 0;

        $model = $is_new
            ? DomainModel::create($data)
            : tap(DomainModel::findOrFail($domain->id()->value()), static fn ($m) => $m->update($data));

        $this->logger->info('Domain saved', ['domain_id' => $model->id]);

        return $this->toDomain($model);
    }

    public function findById(DomainId $id): ?Domain
    {
        $model = DomainModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByIdForUser(DomainId $id, int $user_id): ?Domain
    {
        $model = DomainModel::where('id', $id->value())
            ->where('user_id', $user_id)
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAllByUser(int $user_id): array
    {
        return DomainModel::where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (DomainModel $model) => $this->toDomain($model))
            ->all();
    }

    public function delete(DomainId $id): void
    {
        $this->logger->info('Deleting domain', ['domain_id' => $id->value()]);

        DomainModel::destroy($id->value());

        $this->logger->info('Domain deleted', ['domain_id' => $id->value()]);
    }

    private function toDomain(DomainModel $model): Domain
    {
        return new Domain(
            id:         new DomainId($model->id),
            user_id:    $model->user_id,
            name:       new DomainName($model->name),
            url:        new DomainUrl($model->url),
            is_active:  $model->is_active,
            created_at: new \DateTimeImmutable($model->created_at->toDateTimeString())
        );
    }
}
