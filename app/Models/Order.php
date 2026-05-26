<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'customer_id', 'date', 'total_price', 'notes',
        'reason_for_cancellation', 'nif', 'address', 'payment_type',
        'payment_ref', 'receipt_url', 'custom'
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'total_price' => 'decimal:2'];
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isClosed(): bool { return $this->status === 'closed'; }
    public function isCanceled(): bool { return $this->status === 'canceled'; }
}
