<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>AlignUp - Automated Scheduling System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('AlignUp IMG.jpg') }}" alt="AlignUp Logo" class="h-12 w-auto">
                    <div class="text-2xl font-bold text-indigo-600">AlignUp</div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Features</a>
                    <a href="#about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Auth::check())
                        <a href="/dashboard" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/auth/logout" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    @else
                        <a href="/auth/login" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="/auth/signup" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-indigo-600 focus:outline-none focus:text-indigo-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left side - Content -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h1 class="text-5xl font-bold text-gray-900 leading-tight">
                            Welcome to 
                            <span class="text-indigo-600">AlignUp</span>
                        </h1>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            The automated scheduling system that connects students with faculty for academic advising, tutoring, and more. Streamline your academic journey with intelligent appointment management.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @if (Auth::check())
                            <a href="/dashboard" class="bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold text-lg text-center">
                                <i class="fas fa-arrow-right mr-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="/auth/signup" class="bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold text-lg text-center">
                                <i class="fas fa-rocket mr-2"></i>Get Started
                            </a>
                            <a href="/auth/login" class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-lg hover:bg-indigo-50 transition duration-200 font-semibold text-lg text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                        @endif
                    </div>

                    <!-- Features Preview -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8">
                        <div class="text-center">
                            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-alt text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Smart Scheduling</h3>
                            <p class="text-sm text-gray-600">Automated appointment booking</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Faculty Connect</h3>
                            <p class="text-sm text-gray-600">Direct access to advisors</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comments text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Forum Support</h3>
                            <p class="text-sm text-gray-600">Community discussions</p>
                        </div>
                    </div>
                </div>

                <!-- Right side - Image -->
                <div class="flex justify-center lg:justify-end">
                    <div class="relative">
                        <img src="{{ asset('AlignUp IMG.jpg') }}" alt="AlignUp System Preview" 
                             class="max-w-md w-full h-auto rounded-2xl shadow-2xl transform hover:scale-105 transition duration-300">
                        <div class="absolute -top-4 -right-4 bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            <i class="fas fa-star mr-1"></i>New
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose AlignUp?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our platform simplifies academic scheduling with intelligent features designed for students and faculty.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-clock text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">24/7 Availability</h3>
                    <p class="text-gray-600">Book appointments anytime, anywhere with our automated system.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bell text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Notifications</h3>
                    <p class="text-gray-600">Get reminders and updates about your appointments automatically.</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Secure & Reliable</h3>
                    <p class="text-gray-600">Your data is protected with enterprise-grade security measures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('AlignUp IMG.jpg') }}" alt="AlignUp Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold">AlignUp</span>
                    </div>
                    <p class="text-gray-400">Streamlining academic scheduling for students and faculty worldwide.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition duration-200">Home</a></li>
                        <li><a href="/dashboard" class="text-gray-400 hover:text-white transition duration-200">Dashboard</a></li>
                        <li><a href="/auth/login" class="text-gray-400 hover:text-white transition duration-200">Login</a></li>
                        <li><a href="/auth/signup" class="text-gray-400 hover:text-white transition duration-200">Sign Up</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <p class="text-gray-400">Email: support@alignup.local</p>
                    <p class="text-gray-400">Phone: +201001075484</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 AlignUp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile menu functionality -->
    <script>
        // Simple mobile menu toggle (you can enhance this)
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.md\\:hidden button');
            // Add mobile menu functionality here if needed
        });
    </script>
</body>
</html>
