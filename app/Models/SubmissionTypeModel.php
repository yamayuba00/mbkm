<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmissionTypeModel extends Model
{
    use SoftDeletes;
    protected $table = 'submission_types';
    protected $fillable = [
        'program_mbkm',
    ];

    /**
     * Get the status as a human-readable string.
     */
    // public function getStatusAttribute($value)
    // {
    //     return $value == 1 ? 'Aktif' : 'Nonaktif';
    // }

    /**
     * Get the formatted start date.
     */
    // public function getFormattedStartDateAttribute()
    // {
    //     return $this->start_date ? $this->start_date->format('d-m-Y') : '-';
    // }   


    // /**
    //  * Get the formatted end date.
    //  */
    // public function getFormattedEndDateAttribute()
    // {
    //     return $this->end_date ? $this->end_date->format('d-m-Y') : '-';
    // }

    // cast
    // protected $casts = [
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    //     'status' => 'integer',
    // ];
}
