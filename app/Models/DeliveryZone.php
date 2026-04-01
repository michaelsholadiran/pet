<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryZone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'countries',
        'regions',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'countries' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function shippingRates(): HasMany
    {
        return $this->hasMany(ShippingRate::class, 'delivery_zone_id')->orderBy('min_order_amount');
    }
}
