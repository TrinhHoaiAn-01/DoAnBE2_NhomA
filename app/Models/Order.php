<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promotion_id',
        'code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_district',
        'shipping_service',
        'delivery_date',
        'delivery_time_slot',
        'note',
        'promotion_code',
        'payment_method',
        'payment_status',
        'status',
        'subtotal',
        'shipping_fee',
        'discount_total',
        'total',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
