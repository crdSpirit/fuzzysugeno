<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'store_code',
        'type',
        'store_name',
        'owner_name',
        'phone',
        'email',
        'address',

        // lokasi (ID RajaOngkir / Komerce)
        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',

        // koordinat
        'latitude',
        'longitude',
    ];

    // =====================
    // RELATIONS
    // =====================
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isSupplier()
    {
        return $this->type === 'supplier';
    }

    public function isCustomer()
    {
        return $this->type === 'customer';
    }

}
