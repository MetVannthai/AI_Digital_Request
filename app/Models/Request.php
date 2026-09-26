<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name', 'employee_phone', 'department', 'item_id', 'quantity',
        'purpose', 'status', 'reviewed_by', 'reviewed_at', 'rejected_reason',
        'tracking_code',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
