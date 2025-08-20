<?php

namespace App\Models;

use App\Models\User;
use App\Models\SubmissionTypeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MbkmProgramModel extends Model
{
    use SoftDeletes;
    protected $table = 'mbkm_programs';

    protected $guarded = [];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function submissionType()
    {
        return $this->belongsTo(SubmissionTypeModel::class, 'submission_types_id');
    }

    public function submissionPeriod()
    {
        return $this->belongsTo(SubmissionPeriod::class, 'submission_period_id');
    }
}
