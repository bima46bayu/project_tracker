@extends('layouts.app')

@section('content')
<div class="px-4 py-6 w-full sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Global Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Executive overview of all ongoing projects and their health status.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex items-center">
            <div class="bg-blue-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Projects</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalProjects }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex items-center">
            <div class="bg-indigo-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Active Projects</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $activeProjects }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex items-center">
            <div class="bg-emerald-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Completed</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $completedProjects }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex items-center">
            <div class="bg-emerald-50 p-3 rounded-lg mr-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Active Contract Value</p>
                <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format($totalActiveRAB, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Project Health Status -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Project Health Status</h3>
            <p class="text-xs text-slate-500 mt-1">Real-time monitoring of S-Curve deviation for all ongoing projects.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Project Name</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">RAB Value</th>
                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Planned (%)</th>
                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Actual (%)</th>
                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Deviation (%)</th>
                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Health</th>
                        <th class="px-6 py-3 text-center w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projectHealth as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-800">{{ $p->name }}</p>
                                <p class="text-xs text-slate-500">{{ $p->project_code }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $p->customer }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 text-right">Rp {{ number_format($p->rab, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600 text-center">{{ number_format($p->planned, 2) }}%</td>
                            <td class="px-6 py-4 text-sm font-medium text-primary text-center">{{ number_format($p->actual, 2) }}%</td>
                            
                            <td class="px-6 py-4 text-center text-sm font-bold {{ $p->deviation >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $p->deviation > 0 ? '+' : '' }}{{ number_format($p->deviation, 2) }}%
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                @if($p->status === 'ON_TRACK')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        ON TRACK
                                    </span>
                                @elseif($p->status === 'WARNING')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        WARNING
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 animate-pulse">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        CRITICAL
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('projects.show', $p->id) }}" class="text-primary hover:text-primary-dark p-2 hover:bg-blue-50 rounded-lg inline-block transition-colors" title="View Project">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                No active projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
