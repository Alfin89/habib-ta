<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SppdPengajuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sppd.app',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '199001012015031001',
            'jabatan' => 'Administrator Sistem',
            'unit_kerja' => 'IT Department',
            'phone' => '081234567890',
        ]);

        // Buat beberapa User biasa untuk testing
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@sppd.app',
                'password' => Hash::make('password'),
                'role' => 'user',
                'nip' => '199002022016032002',
                'jabatan' => 'Staff Operasional',
                'unit_kerja' => 'Operational Department',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@sppd.app',
                'password' => Hash::make('password'),
                'role' => 'user',
                'nip' => '199003033017033003',
                'jabatan' => 'Staff Keuangan',
                'unit_kerja' => 'Finance Department',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@sppd.app',
                'password' => Hash::make('password'),
                'role' => 'user',
                'nip' => '199004044018034004',
                'jabatan' => 'Staff HR',
                'unit_kerja' => 'Human Resources',
                'phone' => '081234567893',
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create(array_merge($userData, [
                'email_verified_at' => now(),
            ]));

            // Buat beberapa pengajuan SPPD untuk setiap user
            SppdPengajuan::create([
                'user_id' => $user->id,
                'nama_kegiatan' => 'Workshop Laravel Filament',
                'deskripsi_kegiatan' => 'Mengikuti workshop tentang pengembangan aplikasi dengan Laravel Filament',
                'tempat_kegiatan' => 'Jakarta Convention Center',
                'tanggal_mulai' => now()->addDays(15),
                'tanggal_selesai' => now()->addDays(17),
                'waktu_kegiatan' => '09:00:00',
                'estimasi_biaya' => 2500000,
                'status' => 'pending',
            ]);

            SppdPengajuan::create([
                'user_id' => $user->id,
                'nama_kegiatan' => 'Rapat Koordinasi Regional',
                'deskripsi_kegiatan' => 'Menghadiri rapat koordinasi dengan kantor regional',
                'tempat_kegiatan' => 'Surabaya',
                'tanggal_mulai' => now()->addDays(30),
                'tanggal_selesai' => now()->addDays(31),
                'waktu_kegiatan' => '10:00:00',
                'estimasi_biaya' => 1800000,
                'status' => 'draft',
            ]);
        }

        // Buat beberapa pengajuan yang sudah approved
        SppdPengajuan::create([
            'user_id' => $users[0]['email'] === 'john@sppd.app' ? 2 : 1,
            'nama_kegiatan' => 'Pelatihan Manajemen Proyek',
            'deskripsi_kegiatan' => 'Mengikuti pelatihan manajemen proyek tingkat lanjut',
            'tempat_kegiatan' => 'Bandung',
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->subDays(3),
            'waktu_kegiatan' => '08:30:00',
            'estimasi_biaya' => 3200000,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now()->subDays(10),
            'catatan_admin' => 'Disetujui untuk peningkatan kompetensi karyawan',
        ]);

        // Buat pengajuan yang rejected
        SppdPengajuan::create([
            'user_id' => $users[1]['email'] === 'jane@sppd.app' ? 3 : 2,
            'nama_kegiatan' => 'Konferensi IT Internasional',
            'deskripsi_kegiatan' => 'Menghadiri konferensi IT internasional',
            'tempat_kegiatan' => 'Singapura',
            'tanggal_mulai' => now()->addDays(60),
            'tanggal_selesai' => now()->addDays(63),
            'waktu_kegiatan' => '09:00:00',
            'estimasi_biaya' => 15000000,
            'status' => 'rejected',
            'approved_by' => $admin->id,
            'approved_at' => now()->subDays(2),
            'catatan_admin' => 'Anggaran tidak mencukupi untuk perjalanan luar negeri',
        ]);
    }
}
