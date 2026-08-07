@extends('layouts.app')
@section('title', 'Projects')

@section('content')
<div class="px-4 py-4 md:px-8 md:py-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Projects</h2>
            <p class="text-slate-500 text-sm mt-1">Manage all your construction projects here.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center self-start sm:self-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Project
        </a>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="clean-card p-5 hover:border-primary transition-colors cursor-pointer group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-base font-semibold text-slate-800 line-clamp-1 group-hover:text-primary transition-colors" title="{{ $project->name }}">{{ $project->name }}</h3>
                <span class="px-2 py-0.5 text-[10px] font-medium rounded border 
                    {{ $project->status === 'DONE' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-200' }}">
                    {{ $project->status }}
                </span>
            </div>
            
            <p class="text-xs text-slate-500 mb-4 flex items-center font-medium">
                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ $project->customer ? $project->customer->name : '-' }}
            </p>

            <div class="border-t border-slate-100 pt-3 mt-3">
                <div class="flex justify-between text-[10px] text-slate-400 font-medium uppercase mb-1">
                    <span>Timeline</span>
                    <span>Tasks</span>
                </div>
                <div class="flex justify-between text-xs font-medium text-slate-700">
                    <span>{{ \Carbon\Carbon::parse($project->start_date)->format('d M y') }} - {{ \Carbon\Carbon::parse($project->end_date)->format('d M y') }}</span>
                    <span>{{ $project->tasks_count }} Items</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full clean-card p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-base font-semibold text-slate-700 mb-1">No projects yet</h3>
            <p class="text-sm text-slate-500 mb-4">Start your first construction project to manage tasks and S-Curve.</p>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center text-primary font-medium text-sm hover:underline">
                Create Project
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
