<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'session_id',
        'answers',
        'recommended_products',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'recommended_products' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
