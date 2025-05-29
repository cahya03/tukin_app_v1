<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Selamat Datang di Aplikasi Tukin TNI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased flex items-center justify-center min-h-screen">

    <div class="text-center">
        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        <h1 class="text-4xl font-bold mb-4">Aplikasi Tukin TNI</h1>
        <p class="mb-8 text-lg text-gray-600">Silakan login atau register untuk masuk ke sistem.</p>

        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded transition">
                Login
            </a>
            <a href="{{ route('register') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded transition">
                Register
            </a>
        </div>
    </div>

</body>

</html>