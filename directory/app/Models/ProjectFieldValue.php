<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ProjectFieldValue extends Model
{
    use LogsActivity;

    protected $fillable = ['project_id', 'field_id', 'value'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
