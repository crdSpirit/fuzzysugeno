<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $table = 'shipments';

    protected $fillable = [
        'order_id',
        'distance_km',
        'fuzzy_score',
        'delivery_decision',
        'shipping_cost',
    ];

    // =====================
    // RELATIONS
    // =====================
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
