<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sign Up</title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-indigo-300">
    <div class="flex items-center justify-center min-h-screen">
        <form action="{{ route('user.signup') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm">
            @csrf
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Sign Up</h2>

            {{-- Name --}}
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">Email</label>
                <input type="text" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm text-gray-600 mb-2">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm text-gray-600 mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type --}}
            <div class="mb-6">
                <label for="type" class="block text-sm text-gray-600 mb-2">Type (student / faculty / admin)</label>
                <input type="text" id="type" name="type"
                       value="{{ old('type') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition duration-200">
                Sign Up
            </button>
        </form>
    </div>
</body>
</html>
