<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question_id',
        'option_text',
        'sort_order',
        'product_ids',
    ];

    protected function casts(): array
    {
        return [
            'product_ids' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
