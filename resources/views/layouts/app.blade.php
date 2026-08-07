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
<body x-data="{ mobileMenuOpen: false }" class="text-slate-800 font-sans h-screen flex antialiased overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex-shrink-0 h-screen hidden md:flex flex-col">
        @include('layouts.partials.sidebar')
    </aside>

    <!-- Mobile Sidebar Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="relative z-50 md:hidden" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900 bg-opacity-50" @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 flex z-50">
            <!-- Sidebar panel -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4 h-full">
                
                <!-- Close button inside mobile menu -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" @click="mobileMenuOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                @include('layouts.partials.sidebar')
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-screen overflow-hidden">
        <!-- Mobile Topbar -->
        <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 md:hidden flex-shrink-0">
            <div class="flex items-center">
                <button type="button" @click="mobileMenuOpen = true" class="text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary p-2 -ml-2 rounded-md">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="ml-2 font-semibold text-slate-800">BSG Tracker</span>
            </div>
            <!-- Profile initial -->
            <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-medium">N</div>
        </header>

        
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
