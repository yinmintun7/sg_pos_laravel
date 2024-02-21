<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    protected $table = 'item';
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'price',
        'quantity',
        'code_no',
        'image',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',

    ];

    public function getCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getDiscountItemBy(): HasMany
    {
        return $this->hasMany(DiscountItem::class, 'discount_id', 'id');
    }

    public function getOrderDetail(): HasMany
    {
        return $this->hasMany(DiscountItem::class, 'item_id', 'id');
    }

}
