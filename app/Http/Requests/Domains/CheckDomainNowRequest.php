<?php

declare(strict_types=1);

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Application\CheckNow\CheckDomainNowCommand;

final class CheckDomainNowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function makeCheckDomainNowCommand(): CheckDomainNowCommand
    {
        return new CheckDomainNowCommand(
            domain_id: (int) $this->route('domain'),
            user_id:   (int) $this->user()->id
        );
    }
}
