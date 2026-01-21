<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'spareparts';

    protected $fillable = [
        'name',
        'brand',
        'size',
        'type',
        'weight',
        'fuzzy_weight_value',
        'stock',
        'price',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
