<?php

namespace App\Models;

use App\Models\MbkmProgramModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionPeriod extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'submission_periods';

    protected $fillable = [
        'periode',
    ];

    public function mbkm_programs()
    {
        return $this->hasMany(MbkmProgramModel::class, 'submission_periods_id');
    }
}
