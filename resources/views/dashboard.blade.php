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
        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 text-sm shadow-md animate-pulse">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="group p-5 rounded-xl border-2 border-indigo-200 bg-gradient-to-br from-indigo-50 to-white hover:from-indigo-100 hover:to-indigo-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Confirmed</div>
                    <i class="fas fa-check-circle text-indigo-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-indigo-700 group-hover:text-indigo-800 transition-colors">{{ $appointmentsCount['confirmed'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-white hover:from-green-100 hover:to-green-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-green-600 uppercase tracking-wide">Completed</div>
                    <i class="fas fa-check-double text-green-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-green-700 group-hover:text-green-800 transition-colors">{{ $appointmentsCount['completed'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-red-200 bg-gradient-to-br from-red-50 to-white hover:from-red-100 hover:to-red-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-red-600 uppercase tracking-wide">Canceled</div>
                    <i class="fas fa-times-circle text-red-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-red-700 group-hover:text-red-800 transition-colors">{{ $appointmentsCount['canceled'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-orange-200 bg-gradient-to-br from-orange-50 to-white hover:from-orange-100 hover:to-orange-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-orange-600 uppercase tracking-wide">No-Show</div>
                    <i class="fas fa-user-slash text-orange-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-orange-700 group-hover:text-orange-800 transition-colors">{{ $appointmentsCount['no_show'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 rounded-xl border-2 border-blue-200 bg-gradient-to-br from-white to-blue-50 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800">Upcoming Appointments</h2>
                </div>
                <div class="space-y-3">
                    @forelse($upcoming as $a)
                        <div class="p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 mb-1 flex items-center">
                                        <i class="fas fa-briefcase text-indigo-500 mr-2 text-sm"></i>
                                        {{ $a->service->name ?? 'Service' }}
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center mt-2">
                                        <i class="fas fa-user-graduate text-gray-400 mr-2"></i>
                                        <span class="font-medium">{{ $a->student->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        {{ $a->start_at }} → {{ $a->end_at }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                            <p>No upcoming appointments.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-6 rounded-xl border-2 border-purple-200 bg-gradient-to-br from-white to-purple-50 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <i class="fas fa-clock text-purple-600"></i>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800">My Availability</h2>
                </div>
                <div class="space-y-3">
                    @forelse($rules as $r)
                        <div class="p-4 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 mb-1 flex items-center">
                                        <i class="fas fa-briefcase text-purple-500 mr-2 text-sm"></i>
                                        {{ $r->service->name ?? 'Service' }}
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center mt-2">
                                        @php
                                            $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        @endphp
                                        <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                                        <span class="font-medium">{{ $weekdays[$r->weekday] ?? 'Day ' . $r->weekday }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        {{ date('g:i A', strtotime($r->start_time)) }} - {{ date('g:i A', strtotime($r->end_time)) }}
                                    </div>
                                </div>
                                <form action="{{ route('dashboard.faculty-office-hours.destroy', $r) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this office hour?')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 transform hover:scale-110">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-clock text-4xl mb-3 text-gray-300"></i>
                            <p>No availability rules.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Add Office Hours Form --}}
        <div class="p-6 rounded-xl border-2 border-teal-200 bg-gradient-to-br from-white to-teal-50 shadow-lg mt-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-teal-100 rounded-lg mr-3">
                    <i class="fas fa-plus-circle text-teal-600"></i>
                </div>
                <h2 class="font-bold text-xl text-gray-800">Add Office Hours</h2>
            </div>
            @if(session('error'))
                <div class="mb-4 p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 text-sm shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('dashboard.faculty-office-hours.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div class="transform transition-all duration-200 hover:scale-105">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-briefcase text-teal-500 mr-2"></i>
                        Service Type
                    </label>
                    <select name="service_id" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-400">
                        <option value="">Select service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="transform transition-all duration-200 hover:scale-105">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-day text-teal-500 mr-2"></i>
                        Day of Week
                    </label>
                    <select name="weekday" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-400">
                        <option value="">Select day</option>
                        <option value="0" @selected(old('weekday') == 0)>Sunday</option>
                        <option value="1" @selected(old('weekday') == 1)>Monday</option>
                        <option value="2" @selected(old('weekday') == 2)>Tuesday</option>
                        <option value="3" @selected(old('weekday') == 3)>Wednesday</option>
                        <option value="4" @selected(old('weekday') == 4)>Thursday</option>
                        <option value="5" @selected(old('weekday') == 5)>Friday</option>
                        <option value="6" @selected(old('weekday') == 6)>Saturday</option>
                    </select>
                </div>
                <div class="transform transition-all duration-200 hover:scale-105">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-clock text-teal-500 mr-2"></i>
                        Start Time
                    </label>
                    <input type="time" name="start_time" value="{{ old('start_time', '09:00') }}" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-400">
                </div>
                <div class="transform transition-all duration-200 hover:scale-105">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-clock text-teal-500 mr-2"></i>
                        End Time
                    </label>
                    <input type="time" name="end_time" value="{{ old('end_time', '17:00') }}" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-400">
                </div>
                <div class="md:col-span-4 pt-2">
                    <button type="submit" class="bg-gradient-to-r from-teal-600 to-teal-700 text-white px-8 py-3 rounded-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Add Office Hours
                    </button>
                </div>
            </form>
        </div>

    {{-- =========================
        STUDENT VIEW
    ========================== --}}
    @else
        {{-- Quick Action Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('appointments.create') }}" class="group p-6 rounded-xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-50 to-white hover:from-indigo-100 hover:to-indigo-50 transition-all duration-300 transform hover:scale-105 hover:shadow-xl cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-indigo-700 uppercase tracking-wide mb-1">Book Appointment</div>
                        <div class="text-xs text-gray-600">Schedule a new appointment</div>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-plus text-white text-2xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('appointments.index') }}" class="group p-6 rounded-xl border-2 border-blue-300 bg-gradient-to-br from-blue-50 to-white hover:from-blue-100 hover:to-blue-50 transition-all duration-300 transform hover:scale-105 hover:shadow-xl cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-blue-700 uppercase tracking-wide mb-1">View Schedule</div>
                        <div class="text-xs text-gray-600">See all your appointments</div>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('forum.index') }}" class="group p-6 rounded-xl border-2 border-green-300 bg-gradient-to-br from-green-50 to-white hover:from-green-100 hover:to-green-50 transition-all duration-300 transform hover:scale-105 hover:shadow-xl cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-green-700 uppercase tracking-wide mb-1">Forum</div>
                        <div class="text-xs text-gray-600">Join discussions</div>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-comments text-white text-2xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('settings.index') }}" class="group p-6 rounded-xl border-2 border-purple-300 bg-gradient-to-br from-purple-50 to-white hover:from-purple-100 hover:to-purple-50 transition-all duration-300 transform hover:scale-105 hover:shadow-xl cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-purple-700 uppercase tracking-wide mb-1">Settings</div>
                        <div class="text-xs text-gray-600">Manage preferences</div>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-white text-2xl"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="group p-5 rounded-xl border-2 border-indigo-200 bg-gradient-to-br from-indigo-50 to-white hover:from-indigo-100 hover:to-indigo-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Confirmed</div>
                    <i class="fas fa-check-circle text-indigo-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-indigo-700 group-hover:text-indigo-800 transition-colors">{{ $appointmentsCount['confirmed'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-white hover:from-green-100 hover:to-green-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-green-600 uppercase tracking-wide">Completed</div>
                    <i class="fas fa-check-double text-green-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-green-700 group-hover:text-green-800 transition-colors">{{ $appointmentsCount['completed'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-red-200 bg-gradient-to-br from-red-50 to-white hover:from-red-100 hover:to-red-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-red-600 uppercase tracking-wide">Canceled</div>
                    <i class="fas fa-times-circle text-red-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-red-700 group-hover:text-red-800 transition-colors">{{ $appointmentsCount['canceled'] }}</div>
            </div>
            <div class="group p-5 rounded-xl border-2 border-orange-200 bg-gradient-to-br from-orange-50 to-white hover:from-orange-100 hover:to-orange-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-orange-600 uppercase tracking-wide">No-Show</div>
                    <i class="fas fa-user-slash text-orange-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="text-3xl font-bold text-orange-700 group-hover:text-orange-800 transition-colors">{{ $appointmentsCount['no_show'] }}</div>
            </div>
        </div>

        {{-- Upcoming Appointments Section --}}
        <div class="p-6 rounded-xl border-2 border-indigo-200 bg-gradient-to-br from-white to-indigo-50 shadow-lg hover:shadow-xl transition-all duration-300 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <i class="fas fa-calendar-check text-indigo-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Upcoming Appointments</h2>
                </div>
                <a href="{{ route('appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold flex items-center transition-colors transform hover:scale-105">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($upcoming as $a)
                    <div class="p-4 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-briefcase text-indigo-500 mr-2 text-sm"></i>
                                    {{ $a->service->name ?? 'Service' }}
                                </div>
                                <div class="text-sm text-gray-600 flex items-center mt-2">
                                    <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                                    <span class="font-medium">Faculty: {{ $a->faculty->name ?? 'N/A' }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-clock text-gray-400 mr-2"></i>
                                    {{ $a->start_at }} → {{ $a->end_at }}
                                </div>
                                @if($a->reason)
                                    <div class="text-xs text-gray-500 mt-2 flex items-center bg-gray-100 rounded px-3 py-1.5 inline-block">
                                        <i class="fas fa-sticky-note mr-2"></i>
                                        {{ strlen($a->reason) > 100 ? substr($a->reason, 0, 100) . '...' : $a->reason }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <span class="px-4 py-2 rounded-full text-xs font-bold bg-gradient-to-r 
                                    @if($a->status === 'confirmed') from-blue-500 to-indigo-500
                                    @elseif($a->status === 'completed') from-green-500 to-emerald-500
                                    @elseif($a->status === 'canceled') from-red-500 to-rose-500
                                    @else from-gray-500 to-gray-600
                                    @endif text-white shadow-md">
                                    {{ ucfirst($a->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-white rounded-xl border-2 border-dashed border-gray-300">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times text-6xl text-gray-300"></i>
                        </div>
                        <div class="text-gray-600 font-medium mb-4">No upcoming appointments.</div>
                        <a href="{{ route('appointments.create') }}" class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Book your first appointment
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
