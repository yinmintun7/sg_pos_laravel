<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountItem extends Model
{
    use HasFactory;
    protected $table = 'discount_item';
    protected $fillable = [
        'id',
        'item_id',
        'discount_id',
        'status',
        'image',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',

    ];
}
