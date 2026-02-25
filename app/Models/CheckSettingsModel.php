<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CheckSettingsModel extends Model
{
    protected $table = 'check_settings';

    protected $fillable = [
        'domain_id',
        'interval_minutes',
        'timeout_seconds',
        'method',
    ];

    protected $casts = [
        'interval_minutes' => 'integer',
        'timeout_seconds'  => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomainModel::class, 'domain_id');
    }
}
