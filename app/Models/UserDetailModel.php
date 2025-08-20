<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetailModel extends Model
{
    use HasFactory;
    protected $table = 'user_details'; // Pastikan nama tabel sesuai dengan yang ada di database

    protected $fillable = [
        'user_id',
        'nim',
        'nidn',
        'class',
        'phone',
        'address',
        'prodi_id',
    ];

    // One-to-One (inverse): UserDetail belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculties_id');
    }

    // Jika ada model Prodi:
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
