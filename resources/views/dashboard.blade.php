<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AlignUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Calculate user type variables before body tag --}}
    @php
        $userType = strtolower(trim($user->type ?? ''));
        $isAdmin = $userType === 'admin';
        $isAdminRoute = request()->is('admin/dashboard') || request()->is('admin/dashboard/*');
    @endphp
</head>
<body class="{{ $isAdmin ? 'admin-dashboard' : 'bg-gray-50' }}">
    {{-- Security check: Ensure non-admins don't see admin content --}}
    
    @if(!$isAdmin && $isAdminRoute)
        <script>window.location.href = '/dashboard';</script>
    @endif

<div class="max-w-6xl mx-auto px-4 py-8">

    @if(session('success'))
        <div class="mb-4 {{ $isAdmin ? 'admin-alert-success' : 'p-3 rounded bg-green-50 border border-green-200 text-green-800' }}">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 {{ $isAdmin ? 'admin-alert-error' : 'p-3 rounded bg-red-50 border border-red-200 text-red-800' }}">
            {{ session('error') }}
        </div>
    @endif

    @if($isAdmin)
        <div class="admin-dashboard-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1>Admin Dashboard</h1>
                    <p>Welcome, <span class="font-semibold">{{ $user->name }}</span></p>
                    <span class="admin-role-badge">{{ $user->type }}</span>
                </div>
                <a href="{{ route('user.logout') }}" 
                   class="bg-white text-indigo-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition duration-200 font-medium">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>

        <div class="flex items-center gap-2 mb-6">
            <a href="{{ route('dashboard', ['range' => '7']) }}"
               class="admin-range-btn {{ $range==='7' ? 'active' : '' }}">
                Last 7 days
            </a>
            <a href="{{ route('dashboard', ['range' => '30']) }}"
               class="admin-range-btn {{ $range==='30' ? 'active' : '' }}">
                Last 30 days
            </a>
            <a href="{{ route('dashboard', ['range' => 'all']) }}"
               class="admin-range-btn {{ $range==='all' ? 'active' : '' }}">
                All-time
            </a>
        </div>
    @else
        {{-- Student Navigation Bar --}}
        <nav class="bg-white shadow-md rounded-lg mb-6 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('appointments.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('appointments.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                        <i class="fas fa-calendar mr-2"></i>Appointments
                    </a>
                    <a href="{{ route('forum.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('forum.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                        <i class="fas fa-comments mr-2"></i>Forum
                    </a>
                    <a href="{{ route('settings.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('settings.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600 text-sm">Welcome, <span class="font-semibold">{{ $user->name }}</span></span>
                    <a href="{{ route('user.logout') }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </nav>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600">Welcome, <span class="font-semibold">{{ $user->name }}</span> ({{ $user->type }})</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard', ['range' => '7']) }}"
                   class="px-3 py-2 rounded border {{ $range==='7' ? 'bg-brand-gold text-white border-brand-gold' : 'bg-white text-gray-700 border-gray-200' }}">
                    Last 7 days
                </a>
                <a href="{{ route('dashboard', ['range' => '30']) }}"
                   class="px-3 py-2 rounded border {{ $range==='30' ? 'bg-brand-gold text-white border-brand-gold' : 'bg-white text-gray-700 border-gray-200' }}">
                    Last 30 days
                </a>
                <a href="{{ route('dashboard', ['range' => 'all']) }}"
                   class="px-3 py-2 rounded border {{ $range==='all' ? 'bg-brand-gold text-white border-brand-gold' : 'bg-white text-gray-700 border-gray-200' }}">
                    All-time
                </a>
            </div>
        </div>
    @endif

    {{-- =========================
        ADMIN VIEW (Tabs)
    ========================== --}}
    @if($isAdmin)

        <div class="admin-tabs-container" x-data="{tab:'overview'}">
            <nav class="-mb-px flex gap-6">
                <button @click="tab='overview'" class="pb-3 font-semibold"
                        :class="tab==='overview' ? 'text-brand-red border-b-2 border-brand-red' : 'text-gray-500'">
                    Overview
                </button>
                <button @click="tab='users'" class="pb-3 font-semibold"
                        :class="tab==='users' ? 'text-brand-red border-b-2 border-brand-red' : 'text-gray-500'">
                    Users
                </button>
                <button @click="tab='services'" class="pb-3 font-semibold"
                        :class="tab==='services' ? 'text-brand-red border-b-2 border-brand-red' : 'text-gray-500'">
                    Services
                </button>
                <button @click="tab='settings'" class="pb-3 font-semibold"
                        :class="tab==='settings' ? 'text-brand-red border-b-2 border-brand-red' : 'text-gray-500'">
                    Settings
                </button>
            </nav>

            <div class="pt-6">

                {{-- OVERVIEW --}}
                <div x-show="tab==='overview'">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 admin-stats-grid">
                        <div class="admin-card">
                            <div class="text-sm text-gray-500">Users (total)</div>
                            <div class="text-2xl font-bold">{{ $usersCount['total'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Students: {{ $usersCount['students'] }} · Faculty: {{ $usersCount['faculty'] }} · Admins: {{ $usersCount['admins'] }}
                            </div>
                        </div>

                        <div class="admin-card">
                            <div class="text-sm text-gray-500">Appointments (total)</div>
                            <div class="text-2xl font-bold">{{ $appointmentsCount['total'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Confirmed: {{ $appointmentsCount['confirmed'] }} · Completed: {{ $appointmentsCount['completed'] }}
                            </div>
                        </div>

                        <div class="admin-card">
                            <div class="text-sm text-gray-500">Cancellations</div>
                            <div class="text-2xl font-bold">{{ $appointmentsCount['canceled'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">No-Show: {{ $appointmentsCount['no_show'] }}</div>
                        </div>

                        <div class="admin-card">
                            <div class="text-sm text-gray-500">Slot Utilization</div>
                            <div class="text-2xl font-bold">{{ $slotUtilization }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Needs availability_rules + duration setting
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 admin-card">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-bold text-gray-900">Resource Utilization (by Service)</h2>
                            <span class="text-xs text-gray-500">counts in selected range</span>
                        </div>

                        <div class="divide-y">
                            @foreach($services as $s)
                                <div class="py-3 flex items-center justify-between">
                                    <div class="font-semibold text-gray-800">{{ $s->name }}</div>
                                    <div class="text-gray-700 font-bold">
                                        {{ $utilByService[$s->id] ?? 0 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- USERS --}}
                <div x-show="tab==='users'">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 admin-form-container">
                            <h2 class="font-bold text-gray-900 mb-3">Create User</h2>
                            
                            @if($errors->any())
                                <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800 text-sm">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.users.create') }}" class="space-y-3">
                                @csrf
                                <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" placeholder="Name" required>
                                <input name="email" type="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2" placeholder="Email" required>
                                <select name="type" class="w-full border rounded px-3 py-2" required>
                                    <option value="student" {{ old('type') === 'student' ? 'selected' : '' }}>student</option>
                                    <option value="faculty" {{ old('type') === 'faculty' ? 'selected' : '' }}>faculty</option>
                                    <option value="admin" {{ old('type') === 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                                <input name="password" type="password" class="w-full border rounded px-3 py-2" placeholder="Password (min 6 characters)" required>

                                <button type="submit" class="w-full admin-btn-primary">
                                    Create
                                </button>
                            </form>
                        </div>

                        <div class="lg:col-span-2 admin-table-container overflow-x-auto">
                            <div class="p-4">
                                <h2 class="font-bold text-gray-900 mb-3">Manage Users</h2>
                            </div>
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b">
                                        <th class="py-2">Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($users as $u)
                                        <tr>
                                            <td class="py-2 font-semibold">{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td class="uppercase text-xs font-bold">{{ $u->type }}</td>
                                            <td class="text-right py-2">
                                                <form method="POST" action="{{ route('admin.users.changeType', ['id'=>$u->id]) }}" class="inline-flex items-center gap-2">
                                                    @csrf
                                                    <select name="type" class="border rounded px-2 py-1">
                                                        <option value="student" {{ $u->type==='student'?'selected':'' }}>student</option>
                                                        <option value="faculty" {{ $u->type==='faculty'?'selected':'' }}>faculty</option>
                                                        <option value="admin" {{ $u->type==='admin'?'selected':'' }}>admin</option>
                                                    </select>
                                                    <button class="admin-btn-secondary px-3 py-1">Save</button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.users.delete', ['id'=>$u->id]) }}" class="inline-block ml-2"
                                                      onsubmit="return confirm('Delete this user?');">
                                                    @csrf
                                                    <button class="px-3 py-1 rounded bg-gray-900 text-white hover:bg-gray-800 transition">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SERVICES --}}
                <div x-show="tab==='services'">
                    <div class="admin-card">
                        <h2 class="font-bold text-gray-900 mb-3">Toggle Services</h2>

                        <div class="divide-y">
                            @foreach($services as $s)
                                <div class="py-3 flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold">{{ $s->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $s->description }}</div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.services.toggle') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $s->id }}">
                                        <input type="hidden" name="is_active" value="{{ $s->is_active ? 0 : 1 }}">
                                        <button class="px-3 py-1 rounded text-white {{ $s->is_active ? 'admin-btn-primary' : 'bg-gray-700 hover:bg-gray-600' }}">
                                            {{ $s->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SETTINGS --}}
                <div x-show="tab==='settings'">
                    <div class="admin-form-container">
                        <h2 class="font-bold text-gray-900 mb-3">Platform Settings</h2>

                        <div class="text-sm text-gray-600 mb-4">
                            Tip: add <span class="font-semibold">appointment_duration_minutes</span> to enable slot utilization.
                        </div>

                        <form method="POST" action="{{ route('admin.settings.update') }}" id="settingForm" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                            @csrf
                            <input type="hidden" name="id" id="setting_id">
                            <input name="key" id="setting_key" class="border rounded px-3 py-2" placeholder="key (e.g. appointment_duration_minutes)" required>
                            <input name="value" id="setting_value" class="border rounded px-3 py-2" placeholder="value (e.g. 30)">
                            <button type="submit" class="admin-btn-primary" id="setting_submit">Add/Update</button>
                        </form>
                        <button onclick="clearForm()" id="cancel_edit" class="hidden mb-4 px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Cancel</button>

                        <div class="mt-6">
                            <h3 class="font-bold text-gray-900 mb-3">Current Settings</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-500 border-b">
                                            <th class="py-2">ID</th>
                                            <th class="py-2">Key</th>
                                            <th class="py-2">Value</th>
                                            <th class="py-2">Updated</th>
                                            <th class="py-2 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($settings as $setting)
                                            <tr>
                                                <td class="py-2 text-gray-600">{{ $setting->id }}</td>
                                                <td class="py-2 font-semibold">{{ $setting->key }}</td>
                                                <td class="py-2 text-gray-700">{{ strlen($setting->value) > 50 ? substr($setting->value, 0, 50) . '...' : $setting->value }}</td>
                                                <td class="py-2 text-xs text-gray-500">{{ $setting->updated_at ? $setting->updated_at->format('M d, Y') : 'N/A' }}</td>
                                                <td class="py-2 text-right">
                                                    <button onclick="editSetting({{ $setting->id }}, '{{ $setting->key }}', '{{ addslashes($setting->value) }}')" 
                                                            class="admin-btn-secondary px-3 py-1 text-sm mr-2">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.settings.delete', $setting->id) }}" 
                                                          class="inline-block" onsubmit="return confirm('Delete this setting?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700">
                                                            <i class="fas fa-trash mr-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    {{-- =========================
        FACULTY VIEW
    ========================== --}}
    @elseif($user->isFaculty())
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 rounded-lg border bg-white"><div class="text-sm text-gray-500">Confirmed</div><div class="text-2xl font-bold">{{ $appointmentsCount['confirmed'] }}</div></div>
            <div class="p-4 rounded-lg border bg-white"><div class="text-sm text-gray-500">Completed</div><div class="text-2xl font-bold">{{ $appointmentsCount['completed'] }}</div></div>
            <div class="p-4 rounded-lg border bg-white"><div class="text-sm text-gray-500">Canceled</div><div class="text-2xl font-bold">{{ $appointmentsCount['canceled'] }}</div></div>
            <div class="p-4 rounded-lg border bg-white"><div class="text-sm text-gray-500">No-Show</div><div class="text-2xl font-bold">{{ $appointmentsCount['no_show'] }}</div></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-4 rounded-lg border bg-white">
                <h2 class="font-bold mb-3">Upcoming Appointments</h2>
                <div class="divide-y">
                    @forelse($upcoming as $a)
                        <div class="py-3">
                            <div class="font-semibold">{{ $a->service->name ?? 'Service' }}</div>
                            <div class="text-sm text-gray-600">
                                Student: {{ $a->student->name ?? 'N/A' }} · {{ $a->start_at }} → {{ $a->end_at }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 py-3">No upcoming appointments.</div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 rounded-lg border bg-white">
                <h2 class="font-bold mb-3">My Availability</h2>
                <div class="divide-y">
                    @forelse($rules as $r)
                        <div class="py-3">
                            <div class="font-semibold">{{ $r->service->name ?? 'Service' }}</div>
                            <div class="text-sm text-gray-600">
                                Weekday: {{ $r->weekday }} · {{ $r->start_time }} → {{ $r->end_time }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 py-3">No availability rules.</div>
                    @endforelse
                </div>
            </div>
        </div>

    {{-- =========================
        STUDENT VIEW
    ========================== --}}
    @else
        {{-- Quick Action Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('appointments.create') }}" class="student-action-card p-6 rounded-lg border-2 border-brand-maroon bg-white transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-600">Book Appointment</div>
                        <div class="text-xs text-gray-500 mt-1">Schedule a new appointment</div>
                    </div>
                    <i class="fas fa-calendar-plus text-2xl text-brand-maroon"></i>
                </div>
            </a>

            <a href="{{ route('appointments.index') }}" class="student-action-card p-6 rounded-lg border-2 border-brand-maroon bg-white transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-600">View Schedule</div>
                        <div class="text-xs text-gray-500 mt-1">See all your appointments</div>
                    </div>
                    <i class="fas fa-calendar-check text-2xl text-brand-maroon"></i>
                </div>
            </a>

            <a href="{{ route('forum.index') }}" class="student-action-card p-6 rounded-lg border-2 border-brand-maroon bg-white transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-600">Forum</div>
                        <div class="text-xs text-gray-500 mt-1">Join discussions</div>
                    </div>
                    <i class="fas fa-comments text-2xl text-brand-maroon"></i>
                </div>
            </a>

            <a href="{{ route('settings.index') }}" class="student-action-card p-6 rounded-lg border-2 border-brand-maroon bg-white transition-all duration-200 transform hover:scale-105 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-600">Settings</div>
                        <div class="text-xs text-gray-500 mt-1">Manage preferences</div>
                    </div>
                    <i class="fas fa-cog text-2xl text-brand-maroon"></i>
                </div>
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <div class="text-sm text-gray-500">Confirmed</div>
                <div class="text-2xl font-bold text-brand-maroon">{{ $appointmentsCount['confirmed'] }}</div>
            </div>
            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <div class="text-sm text-gray-500">Completed</div>
                <div class="text-2xl font-bold text-green-600">{{ $appointmentsCount['completed'] }}</div>
            </div>
            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <div class="text-sm text-gray-500">Canceled</div>
                <div class="text-2xl font-bold text-red-600">{{ $appointmentsCount['canceled'] }}</div>
            </div>
            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <div class="text-sm text-gray-500">No-Show</div>
                <div class="text-2xl font-bold text-orange-600">{{ $appointmentsCount['no_show'] }}</div>
            </div>
        </div>

        {{-- Upcoming Appointments Section --}}
        <div class="p-6 rounded-lg border bg-white shadow-sm mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Upcoming Appointments</h2>
                <a href="{{ route('appointments.index') }}" class="text-sm text-brand-maroon hover:underline font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y">
                @forelse($upcoming as $a)
                    <div class="py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">{{ $a->service->name ?? 'Service' }}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-tie mr-1"></i>Faculty: {{ $a->faculty->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $a->start_at }} → {{ $a->end_at }}
                                </div>
                                @if($a->reason)
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-sticky-note mr-1"></i>{{ strlen($a->reason) > 100 ? substr($a->reason, 0, 100) . '...' : $a->reason }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($a->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($a->status === 'completed') bg-blue-100 text-blue-800
                                    @elseif($a->status === 'canceled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($a->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                        <div class="text-gray-500">No upcoming appointments.</div>
                        <a href="{{ route('appointments.create') }}" class="inline-block mt-3 text-brand-maroon hover:underline font-semibold">
                            Book your first appointment <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>

<script>
function editSetting(id, key, value) {
    document.getElementById('setting_id').value = id;
    document.getElementById('setting_key').value = key;
    document.getElementById('setting_value').value = value;
    document.getElementById('setting_submit').textContent = 'Update';
    document.getElementById('cancel_edit').classList.remove('hidden');
    document.getElementById('setting_key').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function clearForm() {
    document.getElementById('setting_id').value = '';
    document.getElementById('setting_key').value = '';
    document.getElementById('setting_value').value = '';
    document.getElementById('setting_submit').textContent = 'Add/Update';
    document.getElementById('cancel_edit').classList.add('hidden');
}
</script>
</body>
</html>
