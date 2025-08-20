<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramsCampus extends Model
{
    protected $table = 'programs_campuses';

    protected $fillable = [
        'parent_id',
        'name',
        'major',
        'level',
        'code',
    ];

    public function parent()
    {
        return $this->belongsTo(ProgramsCampus::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProgramsCampus::class, 'parent_id');
    }

    public function scopeFakultas($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeProdi($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
