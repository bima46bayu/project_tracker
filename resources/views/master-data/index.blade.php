@extends('layouts.app')
@section('title', 'Master Data')

@section('content')
<div x-data="{ tab: '{{ $defaultTab ?? 'items' }}' }" class="px-4 py-4 md:px-8 md:py-6">
    <div class="mb-2">
        <h2 class="text-xl font-bold text-slate-900">Master Data</h2>
        <p class="text-sm text-slate-500">Manage templates for materials, services, and overhead costs.</p>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-slate-200 mt-6 overflow-x-auto scrollbar-none">
        <nav class="-mb-px flex space-x-8 text-sm min-w-max">
            <button @click="tab = 'categories'" :class="tab === 'categories' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors">
                Categories
            </button>
            <button @click="tab = 'items'" :class="tab === 'items' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors">
                Master Items
            </button>
            <button @click="tab = 'indirect'" :class="tab === 'indirect' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors">
                Master Indirect Costs
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div x-show="tab === 'categories'" x-cloak>
        @include('master-data.categories-partial')
    </div>

    <div x-show="tab === 'items'" x-cloak>
        @include('master-data.items-partial')
    </div>

    <div x-show="tab === 'indirect'" x-cloak>
        @include('master-data.indirect-costs-partial')
    </div>
</div>
@endsection
