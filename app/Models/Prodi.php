<?php

namespace App\Models;

use App\Models\User;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prodi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'prodi';

    protected $fillable = [
        'name',
        'code',
        'faculty_id',
        'level',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userDetail()
    {
        return $this->hasMany(UserDetailModel::class, 'prodi_id');
    }
}
