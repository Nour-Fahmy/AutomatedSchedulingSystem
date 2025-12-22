<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <title>@yield('title', 'AlignUp - Automated Scheduling System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Brand Tokens (matching your logo) --}}
    <style>
        
/* =========================
   ALIGN UP – ADMIN DASHBOARD INLINE CSS
   ========================= */

/* Brand Colors (from logo) */
:root {
    --brand-gold: #F5AD3E;
    --brand-red: #982A35;
    --brand-dark: #111827;
    --brand-muted: rgba(17,24,39,.6);
    --brand-border: rgba(17,24,39,.12);
    --brand-shadow: 0 10px 26px rgba(17,24,39,.08);
}

/* Brand helpers used in dashboard */
.bg-brand-gold { background-color: var(--brand-gold) !important; }
.text-brand-gold { color: var(--brand-gold) !important; }
.border-brand-gold { border-color: var(--brand-gold) !important; }

.bg-brand-red { background-color: var(--brand-red) !important; }
.text-brand-red { color: var(--brand-red) !important; }
.border-brand-red { border-color: var(--brand-red) !important; }

.bg-brand-red:hover { filter: brightness(0.92); }
.bg-brand-gold:hover { filter: brightness(0.95); }

/* =========================
   ADMIN CARDS & LAYOUT
   ========================= */
.bg-white.border.border-gray-200 {
    border-color: var(--brand-border);
    box-shadow: var(--brand-shadow);
    border-radius: 14px;
}

h1, h2, h3 {
    letter-spacing: -0.02em;
    color: var(--brand-dark);
}

/* =========================
   ADMIN TABS
   ========================= */
nav.-mb-px button {
    font-weight: 700;
    padding: 0.55rem 0.1rem;
    transition: color .15s ease;
}
nav.-mb-px button:hover {
    color: var(--brand-red);
}
nav.-mb-px button.border-b-2 {
    border-bottom-width: 3px !important;
}

/* =========================
   ADMIN BUTTONS
   ========================= */
button.bg-brand-red,
button.bg-brand-gold {
    border-radius: 12px;
    font-weight: 800;
    letter-spacing: .01em;
    transition: transform .12s ease, filter .12s ease;
}
button.bg-brand-red:hover,
button.bg-brand-gold:hover {
    transform: translateY(-1px);
}

button.bg-gray-900 {
    border-radius: 12px;
    font-weight: 800;
}
button.bg-gray-900:hover {
    transform: translateY(-1px);
    filter: brightness(.95);
}

/* =========================
   ADMIN FORMS
   ========================= */
form input[type="text"],
form input[type="email"],
form input[type="password"],
form select {
    border: 1px solid rgba(17,24,39,.14);
    border-radius: 12px;
    padding: .6rem .8rem;
    background: #fff;
    transition: all .15s ease;
}
form input:focus,
form select:focus {
    border-color: rgba(152,42,53,.55);
    box-shadow: 0 0 0 4px rgba(152,42,53,.12);
    outline: none;
}

/* =========================
   ADMIN TABLES (USERS)
   ========================= */
table.min-w-full {
    border: 1px solid var(--brand-border);
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
}
table.min-w-full thead tr {
    background: rgba(17,24,39,.03);
}
table.min-w-full thead th {
    padding: .9rem;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--brand-muted);
}
table.min-w-full tbody td {
    padding: .95rem;
}
table.min-w-full tbody tr:hover {
    background: rgba(245,173,62,.08);
}

/* Role badge (ADMIN / FACULTY / STUDENT) */
td.uppercase.text-xs.font-bold {
    padding: .18rem .55rem;
    border-radius: 999px;
    border: 1px solid rgba(17,24,39,.14);
    background: rgba(17,24,39,.03);
    display: inline-block;
}

/* =========================
   ALERTS
   ========================= */
.bg-green-50.border-green-200,
.bg-red-50.border-red-200 {
    border-radius: 14px;
    box-shadow: 0 10px 20px rgba(17,24,39,.06);
}


        :root{
            --brand-gold:#F5AD3E;
            --brand-maroon:#982A35;
            --brand-dark:#111827;
        }
        .text-brand-gold{ color: var(--brand-gold); }
        .bg-brand-gold{ background: var(--brand-gold); }
        .hover\:bg-brand-gold:hover{ background: var(--brand-gold); }

        .text-brand-maroon{ color: var(--brand-maroon); }
        .bg-brand-maroon{ background: var(--brand-maroon); }
        .hover\:bg-brand-maroon:hover{ background: var(--brand-maroon); }

        .border-brand-gold{ border-color: var(--brand-gold); }
        .border-brand-maroon{ border-color: var(--brand-maroon); }

        .hover\:text-brand-gold:hover{ color: var(--brand-gold); }
        .hover\:text-brand-maroon:hover{ color: var(--brand-maroon); }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 {{ Auth::check() && Auth::user()->isAdmin() ? 'admin-dashboard' : '' }}">
    <!-- Navigation Bar -->
    <nav class="{{ Auth::check() && Auth::user()->isAdmin() ? 'admin-nav' : 'bg-white' }} shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-4">
                    <a href="/" class="flex items-center space-x-4">
                        <img src="{{ asset('AlignUp IMG.jpg') }}" alt="AlignUp Logo" class="h-12 w-auto">
                        <div class="text-2xl font-bold {{ Auth::check() && Auth::user()->isAdmin() ? 'text-brand-gold' : 'text-brand-maroon' }}">AlignUp</div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/"
                       class="{{ Auth::check() && Auth::user()->isAdmin() ? 'text-white hover:text-brand-gold' : 'text-gray-700 hover:text-brand-maroon' }} px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                        Home
                    </a>
                    <a href="/dashboard"
                       class="{{ Auth::check() && Auth::user()->isAdmin() ? 'text-white hover:text-brand-gold' : 'text-gray-700 hover:text-brand-maroon' }} px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                        Dashboard
                    </a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Auth::check())
                        <span class="{{ Auth::user()->isAdmin() ? 'text-white' : 'text-gray-700' }} text-sm">
                            Welcome, {{ Auth::user()->name }}
                            @if (Auth::user()->isAdmin())
                                <span class="admin-badge">Admin</span>
                            @endif
                        </span>

                        <a href="/dashboard"
                           class="{{ Auth::user()->isAdmin() ? 'bg-brand-gold text-brand-maroon hover:bg-brand-gold hover:brightness-110' : 'bg-brand-maroon text-white hover:bg-brand-gold' }} px-4 py-2 rounded-lg transition duration-200 font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>

                        <a href="/auth/logout"
                           class="{{ Auth::user()->isAdmin() ? 'text-white hover:text-brand-gold' : 'text-gray-700 hover:text-brand-maroon' }} px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    @else
                        <a href="/auth/login"
                           class="text-gray-700 hover:text-brand-maroon px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>

                        <a href="/auth/signup"
                           class="bg-brand-maroon text-white px-4 py-2 rounded-lg hover:bg-brand-gold transition duration-200 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button"
                            class="text-gray-700 hover:text-brand-maroon focus:outline-none focus:text-brand-maroon">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <img src="{{ asset('AlignUp IMG.jpg') }}" alt="AlignUp Logo" class="h-8 w-auto">
                    <span class="text-xl font-bold">AlignUp</span>
                </div>
                <p class="text-gray-400">&copy; 2024 AlignUp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
