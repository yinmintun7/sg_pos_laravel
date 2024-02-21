<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $fillable = [
        'id',
        'quantity',
        'sub_total',
        'order_id',
        'item_id',
        'discount_price',
        'original_price',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',

    ];
    public function getOrderFromOrderDeatil(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getItemFromOrderDeatil(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
