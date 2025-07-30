<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SppdPengajuan; // Tetap gunakan SppdPengajuan sesuai kode asli Anda
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str; // Tambahkan ini untuk Str::slug

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

        // Daftar nama dari prompt Anda (total 100 nama)
        $namaUserList = [
            'Abdur Rahim', 'Siti Hajar', 'Moh. Taufik', 'Nur Halimah', 'Faiq Mustofa',
            'Indah Sari', 'H. Abdus Salam', 'Hj. Masfufah', 'Khoirul Anam', 'Lailatul Badriyah',
            'Muslihuddin', 'Rodiyah', 'Saiful Bahri', 'Syafi\'i', 'Umi Kultsum',
            'Wahyu Nurjaman', 'Zahra Fitriyah', 'Zulkifli', 'Ahmad Junaidi', 'Binti Soleha',
            'Choirul Umam', 'Dewi Ratnasari', 'Edi Susanto', 'Fatimah Zahra', 'Ghufron Syafi\'i',
            'Halimatus Sa\'diyah', 'Imam Syafi\'i', 'Jamilah', 'Khoiriyah', 'Lukman Hakim',
            'Ma\'mun Rasyid', 'Nafisah', 'Oman Fathurrahman', 'Putri Amanda', 'Qori\'atul Hasanah',
            'Ridwan Efendi', 'Siti Khadijah', 'Thoriq Rizki', 'Ubaidillah', 'Vivin Agustina',
            'Wulan Dari', 'Yuniarti', 'Zaenal Arifin', 'Aminah', 'Basuki Rahmad',
            'Cici Paramida', 'Dwi Handayani', 'Eka Putra', 'Fajar Setiawan', 'Guntur Pratama',
            'Haris Ramadhan', 'Ida Royani', 'Johan Permana', 'Kiki Amalia', 'Lia Amelia',
            'Mila Sari', 'Nana Suryana', 'Oki Setiana', 'Pipit Lestari', 'Qolbi Nur',
            'Rina Aprilia', 'Susan Wijaya', 'Tini Sumiati', 'Ujang Suryadi', 'Vera Wijayanti',
            'Wawan Hermawan', 'Yani Suryani', 'Zulfikar Ali', 'Adnan Maulana', 'Bella Cahyani',
            'Chandra Kirana', 'Daffa Pratama', 'Eliza Safira', 'Fikri Maulana', 'Gilang Ramadhan',
            'Hendra Wijaya', 'Intan Permata', 'Jaka Samudera', 'Karina Putri', 'Lutfi Hidayat',
            'Maya Sari', 'Nanda Pratama', 'Oni Indriani', 'Pasha Pratama', 'Qonita Laila',
            'Riyan Hadi', 'Salsa Indah', 'Tio Akbar', 'Uli Susanti', 'Vina Lestari',
            'Wisnu Wardhana', 'Yayan Suryana', 'Zahra Aulia', 'Aldian Putra', 'Bayu Samudra',
            'Cici Lestari', 'Deni Kurniawan', 'Euis Suryani', 'Faisal Anwar', 'Gita Permata'
        ];

        $users = collect(); // Menggunakan koleksi untuk menyimpan user yang dibuat

        // --- 3. Buat 100 User Biasa sesuai daftar nama ---
        foreach ($namaUserList as $name) {
            // Membuat email yang unik dari nama
            $emailBase = Str::slug($name, '.');
            $email = $emailBase . '@pamekasan.app';
            $uniqueEmail = $email;
            $counter = 1;
            // Pastikan email unik jika ada nama yang sama
            while (User::where('email', $uniqueEmail)->exists()) {
                $uniqueEmail = $emailBase . $counter++ . '@pamekasan.app';
            }

            // Generasi NIP yang lebih realistis (YYYYMMDD TahunLahir, YYYYMM TahunPengangkatan, X JenisKelamin/Golongan, NNN NomorUrut)
            $birthDate = $faker->dateTimeBetween('-45 years', '-25 years'); // Usia 25-45 tahun
            $appointmentYear = $faker->numberBetween(2005, 2023); // Tahun pengangkatan
            $appointmentMonth = $faker->numberBetween(1, 12);
            $genderCode = $faker->randomElement(['1', '2']); // 1: Pria, 2: Wanita (contoh sederhana)
            $sequence = str_pad($faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT); // 3 digit nomor urut, pastikan unik untuk NIP
            $nip = $birthDate->format('Ymd') . $appointmentYear . str_pad($appointmentMonth, 2, '0', STR_PAD_LEFT) . $genderCode . $sequence;

            $user = User::firstOrCreate(
                ['email' => $uniqueEmail],
                [
                    'name' => $name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'), // Password default: 'password'
                    'role' => 'user',
                    'nip' => $nip,
                    'jabatan' => $faker->randomElement($jabatans),
                    'unit_kerja' => $faker->randomElement($unitKerjas),
                    'phone' => '08' . $faker->numerify('##########'), // Format nomor telepon Indonesia (08XXXXXXXXXX)
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

        // Daftar tempat kegiatan hanya di Pamekasan dan sekitarnya
        $tempatKegiatansPamekasan = [
            'Pendopo Agung Ronggosukowati, Pamekasan',
            'Kantor Bupati Pamekasan',
            'Aula Dinas Pendidikan Pamekasan',
            'Puskesmas Pamekasan Kota',
            'RSUD Dr. H. Slamet Martodirdjo Pamekasan',
            'Balai Desa Larangan Badung, Pamekasan',
            'Kantor Kecamatan Proppo, Pamekasan',
            'Hotel Front One, Pamekasan',
            'Gedung Islamic Centre, Pamekasan',
            'Kantor Desa Teja Barat, Pamekasan',
            'Kantor Desa Jambringin, Pamekasan',
            'Area Wisata Api Tak Kunjung Padam, Pamekasan',
            'Pasar Tradisional Pamekasan',
            'Kantor Dinas Pertanian Pamekasan',
            'GOR Pamekasan',
            'Perpustakaan Daerah Pamekasan',
            'Taman Monumen Arek Lancor, Pamekasan',
            'Sentra Batik Pamekasan',
            'Desa Klampar, Pamekasan (Pusat Tenun)',
            'Sekolah Dasar Negeri Pamekasan (acak)'
        ];

        // Daftar tempat keberangkatan hanya di Pamekasan dan sekitarnya
        $tempatBerangkatsPamekasan = [
            'Kantor Bupati Pamekasan',
            'Sekretariat Daerah Kabupaten Pamekasan',
            'Dinas Pendidikan dan Kebudayaan Pamekasan',
            'Dinas Kesehatan Kabupaten Pamekasan',
            'Rumah Pribadi Pamekasan', // Diubah menjadi lebih spesifik di Pamekasan
            'Puskesmas Pembantu Pamekasan', // Diubah menjadi lebih spesifik di Pamekasan
            'Kantor Dinas Sosial Pamekasan',
            'Balai Desa Panglegur, Pamekasan',
            'Rumah Sakit Umum Daerah Pamekasan',
            'Kantor Perpajakan Pamekasan'
        ];

        $statuses = ['pending', 'approved', 'rejected', 'draft'];

        // --- 5. Buat Hanya Satu Pengajuan SPPD untuk Setiap User Biasa ---
        foreach ($users as $user) {
            $status = $faker->randomElement($statuses);
            $tanggalMulai = $faker->dateTimeBetween('-3 months', '+6 months');
            $tanggalSelesai = (clone $tanggalMulai)->add(new \DateInterval('P' . $faker->numberBetween(0, 5) . 'D'));

            $sppdData = [
                'user_id' => $user->id,
                'nama_kegiatan' => $faker->randomElement($namaKegiatans),
                'deskripsi_kegiatan' => $faker->randomElement($deskripsiKegiatans),
                'tempat_kegiatan' => $faker->randomElement($tempatKegiatansPamekasan), // Menggunakan daftar Pamekasan
                'tempat_berangkat' => $faker->randomElement($tempatBerangkatsPamekasan), // Menggunakan daftar Pamekasan
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'waktu_kegiatan' => $faker->time('H:i:s'),
                'estimasi_biaya' => $faker->numberBetween(750000, 15000000),
                'status' => $status,
            ];

            if ($status === 'approved' || $status === 'rejected') {
                $sppdData['approved_by'] = $admin->id;
                $sppdData['approved_at'] = (clone $tanggalMulai)->sub(new \DateInterval('P' . $faker->numberBetween(1, 15) . 'D'));
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

            SppdPengajuan::updateOrCreate(
                ['user_id' => $sppdData['user_id']],
                $sppdData
            );
        }
    }
}
