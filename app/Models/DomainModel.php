<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class DomainModel extends Model
{
    protected $table = 'domains';

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkSettings(): HasOne
    {
        return $this->hasOne(CheckSettingsModel::class, 'domain_id');
    }

    public function checkLogs(): HasMany
    {
        return $this->hasMany(CheckLogModel::class, 'domain_id');
    }
}
