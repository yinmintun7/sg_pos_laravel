<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
    use HasFactory,SoftDeletes;
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
}
