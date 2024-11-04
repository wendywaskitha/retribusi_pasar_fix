<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Retribusi Pasar</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-auto h-8">
                    <span class="ml-2 text-xl font-semibold text-gray-800">S I R E P A</span>
                </div>
                <div class="flex items-center">
                    @if (filament()->auth()->check())
                        <a href="{{ filament()->getHomeUrl() }}"
                            class="flex items-center px-5 py-2 text-blue-600 transition-all duration-300 border-2 border-blue-600 rounded-md hover:bg-blue-600 hover:text-white">
                            <i class="mr-2 fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ filament()->getLoginUrl() }}"
                            class="flex items-center px-5 py-2 text-blue-600 transition-all duration-300 border-2 border-blue-600 rounded-md hover:bg-blue-600 hover:text-white">
                            <i class="mr-2 fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-32 pb-20 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white sm:text-5xl md:text-6xl">
                    Sistem Informasi Retribusi Pasar
                </h1>
                <p class="mt-6 text-xl text-blue-100">
                    Solusi modern untuk pengelolaan retribusi pasar yang efisien dan transparan
                </p>
                <div class="mt-10">
                    <a href="#features"
                        class="px-8 py-3 font-semibold text-blue-600 transition-colors bg-white rounded-full hover:bg-gray-100">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Fitur Utama</h2>
                <p class="mt-4 text-gray-600">Berbagai fitur yang memudahkan pengelolaan retribusi pasar</p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-16 md:grid-cols-3">
                <!-- Feature 1 -->
                <div class="p-8 transition-transform transform bg-white shadow-lg rounded-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                        <i class="text-2xl text-blue-600 fas fa-users"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Pengelolaan Pedagang</h3>
                    <p class="mt-4 text-gray-600">
                        Manajemen data pedagang yang terstruktur dengan informasi lengkap dan mudah diakses
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 transition-transform transform bg-white shadow-lg rounded-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                        <i class="text-2xl text-blue-600 fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Pembayaran Retribusi</h3>
                    <p class="mt-4 text-gray-600">
                        Sistem pembayaran yang efisien dengan pencatatan otomatis dan bukti pembayaran digital
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 transition-transform transform bg-white shadow-lg rounded-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                        <i class="text-2xl text-blue-600 fas fa-chart-line"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Laporan & Analisis</h3>
                    <p class="mt-4 text-gray-600">
                        Pembuatan laporan otomatis dan analisis data untuk pengambilan keputusan yang lebih baik
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="py-20 bg-blue-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="text-4xl font-bold text-white">1000+</div>
                    <div class="mt-2 text-blue-200">Pedagang Terdaftar</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white">50+</div>
                    <div class="mt-2 text-blue-200">Pasar Terintegrasi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white">99%</div>
                    <div class="mt-2 text-blue-200">Tingkat Akurasi</div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Section -->
    <div class="py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900">Tentang Sistem</h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Sistem Informasi Retribusi Pasar adalah solusi digital terdepan untuk pengelolaan retribusi
                        pasar yang lebih efisien dan transparan. Dengan menggunakan teknologi terkini, kami membantu
                        pemerintah daerah dan pengelola pasar dalam mencatat, mengelola, dan melaporkan retribusi pasar
                        secara sistematis.
                    </p>
                    <div class="mt-8">
                        <a href="#"
                            class="px-6 py-3 text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">Pelajari
                            Lebih Lanjut</a>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0 lg:w-1/2">
                    <img src="{{ asset('storage/images/sirepa.png') }}" alt="Dashboard Preview"
                        class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Apa Kata Mereka</h2>
                <p class="mt-4 text-lg text-gray-600">Pendapat dari pengguna sistem kami</p>
            </div>
            <div class="grid grid-cols-1 gap-8 mt-16 md:grid-cols-2 lg:grid-cols-3">
                <!-- Testimonial 1 -->
                <div class="p-8 shadow bg-gray-50 rounded-xl">
                    <p class="italic text-gray-600">"Sistem ini sangat membantu kami dalam mengelola retribusi pasar.
                        Proses menjadi lebih efisien dan transparan."</p>
                    <div class="flex items-center mt-6">
                        <img src="{{ asset('avatar1.jpg') }}" alt="Avatar" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">John Doe</div>
                            <div class="text-gray-600">Kepala Pasar Sentral</div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="p-8 shadow bg-gray-50 rounded-xl">
                    <p class="italic text-gray-600">"Laporan yang dihasilkan sangat detail dan memudahkan kami dalam
                        pengambilan keputusan."</p>
                    <div class="flex items-center mt-6">
                        <img src="{{ asset('avatar2.jpg') }}" alt="Avatar" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Jane Smith</div>
                            <div class="text-gray-600">Kepala Dinas Perdagangan</div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="p-8 shadow bg-gray-50 rounded-xl">
                    <p class="italic text-gray-600">"Antarmuka yang user-friendly membuat pekerjaan kami menjadi lebih
                        mudah dan cepat."</p>
                    <div class="flex items-center mt-6">
                        <img src="{{ asset('avatar3.jpg') }}" alt="Avatar" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Robert Johnson</div>
                            <div class="text-gray-600">Staf Administrasi Pasar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white bg-gray-800">
        <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Tentang Kami</h3>
                    <p class="text-gray-400">Sistem Informasi Retribusi Pasar adalah solusi terdepan untuk pengelolaan
                        retribusi pasar yang efisien dan transparan.</p>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Beranda</a>
                        </li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Fitur</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Tentang</a>
                        </li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Hubungi Kami</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="mr-2 fas fa-map-marker-alt"></i> Jl. Contoh No. 123, Kota</li>
                        <li><i class="mr-2 fas fa-phone"></i> (021) 1234-5678</li>
                        <li><i class="mr-2 fas fa-envelope"></i> info@sirp.com</li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 transition-colors hover:text-white"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="pt-8 mt-12 text-center text-gray-400 border-t border-gray-700">
                <p>&copy; 2023 Sistem Informasi Retribusi Pasar. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed hidden p-3 text-white transition-colors bg-blue-600 rounded-full shadow-lg bottom-8 right-8 hover:bg-blue-700">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Contact Form Modal -->
    <div id="contactModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="min-h-screen px-4 text-center">
            <div
                class="inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Hubungi Kami</h3>
                            <div class="mt-4">
                                <form>
                                    <div class="mb-4">
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
                                            Nama
                                        </label>
                                        <input
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            id="name" type="text" placeholder="Masukkan nama">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                                            Email
                                        </label>
                                        <input
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            id="email" type="email" placeholder="Masukkan email">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="message">
                                            Pesan
                                        </label>
                                        <textarea
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            id="message" rows="4" placeholder="Tulis pesan Anda"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Kirim
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-32 h-32 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
    </div>

    <!-- Scripts -->
    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');

        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        };

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Modal Functions
        function openModal() {
            document.getElementById('contactModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }

        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Show Loading Spinner
        function showLoading() {
            document.getElementById('loadingSpinner').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.add('hidden');
        }

        // Add Animation on Scroll
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeIn');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach((element) => {
            observer.observe(element);
        });
    </script>

    <!-- Add custom styles -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .animate-on-scroll {
            opacity: 0;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</body>

</html>
