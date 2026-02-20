<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Inventori Gudang IT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (max-width: 640px) {
            .login-card {
                margin: 1rem;
                padding: 1.5rem;
            }
            .login-input {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-800 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl p-6 sm:p-8 w-full max-w-md login-card">
        <div class="text-center mb-6 sm:mb-8">
            <i class="fas fa-warehouse text-4xl sm:text-5xl text-blue-600 mb-3 sm:mb-4"></i>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Inventori Gudang IT</h1>
            <p class="text-gray-500 text-sm sm:text-base">Silakan login untuk melanjutkan</p>
        </div>

        @if(Session::has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ Session::get('error') }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <input type="text" name="username" id="username" required
                    class="w-full px-4 py-2 sm:py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 login-input"
                    placeholder="Masukkan username">
            </div>

            <div class="mb-4 sm:mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 sm:py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 login-input"
                    placeholder="Masukkan password">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 sm:py-3 px-4 rounded-lg transition duration-200 text-base sm:text-lg">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Default: admin / admin</p>
        </div>
    </div>
</body>
</html>
