<?php

namespace App\Models;

use App\Models\User;
use App\Models\MbkmProgramModel;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $table = 'activities';

    protected $guarded = [];

    public function mbkmProgram()
    {
        return $this->belongsTo(MbkmProgramModel::class, 'mbkm_program_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    
}
