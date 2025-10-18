<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br">
        <form action="{{ route("user.signup") }}" method="post" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm">
            @csrf
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Sign Up</h2>

            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">email</label>
                <input type="text" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm text-gray-600 mb-2">password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm text-gray-600 mb-2">password confirmation</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            
            <div class="mb-6">
                <label for="type" class="block text-sm text-gray-600 mb-2">type</label>
                <input type="text" id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            
            <button type="submit"
                class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition duration-200">
                Sign Up
            </button>
        </form>
    </div>
</body>
</html>