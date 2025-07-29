<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SppdPengajuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker; // Import Faker untuk data dummy

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Inisialisasi Faker dengan locale Indonesia
        $faker = Faker::create('id_ID');

        // --- 1. Buat Admin User ---
        // Menggunakan firstOrCreate untuk mencegah duplikasi jika seeder dijalankan berkali-kali
        $admin = User::firstOrCreate(
            ['email' => 'admin@sppd.app'], // Kriteria untuk menemukan record yang sudah ada
            [
                'name' => 'Administrator SPPD',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Password default: 'password'
                'role' => 'admin',
                'nip' => '199001012015031001', // Contoh NIP realistis
                'jabatan' => 'Kepala Bagian Umum',
                'unit_kerja' => 'Sekretariat Daerah Kabupaten Pamekasan',
                'phone' => '081122334455',
                // 'profile_photo_path' => null, // BARIS INI DIHAPUS
            ]
        );

        // --- 2. Data Master untuk User Biasa ---
        // Daftar jabatan umum di pemerintahan/kantor
        $jabatans = [
            'Staff Administrasi', 'Analis Kebijakan', 'Kepala Seksi', 'Bendahara',
            'Penyuluh Pertanian', 'Guru', 'Dokter Umum', 'Perawat',
            'Staff Keuangan', 'Staff Operasional', 'Arsitek', 'Teknisi IT',
            'Humas', 'Pengawas Lapangan', 'Koordinator Program',
            'Kasubbag Perencanaan', 'Kepala Bidang', 'Fungsional Umum'
        ];

        // Daftar unit kerja/dinas yang relevan dengan Kabupaten Pamekasan
        $unitKerjas = [
            'Dinas Pendidikan dan Kebudayaan Pamekasan',
            'Dinas Kesehatan Kabupaten Pamekasan',
            'Dinas Pekerjaan Umum dan Penataan Ruang Pamekasan',
            'Badan Perencanaan Pembangunan Daerah Pamekasan',
            'Dinas Pertanian dan Ketahanan Pangan Pamekasan',
            'Dinas Sosial Kabupaten Pamekasan',
            'Kantor Bupati Pamekasan',
            'Sekretariat DPRD Pamekasan',
            'Puskesmas Pamekasan Kota',
            'RSUD Dr. H. Slamet Martodirdjo Pamekasan',
            'Dinas Lingkungan Hidup Pamekasan',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Pamekasan',
            'Satuan Polisi Pamong Praja Pamekasan',
            'Dinas Perhubungan Kabupaten Pamekasan',
            'Dinas Komunikasi dan Informatika Pamekasan'
        ];

        // --- 3. Buat Beberapa User Biasa (Sekitar 15-20 user) ---
        $numUsers = 20;
        $users = collect(); // Menggunakan koleksi untuk menyimpan user yang dibuat

        for ($i = 0; $i < $numUsers; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = $firstName . ' ' . $lastName;
            // Membuat email yang lebih unik dan relevan
            $email = strtolower(str_replace(' ', '.', $firstName)) . '.' . strtolower($lastName) . $faker->unique()->randomNumber(3) . '@pamekasan.app';

            // Generasi NIP yang lebih realistis (YYYYMMDD TahunLahir, YYYYMM TahunPengangkatan, X JenisKelamin/Golongan, NNN NomorUrut)
            $birthDate = $faker->dateTimeBetween('-45 years', '-25 years'); // Usia 25-45 tahun
            $appointmentYear = $faker->numberBetween(2005, 2023); // Tahun pengangkatan
            $appointmentMonth = $faker->numberBetween(1, 12);
            $genderCode = $faker->randomElement(['1', '2']); // 1: Pria, 2: Wanita (contoh sederhana)
            $sequence = str_pad($faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT); // 3 digit nomor urut
            $nip = $birthDate->format('Ymd') . $appointmentYear . str_pad($appointmentMonth, 2, '0', STR_PAD_LEFT) . $genderCode . $sequence;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'), // Password default: 'password'
                    'role' => 'user',
                    'nip' => $nip,
                    'jabatan' => $faker->randomElement($jabatans),
                    'unit_kerja' => $faker->randomElement($unitKerjas),
                    'phone' => '08' . $faker->numerify('##########'), // Format nomor telepon Indonesia (08XXXXXXXXXX)
                    // 'profile_photo_path' => null, // BARIS INI DIHAPUS
                ]
            );
            $users->push($user);
        }

        // --- 4. Data Master untuk Pengajuan SPPD ---
        $namaKegiatans = [
            'Rapat Koordinasi Bidang', 'Pelatihan Peningkatan Kompetensi SDM', 'Kunjungan Kerja Lapangan ke Desa',
            'Sosialisasi Peraturan Baru', 'Evaluasi Program Tahunan', 'Workshop Penyusunan Anggaran',
            'Bimbingan Teknis Penggunaan Aplikasi', 'Audit Internal Keuangan', 'Pengawasan Proyek Infrastruktur',
            'Studi Banding Inovasi Pelayanan Publik', 'Pembinaan Kelompok Tani', 'Pendataan Potensi Wisata Daerah',
            'Musyawarah Perencanaan Pembangunan (Musrenbang)', 'Rapat Komisi DPRD', 'Verifikasi Data Lapangan'
        ];

        $deskripsiKegiatans = [
            'Membahas rencana kerja dan anggaran untuk triwulan berikutnya.',
            'Meningkatkan keterampilan pegawai dalam penggunaan software baru.',
            'Meninjau langsung kondisi proyek pembangunan di daerah terpencil.',
            'Memberikan informasi tentang regulasi terbaru kepada masyarakat.',
            'Mengevaluasi capaian program selama satu tahun terakhir.',
            'Menyusun draf anggaran berdasarkan prioritas pembangunan.',
            'Memberikan panduan teknis untuk implementasi sistem informasi.',
            'Melakukan pemeriksaan rutin terhadap laporan keuangan dinas.',
            'Memastikan kualitas dan progres pembangunan sesuai jadwal.',
            'Mempelajari praktik terbaik dari daerah lain dalam inovasi pelayanan.',
            'Memberikan bimbingan dan pendampingan kepada petani lokal.',
            'Mengidentifikasi dan mendokumentasikan objek wisata potensial.',
            'Mengumpulkan aspirasi masyarakat untuk rencana pembangunan.',
            'Membahas isu-isu strategis dan kebijakan daerah.',
            'Memvalidasi data kependudukan di tingkat RT/RW.'
        ];

        // Tempat kegiatan yang spesifik di Pamekasan dan sekitarnya
        $tempatKegiatans = [
            'Pendopo Agung Ronggosukowati, Pamekasan',
            'Kantor Bupati Pamekasan',
            'Aula Dinas Pendidikan Pamekasan',
            'Puskesmas Pamekasan Kota',
            'RSUD Dr. H. Slamet Martodirdjo Pamekasan',
            'Balai Desa Larangan Badung, Pamekasan',
            'Kantor Kecamatan Proppo, Pamekasan',
            'Hotel Front One, Pamekasan',
            'Gedung Islamic Centre, Pamekasan',
            'Surabaya', 'Malang', 'Sumenep', 'Sampang', 'Bangkalan', 'Gresik', 'Sidoarjo', 'Jakarta', 'Denpasar'
        ];

        $statuses = ['pending', 'approved', 'rejected', 'draft'];

        // --- 5. Buat Pengajuan SPPD untuk Setiap User ---
        foreach ($users as $user) {
            $numSppdPerUser = $faker->numberBetween(3, 7); // Setiap user memiliki 3-7 pengajuan SPPD
            for ($j = 0; $j < $numSppdPerUser; $j++) {
                $status = $faker->randomElement($statuses);
                $tanggalMulai = $faker->dateTimeBetween('-3 months', '+6 months'); // Rentang 3 bulan lalu hingga 6 bulan ke depan
                // Perbaikan: Gunakan add() dengan DateInterval
                $tanggalSelesai = (clone $tanggalMulai)->add(new \DateInterval('P' . $faker->numberBetween(0, 5) . 'D')); // Durasi 0-5 hari

                $sppdData = [
                    'user_id' => $user->id,
                    'nama_kegiatan' => $faker->randomElement($namaKegiatans),
                    'deskripsi_kegiatan' => $faker->randomElement($deskripsiKegiatans),
                    'tempat_kegiatan' => $faker->randomElement($tempatKegiatans),
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'waktu_kegiatan' => $faker->time('H:i:s'),
                    'estimasi_biaya' => $faker->numberBetween(750000, 15000000), // Estimasi biaya Rp 750rb - 15jt
                    'status' => $status,
                ];

                // Tambahkan data persetujuan/penolakan jika statusnya bukan 'pending' atau 'draft'
                if ($status === 'approved' || $status === 'rejected') {
                    $sppdData['approved_by'] = $admin->id; // Diasumsikan admin yang menyetujui/menolak
                    // Perbaikan: Gunakan sub() dengan DateInterval
                    $sppdData['approved_at'] = (clone $tanggalMulai)->sub(new \DateInterval('P' . $faker->numberBetween(1, 15) . 'D')); // Disetujui/ditolak sebelum tanggal mulai
                    $sppdData['catatan_admin'] = ($status === 'approved')
                        ? 'Pengajuan disetujui sesuai dengan kebijakan yang berlaku.'
                        : 'Pengajuan ditolak karena ' . $faker->randomElement([
                            'anggaran tidak mencukupi.',
                            'jadwal bentrok dengan kegiatan lain.',
                            'persyaratan belum lengkap.',
                            'tujuan kegiatan kurang relevan.',
                            'prioritas anggaran dialihkan.'
                        ]);
                }

                SppdPengajuan::firstOrCreate(
                    [
                        'user_id' => $sppdData['user_id'],
                        'nama_kegiatan' => $sppdData['nama_kegiatan'],
                        'tanggal_mulai' => $sppdData['tanggal_mulai']
                    ], // Kriteria unik untuk firstOrCreate (mencegah duplikasi)
                    $sppdData
                );
            }
        }
    }
}
