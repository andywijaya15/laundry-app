<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'method' => 'string',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getMethodLabel(): string
    {
        return match ($this->method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            default => ucfirst($this->method),
        };
    }
}
