<div class="p-6">
    <h1 class="text-xl font-bold text-primary flex items-center">
        BSG Tracker
    </h1>
</div>
<nav class="flex-1 px-4 space-y-1 mt-2 overflow-y-auto">
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
