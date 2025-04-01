<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workouts extends Model
{
    protected $fillable = ['program_id', 'name', 'duration', 'calories_burn'];

    public function program()
    {
        return $this->belongsTo(training_programs::class, 'program_id');
    }
}
