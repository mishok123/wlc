<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'slug', 'description', 'max_wlc', 'max_reputation', 'max_privacy'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class)
                    ->withPivot('is_visible_in_card', 'order', 'option_scores')
                    ->withTimestamps();
    }
}
