<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPK Harini Duta Ayu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #7c3aed  75%, #c026d3 100%);
            position: relative;
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .nav-blur {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .btn-primary {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        .btn-secondary {
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                    <img src="images/lpkharini.jpg" alt="LPK Harini Duta Ayu Logo" class="w-full h-full object-contain"/>
                                </div>
                                <span class="ml-3 text-xl font-bold text-gray-900">LPK Harini Duta Ayu</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="gradient-bg hero-pattern">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-building text-white text-4xl"></i>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-bold text-black-300 mb-6 leading-tight">
                            <span class="text-black-300">LPK Harini Duta Ayu</span>
                        </h1>
                        <p class="text-xl text-blue-500 text-opacity-90 max-w-3xl mx-auto mb-8">
                            Data Siswa Training Crash Program
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition duration-300">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="button-primary inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition duration-300">
                                <i class="fas fa-user-plus mr-2"></i>
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="button-secondary inline-flex items-center px-8 py-3 border-2 border-white text-lg font-medium rounded-md text-white hover:bg-white hover:text-blue-600 transition duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose LPK Harini Duta Ayu?</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Platform terdepan untuk manajemen data ABK dengan fitur-fitur modern dan keamanan tingkat tinggi.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Reliable</h3>
                        <p class="text-gray-600">Data ABK tersimpan dengan aman menggunakan teknologi enkripsi terbaru.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Real-time Analytics</h3>
                        <p class="text-gray-600">Monitor dan analisis data ABK secara real-time dengan dashboard interaktif.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Role-based Access</h3>
                        <p class="text-gray-600">Sistem akses berbasis role untuk admin dan user dengan permission yang berbeda.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
                    <p class="text-lg text-gray-300 mb-8">
                        Bergabunglah dengan ratusan perusahaan yang telah mempercayai LPK Company untuk manajemen data ABK mereka.
                    </p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-gray-900 bg-white hover:bg-gray-100 transition duration-300">
                            <i class="fas fa-rocket mr-2"></i>
                            Access Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-gray-900 bg-white hover:bg-gray-100 transition duration-300">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Free Trial
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <span class="ml-2 text-white font-semibold">LPK Harini Duta Ayu</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        © 2025 LPK Harini Duta Ayu. All rights reserved. |
                        <a href="#" class="hover:text-white">Privacy Policy</a> |
                        <a href="#" class="hover:text-white">Terms of Service</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('LPK Company Landing Page loaded successfully!');
        });
    </script>
</body>
</html>
