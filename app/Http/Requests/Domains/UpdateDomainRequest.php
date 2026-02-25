<?php

declare(strict_types=1);

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Application\Update\UpdateDomainCommand;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;

final class UpdateDomainRequest extends FormRequest
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
        return [
            'name'      => ['required', 'string', 'max:255'],
            'url'       => ['required', 'url', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function makeUpdateDomainCommand(): UpdateDomainCommand
    {
        return new UpdateDomainCommand(
            domain_id: (int) $this->route('domain'),
            user_id: (int) $this->user()->id,
            name: $this->input('name'),
            url: $this->input('url'),
            is_active: $this->boolean('is_active', true)
        );
    }
}
