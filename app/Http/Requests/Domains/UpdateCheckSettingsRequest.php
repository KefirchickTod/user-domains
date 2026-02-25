<?php

declare(strict_types=1);

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Monitoring\Application\UpdateSettings\UpdateCheckSettingsCommand;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;

final class UpdateCheckSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $domain_id = (int) $this->route('domain');
        $user_id   = (int) $this->user()?->id;

        $domain = resolve(DomainRepositoryInterface::class)
            ->findByIdForUser(new DomainId($domain_id), $user_id);

        if ($domain === null) {
            return false;
        }

        return resolve(CheckSettingsRepositoryInterface::class)
            ->findByDomainId($domain_id) !== null;
    }

    public function rules(): array
    {
        return [
            'interval_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'timeout_seconds'  => ['required', 'integer', 'min:1', 'max:60'],
            'method'           => ['required', 'in:GET,HEAD'],
        ];
    }

    public function makeUpdateCheckSettingsCommand(): UpdateCheckSettingsCommand
    {
        return new UpdateCheckSettingsCommand(
            domain_id: (int) $this->route('domain'),
            user_id: (int) $this->user()->id,
            interval_minutes: (int) $this->input('interval_minutes'),
            timeout_seconds: (int) $this->input('timeout_seconds'),
            method: $this->input('method')
        );
    }
}
