<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Reset Password</title>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br">
        <form action="{{ route('password.update') }}" method="post" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Reset Password</h2>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            @if (session('status'))
                <div class="mb-4 text-green-600">{{ session('status') }}</div>
            @endif
            @error('email')
                <div class="mb-4 text-red-600">{{ $message }}</div>
            @enderror
            @error('password')
                <div class="mb-4 text-red-600">{{ $message }}</div>
            @enderror
            @error('token')
                <div class="mb-4 text-red-600">{{ $message }}</div>
            @enderror
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm text-gray-600 mb-2">New Password:</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm text-gray-600 mb-2">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <button type="submit" class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition duration-200">Reset Password</button>
        </form>
    </div>
</body>
</html>