<?php

namespace App\Models;

use App\Models\User;
use App\Models\Prodi;
use App\Models\UserDetailModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'faculties';

    protected $fillable = [
        'name',
        'code',
    ];

    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userDetail()
    {
        return $this->hasMany(UserDetailModel::class, 'faculties_id');
    }
}
