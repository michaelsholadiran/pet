<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key',
        'name',
        'subject',
        'body_html',
        'variables',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public static function keys(): array
    {
        return [
            'abandoned_cart' => 'Abandoned Cart',
            'welcome' => 'Welcome',
            'order_update' => 'Order Update',
            'post_purchase_review' => 'Post-Purchase Review Request',
            'subscription_reminder' => 'Subscription Reminder',
        ];
    }
}
