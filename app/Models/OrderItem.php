<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'sparepart_id',
        'quantity',
        'unit_weight',
        'total_weight',
    ];

    // =====================
    // RELATIONS
    // =====================
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
