<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login - AlignUp</title>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br ">
        <form action="{{ route("user.login") }}" method="post" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm" id="loginForm" autocomplete="off">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login</h2>
            @csrf
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">Email:</label>
                <input type="text" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <div class="mb-6">
                <label for="Password" class="block text-sm text-gray-600 mb-2">Password:</label>
                <input type="password" id="Password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <button type="submit" class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition duration-200">Log in</button>
            <div class="text-center mt-4">
                <a href="{{ route('password.email') }}" class="text-indigo-500 hover:text-indigo-700">Forgot Password?</a>
            </div>
        </form>
    </div>
</body>
</html>