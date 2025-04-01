<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class training_programs extends Model
{
    use SoftDeletes;
    protected $fillable = ['coach_id', 'title', 'description', 'start_date', 'end_date'];

    public function workouts()
    {
        return $this->hasMany(workouts::class, 'program_id');
    }
}
