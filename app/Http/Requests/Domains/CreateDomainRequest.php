<?php

declare(strict_types=1);

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Application\Create\CreateDomainCommand;

final class CreateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:2048'],
        ];
    }

    public function makeCreateDomainCommand(): CreateDomainCommand
    {
        return new CreateDomainCommand(
            user_id: (int) $this->user()->id,
            name: $this->input('name'),
            url: $this->input('url')
        );
    }
}
