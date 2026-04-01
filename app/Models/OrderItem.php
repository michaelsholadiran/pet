<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'bundle_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'bundle_snapshot' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::creating(function (OrderItem $item) {
            if (! $item->product_id) {
                return;
            }

            $product = Product::query()->find($item->product_id);
            if (! $product) {
                return;
            }

            if (empty($item->product_name)) {
                $item->product_name = $product->name;
            }

            if ($item->bundle_snapshot === null && $product->isBundle()) {
                $item->bundle_snapshot = $product->bundleLinesForCatalog();
            }
        });

        static::saving(function (OrderItem $item) {
            if ($item->product_id && empty($item->product_name) && $item->product) {
                $item->product_name = $item->product->name;
            }
        });
    }
}
