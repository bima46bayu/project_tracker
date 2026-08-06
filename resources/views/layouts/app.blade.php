<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPN Dashboard - @yield('title', 'Overview')</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563eb', // Blue 600
                        'primary-light': '#eff6ff', // Blue 50
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Hotwire Turbo for SPA feel -->
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
    <!-- TomSelect for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    
    <!-- Flatpickr Custom Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f8fafc; } /* slate-50 */
        .clean-card { background: white; border-radius: 0.75rem; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }

        /* Global TomSelect Styling */
        .ts-wrapper { width: 100% !important; }
        .ts-control { 
            border: 1px solid #e2e8f0; 
            border-radius: 0.25rem; 
            padding: 0.5rem 2.5rem 0.5rem 1rem; 
            font-size: 0.875rem; 
            box-shadow: none; 
            background-color: #fff; 
            min-height: 42px; 
            display: flex; 
            align-items: center; 
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        .ts-control.focus { border-color: #2563eb; box-shadow: 0 0 0 1px #2563eb; }
        .ts-dropdown { font-size: 0.875rem; border-color: #e2e8f0; border-radius: 0.25rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-top: 4px; z-index: 9999; }
        .ts-dropdown .active { background-color: #eff6ff; color: #2563eb; }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 font-sans h-screen flex antialiased overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex-shrink-0 h-screen hidden md:flex flex-col">
        <div class="p-6">
            <h1 class="text-xl font-bold text-primary flex items-center">
                BSG Tracker
            </h1>
        </div>
        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('projects.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('projects.index') || request()->routeIs('projects.show') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('projects.index') || request()->routeIs('projects.show') ? 'text-primary' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                Projects
            </a>

            @if(session()->has('recent_projects') && count(session('recent_projects')) > 0)
                <div class="pl-12 pr-4 py-1 mb-2 space-y-1">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Recent</p>
                    @foreach(session('recent_projects') as $recentProj)
                        <a href="{{ route('projects.show', $recentProj['id']) }}" class="block text-xs text-slate-500 hover:text-primary truncate transition-colors py-1" title="{{ $recentProj['code'] }}">
                            {{ $recentProj['name'] }}
                        </a>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('projects.create') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('projects.create') ? 'text-primary' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                New Project
            </a>

            <!-- Master Data Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('master-data.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('master-data.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('master-data.*') ? 'text-primary' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        <span>Master Data</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('master-data.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('master-data.index') ? 'text-primary bg-primary-light' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Master Umum
                    </a>
                    <a href="{{ route('master-data.items.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('master-data.items.*') ? 'text-primary bg-primary-light' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Master Item
                    </a>
                    <a href="{{ route('master-data.indirect.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('master-data.indirect.*') ? 'text-primary bg-primary-light' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Master Indirect Cost
                    </a>
                </div>
            </div>
            
            <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors text-slate-600 hover:bg-slate-50 mt-8">
                <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                S-Curve Analytics
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition-colors">
                <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-medium">N</div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-slate-700">Sign out</p>
                    <p class="text-[10px] text-slate-400">Project Monitoring v1.0</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-screen overflow-hidden">
        
        <div class="flex-1 overflow-auto bg-slate-50" 
             x-data="{ loading: false, toasts: [] }" 
             @turbo:visit.window="loading = true" 
             @turbo:load.window="loading = false"
             @notify.window="let id = Date.now(); toasts.push({ id: id, msg: $event.detail.msg, type: $event.detail.type || 'success' }); setTimeout(() => { toasts = toasts.filter(t => t.id !== id) }, 3000)">
             
            <!-- Toast Notifications -->
            <div class="fixed top-4 right-4 z-50 flex flex-col space-y-2 pointer-events-none">
                
                <!-- Dynamic Alpine Toasts -->
                <template x-for="toast in toasts" :key="toast.id">
                    <div x-data="{ show: false }" 
                         x-init="$nextTick(() => show = true)"
                         x-show="show" 
                         x-transition.opacity.duration.300ms
                         :class="toast.type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700'"
                         class="border px-4 py-3 rounded-lg text-sm shadow-lg pointer-events-auto flex items-center space-x-3 transition-all">
                        
                        <template x-if="toast.type === 'success'">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>

                        <span class="font-medium" x-text="toast.msg"></span>
                        
                        <button @click="show = false; setTimeout(() => toasts = toasts.filter(t => t.id !== toast.id), 300)" :class="toast.type === 'error' ? 'text-red-500 hover:text-red-700' : 'text-emerald-500 hover:text-emerald-700'" class="ml-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </template>

                <!-- Session Toasts -->
                @if(session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 3000)"
                         x-transition.opacity.duration.300ms
                         class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm shadow-lg pointer-events-auto flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 ml-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif

                @if($errors->any())
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition.opacity.duration.300ms
                         class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm shadow-lg pointer-events-auto flex items-start space-x-3 max-w-sm">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700 ml-4 mt-0.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
            </div>

            <!-- Skeleton Loader (shows during Turbo navigation) -->
            <div x-show="loading" x-cloak class="p-8 w-full animate-pulse">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="h-24 bg-slate-200 rounded-lg clean-card"></div>
                    <div class="h-24 bg-slate-200 rounded-lg clean-card"></div>
                    <div class="h-24 bg-slate-200 rounded-lg clean-card"></div>
                </div>
                <div class="h-8 bg-slate-200 rounded w-48 mb-6"></div>
                <div class="h-64 bg-slate-200 rounded-lg clean-card"></div>
            </div>

            <!-- Actual Content -->
            <div x-show="!loading" x-transition.opacity.duration.300ms>
                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
