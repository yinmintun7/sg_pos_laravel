<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'shift';
    protected $fillable = [
        'id',
        'start_date_time',
        'end_date_time',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',

    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shift_id', 'id');
    }
}
