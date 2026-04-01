<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use SoftDeletes;

    public const TYPE_PRIVACY = 'privacy';

    public const TYPE_RETURN = 'return';

    public const TYPE_SHIPPING = 'shipping';

    protected $fillable = [
        'type',
        'title',
        'content',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function types(): array
    {
        return [
            self::TYPE_PRIVACY => 'Privacy Policy',
            self::TYPE_RETURN => 'Return Policy',
            self::TYPE_SHIPPING => 'Shipping Policy',
        ];
    }

    public static function findByType(string $type): ?self
    {
        return static::where('type', $type)->where('is_active', true)->first();
    }
}
