<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LPK Harini Duta Ayu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Masuk ke Akun Anda
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Atau
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        daftar akun baru di sini
                    </a>
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input id="email" name="email" type="email" required
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Masukkan email"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input id="password" name="password" type="password" required
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Masukkan password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                        </span>
                        Masuk
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        Dengan masuk, Anda menyetujui
                        <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a>
                        dan
                        <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
