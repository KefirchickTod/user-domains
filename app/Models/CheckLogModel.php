<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CheckLogModel extends Model
{
    public $timestamps = false;

    protected $table = 'check_logs';

    protected $fillable = [
        'domain_id',
        'status_code',
        'response_time_ms',
        'is_successful',
        'error_message',
        'checked_at',
    ];

    protected $casts = [
        'status_code'      => 'integer',
        'response_time_ms' => 'integer',
        'is_successful'    => 'boolean',
        'checked_at'       => 'datetime',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomainModel::class, 'domain_id');
    }
}
