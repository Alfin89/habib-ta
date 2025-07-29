<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi SPPD Pemerintah Kabupaten Pamekasan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://example.com/path/to/your/favicon.ico" type="image/x-icon">
    <style>
        /* Optional: Smooth transition for dropdown */
        .dropdown-menu {
            transition: all 0.3s ease-out;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
        }
        .dropdown-menu.open {
            max-height: 200px; /* Adjust as needed */
            opacity: 1;
            transform: translateY(0);
        }
        /* Custom Green Palette */
        .bg-green-700 { background-color: #1a6d36; } /* Darker Green for Header/Footer */
        .bg-green-600 { background-color: #28a745; } /* Primary Green for Hero */
        .hover\:bg-green-600:hover { background-color: #218838; }
        .text-green-700 { color: #1a6d36; }
        .hover\:bg-green-100:hover { background-color: #d4edda; }
        .text-green-500 { color: #28a745; } /* Accent green for icons */
        .hover\:bg-green-500:hover { background-color: #218838; }

        /* Mobile menu specific styles */
        .mobile-menu {
            transition: all 0.3s ease-out;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
        }
        .mobile-menu.open {
            max-height: 500px; /* Adjust as needed to fit all menu items */
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Perbaikan Dropdown Mobile --- */
        /* Pastikan dropdown mobile tidak absolute agar alur layoutnya mengikuti */
        #mobileDropdownMenu {
            position: static; /* Menghilangkan posisi absolute */
            width: 100%; /* Memastikan lebar penuh */
            box-shadow: none; /* Menghilangkan bayangan jika tidak diperlukan di sini */
            margin-top: 0.5rem; /* Sedikit jarak dari tombol Login */
            border-radius: 0.5rem; /* Menyesuaikan radius */
            padding-top: 0; /* Sesuaikan padding */
            padding-bottom: 0; /* Sesuaikan padding */
        }

        /* Styling untuk item di dalam mobile dropdown */
        #mobileDropdownMenu a {
            padding-left: 1rem;
            padding-right: 1rem;
        }

    </style>
</head>
<body class="font-sans antialiased bg-gray-50">

    <header class="bg-green-700 text-white shadow-xl">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center relative">
            <a href="#" class="text-1xl font-bold flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Instansi" class="inline-block mr-2 rounded-full shadow-md w-10 h-10">
                <span class="hidden sm:inline-block">SILAT Sistem Informasi Layanan Terpadu</span>
                <span class="inline-block sm:hidden text-lg">SILAT SPPD</span> </a>

            <div class="md:hidden">
                <button id="mobileMenuButton" class="text-white focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <a href="#fitur" class="px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Fitur</a>
                <a href="#tentang" class="px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Tentang Kami</a>
                <a href="#kontak" class="px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Kontak</a>

                <div class="relative">
                    <button id="dropdownButton" class="ml-4 bg-white text-green-700 px-5 py-2 rounded-full hover:bg-green-100 transition duration-300 shadow-lg flex items-center">
                        Login
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl z-10 py-2">
                        <a href="/admin/login" class="block px-4 py-2 text-gray-800 hover:bg-green-500 hover:text-white rounded-lg mx-2 transition duration-200">Login Admin</a>
                        <a href="/user/login" class="block px-4 py-2 text-gray-800 hover:bg-green-500 hover:text-white rounded-lg mx-2 transition duration-200">Login User</a>
                    </div>
                </div>
            </div>
        </nav>

        <div id="mobileMenu" class="mobile-menu md:hidden bg-green-700 pb-4">
            <div class="container mx-auto px-6 pt-2 pb-4 space-y-2 flex flex-col items-center">
                <a href="#fitur" class="block w-full text-center px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Fitur</a>
                <a href="#tentang" class="block w-full text-center px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Tentang Kami</a>
                <a href="#kontak" class="block w-full text-center px-4 py-2 hover:bg-green-600 rounded-lg transition duration-300">Kontak</a>
                <div class="relative w-full text-center">
                    <button id="mobileDropdownButton" class="bg-white text-green-700 px-5 py-2 rounded-full hover:bg-green-100 transition duration-300 shadow-lg flex items-center justify-center w-full">
                        Login
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="mobileDropdownMenu" class="dropdown-menu mt-2 w-full bg-white rounded-xl shadow-xl z-10 py-2">
                        <a href="/admin/login" class="block px-4 py-2 text-gray-800 hover:bg-green-500 hover:text-white rounded-lg mx-2 transition duration-200">Login Admin</a>
                        <a href="/user/login" class="block px-4 py-2 text-gray-800 hover:bg-green-500 hover:text-white rounded-lg mx-2 transition duration-200">Login User</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="bg-green-600 text-white py-20 rounded-b-2xl shadow-xl mx-4 mt-4">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight mb-6">Kelola Perjalanan Dinas Pendidikan di Pamekasan dengan Mudah & Efisien</h1>
            <p class="text-md sm:text-lg mb-10 max-w-3xl mx-auto">Sistem Informasi SPPD Pemerintah Kabupaten Pamekasan membantu mengotomatisasi proses pengajuan, persetujuan, dan pelaporan perjalanan dinas secara terpadu dan transparan.</p>
            <a href="#" class="bg-white text-green-700 text-lg font-semibold px-8 py-4 rounded-full hover:bg-green-100 transition duration-300 shadow-lg transform hover:scale-105">Mulai Sekarang</a>
            <p class="mt-4 text-sm opacity-80">Untuk Seluruh Instansi Dibawah Naungan Dinas Pendidikan Kabupaten Pamekasan</p>
        </div>
    </section>

    ---

    <section id="fitur" class="py-16 bg-white mx-4 mt-8 rounded-2xl shadow-lg">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-12">Fitur Unggulan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Pengajuan & Persetujuan Digital</h3>
                    <p class="text-gray-600 text-center">Proses pengajuan dan persetujuan SPPD yang cepat dan tanpa kertas.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Pelaporan & Monitoring Real-time</h3>
                    <p class="text-gray-600 text-center">Pantau status perjalanan dinas dan laporan secara real-time.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0014.586 3H7a2 2 0 00-2 2v11m0 5l4-4m-4 0l-4 4m4-4h.01"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Integrasi Data & Keamanan</h3>
                    <p class="text-gray-600 text-center">Data terintegrasi dan aman sesuai standar keamanan pemerintah.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8m-2-4h.01"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Manajemen Anggaran SPPD</h3>
                    <p class="text-gray-600 text-center">Alokasi dan pengelolaan anggaran perjalanan dinas yang transparan.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8H4a2 2 0 01-2-2v-4A2 2 0 014 8h10a2 2 0 012 2v4a2 2 0 01-2 2h-6m-6 0h10a2 2 0 002-2v-4a2 2 0 00-2-2h-10a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Laporan Akuntabilitas</h3>
                    <p class="text-gray-600 text-center">Sediakan laporan akuntabilitas yang lengkap dan mudah diakses.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 7L15 12L10 17"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 text-center">Antarmuka Responsif</h3>
                    <p class="text-gray-600 text-center">Dapat diakses dari perangkat apapun, desktop maupun mobile.</p>
                </div>
            </div>
        </div>
    </section>

    ---

    <section id="tentang" class="py-16 bg-gray-100 mx-4 mt-8 rounded-2xl shadow-lg">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-8">Tentang Sistem Kami</h2>
            <p class="text-base sm:text-lg text-gray-700 max-w-4xl mx-auto leading-relaxed">
                Sistem Informasi SPPD Pemerintah Kabupaten Pamekasan dirancang khusus untuk memenuhi kebutuhan instansi pemerintah dalam mengelola perjalanan dinas. Kami berkomitmen untuk menyediakan solusi yang inovatif, efisien, dan transparan, mendukung tata kelola pemerintahan yang baik di Kabupaten Pamekasan. Dengan teknologi terkini dan antarmuka yang ramah pengguna, kami hadir untuk membantu meningkatkan produktivitas dan akuntabilitas instansi Anda.
            </p>
            <div class="mt-10">
                <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard SPPD Pamekasan" class="rounded-xl shadow-xl mx-auto w-full md:w-3/4 lg:w-2/3">
            </div>
        </div>
    </section>

    ---

    <section class="bg-green-700 text-white py-16 mx-4 mt-8 rounded-2xl shadow-xl">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Siap Mengoptimalkan Pengelolaan SPPD Instansi Anda di Pamekasan?</h2>
            <p class="text-md sm:text-xl mb-8 max-w-2xl mx-auto">Hubungi kami untuk mendapatkan demo atau informasi lebih lanjut tentang bagaimana Sistem Informasi SPPD kami dapat membantu instansi Anda di **Kabupaten Pamekasan**.</p>
            <a href="#kontak" class="bg-white text-green-700 text-lg font-semibold px-8 py-4 rounded-full hover:bg-green-100 transition duration-300 shadow-lg transform hover:scale-105">Hubungi Kami</a>
        </div>
    </section>

    ---

    <section id="kontak" class="py-16 bg-white mx-4 mt-8 rounded-2xl shadow-lg mb-8">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-12">Kontak Kami</h2>
            <div class="flex flex-wrap justify-center gap-10">
                <div class="bg-gray-50 p-8 rounded-xl shadow-md text-center flex-1 min-w-[300px] max-w-sm">
                    <div class="text-green-500 mb-4">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Alamat</h3>
                    <p class="text-gray-600">Jl. Raya Pamekasan No. XX, Pamekasan</p>
                    <p class="text-gray-600">Kabupaten Pamekasan, Jawa Timur</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md text-center flex-1 min-w-[300px] max-w-sm">
                    <div class="text-green-500 mb-4">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Telepon</h3>
                    <p class="text-gray-600">(0324) XXXX XXXX</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-md text-center flex-1 min-w-[300px] max-w-sm">
                    <div class="text-green-500 mb-4">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-600">info@sppd-pamekasan.go.id</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8 rounded-t-2xl shadow-xl mx-4">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 SPPD Pemerintah Kabupaten Pamekasan. Hak Cipta Dilindungi Undang-Undang.</p>
            <p class="mt-2 text-sm">Dibangun dengan ❤️ untuk efisiensi birokrasi di Pamekasan.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('dropdownButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileDropdownButton = document.getElementById('mobileDropdownButton');
            const mobileDropdownMenu = document.getElementById('mobileDropdownMenu');

            // Desktop Dropdown
            dropdownButton.addEventListener('click', function (event) {
                event.stopPropagation(); // Prevent click from bubbling to window and closing immediately
                dropdownMenu.classList.toggle('open');
            });

            // Mobile Menu Toggle
            mobileMenuButton.addEventListener('click', function (event) {
                event.stopPropagation(); // Prevent click from bubbling to window
                mobileMenu.classList.toggle('open');
                // Close mobile dropdown if main mobile menu is closed
                if (!mobileMenu.classList.contains('open')) {
                    mobileDropdownMenu.classList.remove('open');
                }
            });

            // Mobile Dropdown Toggle
            mobileDropdownButton.addEventListener('click', function (event) {
                event.stopPropagation(); // Prevent click from bubbling to window
                mobileDropdownMenu.classList.toggle('open');
            });

            // Close all dropdowns/menus if the user clicks outside of them
            window.addEventListener('click', function (event) {
                // Close desktop dropdown
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('open');
                }
                // Close mobile menu and its dropdown
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.remove('open');
                    mobileDropdownMenu.classList.remove('open'); // Also close mobile dropdown
                }
            });

            // Close mobile menu when a menu item is clicked
            const mobileMenuItems = mobileMenu.querySelectorAll('a');
            mobileMenuItems.forEach(item => {
                item.addEventListener('click', () => {
                    mobileMenu.classList.remove('open');
                    mobileDropdownMenu.classList.remove('open');
                });
            });
        });
    </script>

</body>
</html>
