<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'gender',
        'role',
        'last_login_at',
        'lecturer_id',
    ];


    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function students()
    {
        return $this->hasMany(User::class, 'lecturer_id');
    }

    // One-to-One: User has one UserDetail
    public function detail()
    {
        return $this->hasOne(UserDetailModel::class);
    }

    // Optional helper for role checking
    public function isStudent()
    {
        return $this->role === 4;
    }

    public function isKaprodi()
    {
        return $this->role === 2;
    }

    public function isUniv()
    {
        return $this->role === 1;
    }

    public function isLecturer()
    {
        return $this->role === 3;
    }

    public function getRoleName()
    {
        return match ($this->role) {
            1 => 'Admin Univ',
            2 => 'Kaprodi',
            3 => 'Dosen',
            4 => 'Mahasiswa',
            default => 'Tidak Diketahui',
        };
    }

    public function getRoleLabelAttribute()
    {
        return $this->getRoleName();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }
}
