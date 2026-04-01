<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Puppy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'birth_date',
        'weight',
        'size_category',
        'health_notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function sizeCategories(): array
    {
        return [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
        ];
    }
}
