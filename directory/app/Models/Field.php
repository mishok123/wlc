<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Field extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'key', 'type', 'options', 'option_scores'];

    protected $casts = [
        'options' => 'array',
        'option_scores' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)
                    ->withPivot('is_visible_in_card', 'order')
                    ->withTimestamps();
    }
}
