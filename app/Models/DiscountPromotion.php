<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class DiscountPromotion extends Model
{
    use HasFactory;
    protected $table = 'discount_promotion';
    protected $fillable = [
        'id',
        'name',
        'amount',
        'percentage',
        'start_date',
        'end_date',
        'description',
        'status',
        'image',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',

    ];
    public function getDiscountItems():HasMany
    {
        return $this->hasMany(DiscountItem::class,'discount_id','id');
    }
}
