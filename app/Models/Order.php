<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_code',
        'store_id',
        'order_date',
        'status',
        'total_weight',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    // Scope filters
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhereHas('store', function ($storeQuery) use ($search) {
                      $storeQuery->where('store_name', 'like', '%' . $search . '%')
                                 ->orWhere('city', 'like', '%' . $search . '%');
                  });
            });
        });
    }

    // =====================
    // RELATIONS
    // =====================
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
