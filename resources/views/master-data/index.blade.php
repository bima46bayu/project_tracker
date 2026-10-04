@extends('layouts.app')
@section('title', 'Master Data')

@section('content')
<div x-data="{ tab: '{{ $defaultTab ?? 'items' }}' }" class="px-4 py-4 md:px-8 md:py-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 capitalize">Master {{ $defaultTab ?? 'Items' }}</h2>
        <p class="text-sm text-slate-500">Manage templates for materials, services, and overhead costs.</p>
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
