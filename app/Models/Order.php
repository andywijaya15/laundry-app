<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class Order extends Model
{
    use HasFactory;

    public const WORKFLOW = [
        'diterima',
        'cuci',
        'setrika',
        'siap_diambil',
        'selesai',
    ];

    public const PAYMENT_STATUS = [
        'belum_bayar',
        'lunas',
    ];

    protected $fillable = [
        'order_code',
        'outlet_id',
        'customer_id',
        'user_id',
        'status',
        'payment_status',
        'total_price',
        'estimated_done',
        'finished_at',
    ];

    protected $casts = [
        'status' => 'string',
        'payment_status' => 'string',
        'total_price' => 'decimal:2',
        'estimated_done' => 'date',
        'finished_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateOrderCode(): string
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = "ORD-{$date}-";

        $lastOrder = static::where('order_code', 'like', $prefix.'%')
            ->orderBy('order_code', 'desc')
            ->first();

        $sequence = 1;
        if ($lastOrder) {
            $lastSequence = (int) substr($lastOrder->order_code, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getNextStatus(): ?string
    {
        $currentIndex = array_search($this->status, self::WORKFLOW);
        if ($currentIndex === false || $currentIndex >= count(self::WORKFLOW) - 1) {
            return null;
        }

        return self::WORKFLOW[$currentIndex + 1];
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        $expectedNext = $this->getNextStatus();

        return $expectedNext === $nextStatus;
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'diterima' => 'Diterima',
            'cuci' => 'Proses Cuci',
            'setrika' => 'Proses Setrika',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'belum_bayar' => 'Belum Bayar',
            'lunas' => 'Lunas',
            default => ucfirst($this->payment_status),
        };
    }

    public function isEditable(): bool
    {
        return $this->status === 'diterima';
    }

    public function isFinished(): bool
    {
        return $this->status === 'selesai';
    }

    public function recalculateTotal(): void
    {
        $this->total_price = $this->items()->sum('subtotal');
        $this->save();
    }

    public function getNotaUrl(): string
    {
        return URL::signedRoute(
            'orders.nota',
            ['order' => $this->id],
            now()->addDays(30)
        );
    }

    public function scopeForRole($query)
    {
        $user = auth()->user();
        if ($user && $user->role === 'staff') {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }
}
