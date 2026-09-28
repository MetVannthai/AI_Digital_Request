<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'default_min_stock',
        'require_request_approval',
    ];

    protected function casts(): array
    {
        return [
            'default_min_stock' => 'integer',
            'require_request_approval' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }
}
