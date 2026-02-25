<?php

declare(strict_types=1);

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Application\Delete\DeleteDomainCommand;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;

final class DestroyDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return resolve(DomainRepositoryInterface::class)
            ->findByIdForUser(
                new DomainId((int) $this->route('domain')),
                (int) $this->user()?->id
            ) !== null;
    }

    public function rules(): array
    {
        return [];
    }

    public function makeDeleteDomainCommand(): DeleteDomainCommand
    {
        return new DeleteDomainCommand(
            domain_id: (int)$this->route('domain'),
            user_id:   (int)$this->user()->id
        );
    }
}
