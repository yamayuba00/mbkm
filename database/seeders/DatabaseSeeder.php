<?php

namespace Database\Seeders;

use App\Models\InformationProgram;
use App\Models\ProgramsCampus;
use App\Models\SubmissionPeriod;
use App\Models\SubmissionTypeModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            // Univ Admin
            [
                'username' => 'adminupb',
                'email' => 'admin@upb.ac.id',
                'password' => Hash::make('password'),
                'role' => 1,
                'gender' => 1,
                'status' => 1,
                'detail' => [],
            ],

            // Kaprodi
            [
                'username' => 'kaprodiTI',
                'email' => 'kaprodi.ti@upb.ac.id',
                'password' => Hash::make('password'),
                'role' => 2,
                'gender' => 1,
                'status' => 1,
                'detail' => [
                    'nidn' => '0011223344',
                    // 'prodi_id' => 1,
                    'phone' => '081234567890',
                    'address' => 'Jl. Prodi TI No.1',
                ],
            ],

            // Dosen
            [
                'username' => 'dosen1',
                'email' => 'dosen1@upb.ac.id',
                'password' => Hash::make('password'),
                'role' => 3,
                'gender' => 2,
                'status' => 1,
                // 'lecturer_id' => null, // assuming this is the kaprodi
                'detail' => [
                    'nidn' => '1122334455',
                    // 'prodi_id' => 2,
                    'phone' => '081223344556',
                    'address' => 'Jl. Dosen No.3',
                ],
            ],

            // Mahasiswa
            [
                'username' => 'mhs123',
                'email' => 'mahasiswa1@upb.ac.id',
                'password' => Hash::make('password'),
                'role' => 4,
                'gender' => 2,
                'status' => 1,
                'lecturer_id' => 3,
                'detail' => [
                    'nim' => '2312345678',
                    'class' => 'IF-23-A',
                    // 'prodi_id' => 1,
                    'phone' => '081298765432',
                    'address' => 'Jl. Mahasiswa No.7',
                ],
            ],
        ];

        foreach ($users as $user) {
            $detail = $user['detail'] ?? [];
            unset($user['detail']);

            $userId = DB::table('users')->insertGetId([
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role' => $user['role'],
                'gender' => $user['gender'],
                'status' => $user['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_details')->insert([
                'user_id' => $userId,
                'nim' => $detail['nim'] ?? null,
                'nidn' => $detail['nidn'] ?? null,
                'class' => $detail['class'] ?? null,
                'phone' => $detail['phone'] ?? null,
                'address' => $detail['address'] ?? null,
                'prodi_id' => $detail['prodi_id'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        InformationProgram::insert([
            [
                'title' => 'Program Beasiswa Semester Ganjil',
                'content' => 'Beasiswa semester ganjil telah dibuka untuk seluruh mahasiswa aktif. Silakan ajukan melalui portal akademik.',
                'created_by' => 1, 
                'updated_by' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Workshop Pemrograman Web',
                'content' => 'Workshop pemrograman web akan diadakan pada tanggal 5 Agustus 2025. Terbuka untuk seluruh mahasiswa Prodi Teknik Informatika.',
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sosialisasi MBKM',
                'content' => 'Sosialisasi program Merdeka Belajar Kampus Merdeka akan dilakukan secara daring melalui Zoom pada tanggal 10 Agustus.',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        SubmissionPeriod::insert([
            ['periode' => 'Batch 1','created_at' => now(), 'updated_at' => now()],
            ['periode' => 'Batch 2', 'created_at' => now(), 'updated_at' => now()],
            ['periode' => 'Batch 3','created_at' => now(), 'updated_at' => now()],
        ]);
        SubmissionTypeModel::insert([
            ['program_mbkm' => 'Program Magang', 'created_at' => now(), 'updated_at' => now()],
            ['program_mbkm' => 'Program Studi Independen', 'created_at' => now(), 'updated_at' => now()],
            ['program_mbkm' => 'Kegiatan Wirausaha', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
