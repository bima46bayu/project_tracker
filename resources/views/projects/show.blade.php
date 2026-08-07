@extends('layouts.app')
@section('title', $project->name)

@section('content')
<div x-data="projectTracker({{ $project->id }})" class="min-h-screen bg-white">
    
    <!-- Header Summary (Matching Reference) -->
    <div class="px-3 sm:px-8 pt-4 pb-2">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
            <div class="flex items-center">
                <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-slate-600 mr-2 sm:mr-4 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 mr-2 sm:mr-4 leading-tight">{{ $project->name }}</h1>
            </div>
            
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border self-start sm:self-auto"
                  :class="{
                      'bg-emerald-50 text-emerald-600 border-emerald-100': computedProjectStatus === 'FINISH' || computedProjectStatus === 'DONE',
                      'bg-slate-50 text-slate-600 border-slate-200': computedProjectStatus === 'NOT_STARTED',
                      'bg-blue-50 text-blue-600 border-blue-100': computedProjectStatus !== 'FINISH' && computedProjectStatus !== 'DONE' && computedProjectStatus !== 'NOT_STARTED'
                  }">
                <span x-text="computedProjectStatus === 'NOT_STARTED' ? 'Not Started' : (computedProjectStatus === 'FINISH' ? 'Finish' : (computedProjectStatus === 'DONE' ? 'Done' : 'Ongoing'))"></span>
                (<span x-text="sCurveData && sCurveData.current_actual_progress !== undefined ? parseFloat(sCurveData.current_actual_progress).toFixed(1) : (project.progress || 0)"></span>%)
            </span>
        </div>
        <div class="flex items-center text-xs text-slate-500 pl-6 sm:pl-9 space-x-2">
            <span class="uppercase font-medium">{{ $project->project_code }}</span>
            <span class="text-slate-300">•</span>
            <span class="font-medium text-slate-600">{{ $project->customer ? $project->customer->name : '-' }}</span>
        </div>
    </div>
    <!-- Tabs (Matching Reference) -->
    <div class="px-3 sm:px-8 border-b border-slate-200 mt-4 sm:mt-6 relative">
        <nav class="-mb-px flex flex-row flex-nowrap space-x-4 sm:space-x-8 text-xs sm:text-sm overflow-x-auto whitespace-nowrap hide-scrollbar pb-1">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Overview
            </button>
            <button @click="activeTab = 'dokumentasi'" :class="activeTab === 'dokumentasi' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                Dokumentasi Project
            </button>
            <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Task Board
            </button>
            <button @click="activeTab = 'indirect'" :class="activeTab === 'indirect' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Indirect Cost
            </button>
            <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Payment
            </button>
            <button @click="activeTab = 'bobot'; fetchSCurve()" :class="activeTab === 'bobot' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Bobot Project
            </button>
            <a href="#" @click.prevent="activeTab = 's-curve'" class="whitespace-nowrap pb-3 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center" :class="activeTab === 's-curve' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                S-Curve
            </a>
            <a href="#" @click.prevent="activeTab = 'issues'" class="whitespace-nowrap pb-3 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center" :class="activeTab === 'issues' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Issues
            </a>
            <a href="#" @click.prevent="activeTab = 'settings'" class="whitespace-nowrap pb-3 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center" :class="activeTab === 'settings' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.11-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </nav>
    </div>

    <!-- Tab Contents Container -->
    <div class="px-3 py-3 sm:px-4 sm:py-4 md:px-6 md:py-6 lg:px-8 lg:py-8 bg-slate-50 min-h-screen">
        
        <!-- Overview Tab (Matches the Reference Image exactly) -->
        <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
            <!-- Project Details Card -->
            <div class="clean-card p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-6">Project Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-12">
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">PROJECT ID</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->project_code }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">TANGGAL PROJECT</p>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->translatedFormat('d M Y') : '-' }} - 
                            {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->translatedFormat('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">SPK/PO NO</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->spk_no ?: '-' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">NAMA PROJECT</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">JENIS PROJECT</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->jenisProject ? $project->jenisProject->value : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">LOKASI PROJECT</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->lokasi ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">CUSTOMER</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->customer ? $project->customer->name : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">BOWHEER</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->bowheer ? $project->bowheer->name : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">SUBKON</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->subkons->count() > 0 ? $project->subkons->pluck('name')->join(', ') : '-' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">NILAI PROJECT</p>
                        <p class="text-sm font-medium text-slate-900" x-text="calculateProjectTotal() > 0 ? 'Rp ' + calculateProjectTotal().toLocaleString('id-ID') : 'Menunggu RAB'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">ACCOUNT MANAGER (A/M)</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->accountManager ? $project->accountManager->name : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider mb-1">PROJECT MANAGER(S) (P/M)</p>
                        <p class="text-sm font-medium text-slate-900">{{ $project->projectManagers->count() > 0 ? $project->projectManagers->pluck('name')->join(', ') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Project Status Card -->
            <div class="clean-card p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-4">Project Status</h2>
                
                <div class="bg-amber-50 border border-amber-200 rounded p-3 flex items-start mb-6">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="text-xs text-amber-700">Status is set automatically: from <strong>Bobot Project</strong> total % (→ Finish at 100%); <strong>Done</strong> when all payments are lunas. This section cannot be edited.</p>
                </div>

                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="px-4 py-1.5 border rounded text-sm transition-colors" :class="computedProjectStatus === 'NOT_STARTED' ? 'border-2 border-primary bg-primary-light font-medium text-primary' : 'border-slate-200 text-slate-400 cursor-not-allowed'">Not Started</button>
                    <button class="px-4 py-1.5 border rounded text-sm transition-colors" :class="computedProjectStatus === 'ONGOING' ? 'border-2 border-primary bg-primary-light font-medium text-primary' : 'border-slate-200 text-slate-400 cursor-not-allowed'">Ongoing</button>
                    <button class="px-4 py-1.5 border rounded text-sm transition-colors" :class="computedProjectStatus === 'FINISH' ? 'border-2 border-emerald-500 bg-emerald-50 font-medium text-emerald-600' : 'border-slate-200 text-slate-400 cursor-not-allowed'">Finish</button>
                    <button class="px-4 py-1.5 border rounded text-sm transition-colors" :class="computedProjectStatus === 'DONE' ? 'border-2 border-emerald-500 bg-emerald-50 font-medium text-emerald-600' : 'border-slate-200 text-slate-400 cursor-not-allowed'">Done</button>
                </div>

                <div class="space-y-1 mb-8 text-sm">
                    <p class="text-slate-600 font-medium">Progress: <span class="text-primary font-bold" x-text="(sCurveData && sCurveData.current_actual_progress !== undefined ? parseFloat(sCurveData.current_actual_progress).toFixed(1) : (project.progress || 0)) + '%'"></span></p>
                    <p class="text-xs text-slate-500"><strong>Finish:</strong> Project work completed, payment pending</p>
                    <p class="text-xs text-slate-500"><strong>Done:</strong> Project and payment both completed</p>
                </div>
            </div>

            <!-- Update Assignment Card -->
            <div class="clean-card p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-4">Update AM/PM Assignment</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-2">Account Manager (A/M) <span class="text-red-500">*</span></label>
                        <div wire:ignore>
                            <select id="overview_account_manager_id" x-model="project.account_manager_id" placeholder="-- Select Account Manager --"></select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-2">Project Manager(s) (P/M) <span class="text-red-500">*</span></label>
                        <div wire:ignore>
                            <select id="overview_pm_ids" multiple placeholder="Select project managers..."></select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="updateProjectSettings()" class="bg-primary hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Assignment
                    </button>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'dokumentasi'" x-cloak>
            @include('projects.partials.dokumentasi')
        </div>

        <div x-show="activeTab === 'tasks'" x-cloak>
            @include('projects.partials.task-board')
        </div>
        <div x-show="activeTab === 's-curve'" x-cloak>
            @include('projects.partials.s-curve')
        </div>
        <div x-show="activeTab === 'indirect'" x-cloak>
            @include('projects.partials.indirect-costs')
        </div>
        <div x-show="activeTab === 'payments'" x-cloak>
            @include('projects.partials.payments')
        </div>
        <div x-show="activeTab === 'bobot'" x-cloak>
            @include('projects.partials.bobot')
        </div>
        <div x-show="activeTab === 'settings'" style="display: none;">
            @include('projects.partials.settings')
        </div>

        <div x-show="activeTab === 'issues'" style="display: none;">
            @include('projects.partials.issues')
        </div>
        <!-- Alpine CSS for hiding scrollbar but allowing scroll -->
        <style>
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('projectTracker', (projectId) => ({
            projectId: projectId,
            activeTab: 'overview',
            project: {
                id: {{ $project->id }},
                project_code: @json($project->project_code),
                name: @json($project->name),
                start_date: @json($project->start_date),
                end_date: @json($project->end_date),
                status: @json($project->status),
                spk_no: @json($project->spk_no),
                lokasi: @json($project->lokasi),
                jenis_project_id: {{ $project->jenis_project_id ?? 'null' }},
                bowheer_id: {{ $project->bowheer_id ?? 'null' }},
                customer_id: {{ $project->customer_id ?? 'null' }},
                account_manager_id: {{ $project->account_manager_id ?? 'null' }},
                subkon_ids: @json($project->subkons->pluck('id')),
                subkons: @json($project->subkons),
                pm_ids: @json($project->projectManagers->pluck('id')),
                tasks: @json($project->tasks),
                payments: @json($project->payments),
                indirect_costs: @json($project->indirectCosts),
                issues: @json($project->issues)
            },
            sCurveData: {},
            sCurveViewMode: 'daily',
            masterItems: [],
            masterIndirectCosts: [],
            showTaskSlideover: false,
            showIssueModal: false,
            isEditingTask: false,
            isEditingIssue: false,
            activeTask: { name: '', start_date: '', end_date: '', status: 'TODO', priority: 'MEDIUM', progress_percentage: 0, task_items: [] },
            activeIssue: { id: null, project_task_id: null, title: '', category: '', impact: 'LOW', status: 'OPEN', reported_date: new Date().toISOString().split('T')[0], resolution: '' },

            get computedProjectStatus() {
                if (this.project.status === 'DONE') return 'DONE'; 
                
                const progress = parseFloat(this.sCurveData && this.sCurveData.current_actual_progress !== undefined ? this.sCurveData.current_actual_progress : (this.project.progress || 0));
                
                if (progress >= 100) return 'FINISH';
                if (progress > 0) return 'ONGOING';
                return 'NOT_STARTED';
            },

            init() {
                // Initialize tab from URL hash
                if (window.location.hash) {
                    const hashTab = window.location.hash.substring(1);
                    const validTabs = ['overview', 'tasks', 'indirect', 'payments', 'bobot', 's-curve', 'issues', 'settings'];
                    if (validTabs.includes(hashTab)) {
                        this.activeTab = hashTab;
                    }
                }

                // Sync URL hash when tab changes
                this.$watch('activeTab', (value) => {
                    window.location.hash = value;
                });

                this.$watch('activeTask.progress_percentage', (value) => {
                    const progress = parseInt(value) || 0;
                    if (progress === 0) {
                        this.activeTask.status = 'TODO';
                    } else if (progress >= 100) {
                        this.activeTask.status = 'DONE';
                    } else {
                        this.activeTask.status = 'IN_PROGRESS';
                    }
                });
                this.fetchMasterItems();
                this.fetchMasterIndirectCosts();
                this.fetchSCurve();
                
                // Initialize TomSelects
                setTimeout(() => {
                    this.initTomSelect('edit_jenis_project_id', '/api/miscs?type=jenis_project', false, 'jenis_project_id');
                    this.initTomSelect('edit_bowheer_id', '/api/bowheers', false, 'bowheer_id');
                    this.initTomSelect('edit_customer_id', '/api/customers', false, 'customer_id');
                    this.initTomSelect('edit_subkon_ids', '/api/subkons', true, 'subkon_ids');
                    this.initTomSelect('edit_account_manager_id', '/api/users', false, 'account_manager_id');
                    this.initTomSelect('edit_pm_ids', '/api/users', true, 'pm_ids');

                    // Initialize Overview selects
                    this.initTomSelect('overview_account_manager_id', '/api/users', false, 'account_manager_id');
                    this.initTomSelect('overview_pm_ids', '/api/users', true, 'pm_ids');
                }, 300);
            },

            initTomSelect(id, url, isMultiple, valueProp) {
                let el = document.getElementById(id);
                if (!el) return;
                
                let isMisc = id === 'edit_jenis_project_id';
                
                let ts = new TomSelect(el, {
                    valueField: 'id',
                    labelField: isMisc ? 'value' : 'name',
                    searchField: isMisc ? 'value' : 'name',
                    plugins: isMultiple ? ['remove_button'] : [],
                    create: false,
                    onChange: (value) => {
                        if (this.project) {
                            if (isMultiple) {
                                this.project[valueProp] = Array.isArray(value) 
                                    ? value 
                                    : (value ? value.split(',') : []);
                            } else {
                                this.project[valueProp] = value;
                            }
                        }
                    }
                });
                
                fetch(url).then(res => res.json()).then(data => {
                    if (id === 'edit_account_manager_id' || id === 'overview_account_manager_id') data = data.filter(u => u.type === 'AM');
                    if (id === 'edit_pm_ids' || id === 'overview_pm_ids') data = data.filter(u => u.type === 'PM');
                    
                    ts.addOptions(data);
                    
                    setTimeout(() => {
                        if (this.project && this.project[valueProp]) {
                            ts.setValue(this.project[valueProp]);
                        }
                    }, 500);
                });
            },

            fetchMasterItems() {
                fetch('/api/master-items')
                    .then(res => res.json())
                    .then(data => {
                        this.masterItems = Array.isArray(data) ? data : (data.data || []);
                    });
            },

            fetchMasterIndirectCosts() {
                fetch('/api/master-indirect-costs')
                    .then(res => res.json())
                    .then(data => {
                        this.masterIndirectCosts = Array.isArray(data) ? data : (data.data || []);
                    });
            },

            refreshProject() {
                fetch(`/api/projects/${this.projectId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.project = {
                            id: data.id,
                            project_code: data.project_code,
                            name: data.name,
                            start_date: data.start_date,
                            end_date: data.end_date,
                            status: data.status,
                            spk_no: data.spk_no,
                            lokasi: data.lokasi,
                            jenis_project_id: data.jenis_project_id,
                            bowheer_id: data.bowheer_id,
                            customer_id: data.customer_id,
                            account_manager_id: data.account_manager_id,
                            subkon_ids: data.subkons ? data.subkons.map(s => s.id) : [],
                            subkons: data.subkons || [],
                            pm_ids: data.project_managers ? data.project_managers.map(pm => pm.id) : [],
                            tasks: data.tasks || [],
                            payments: data.payments || [],
                            indirect_costs: data.indirect_costs || [],
                            issues: data.issues || []
                        };
                    });
            },

            openNewTask() {
                this.isEditingTask = false;
                this.activeTask = { name: '', start_date: '', end_date: '', status: 'TODO', priority: 'MEDIUM', progress_percentage: 0, task_items: [] };
                this.showTaskSlideover = true;
            },

            openTaskDetail(task) {
                this.isEditingTask = true;
                this.activeTask = JSON.parse(JSON.stringify(task));
                if (!this.activeTask.task_items) this.activeTask.task_items = [];
                this.showTaskSlideover = true;
            },

            closeSlideover() {
                this.showTaskSlideover = false;
            },

            addActiveRabRow() {
                this.activeTask.task_items.push({ master_item_id: '', qty: 1, harga_satuan: 0, modal_satuan: 0 });
            },

            updateActiveRabPrice(index) {
                const rab = this.activeTask.task_items[index];
                if (!rab.master_item_id) return;
                
                if(rab.harga_satuan === undefined) rab.harga_satuan = 0;
                if(rab.modal_satuan === undefined) rab.modal_satuan = 0;
            },

            calculateActiveTaskTotal() {
                if(!this.activeTask || !this.activeTask.task_items) return 0;
                return this.activeTask.task_items.reduce((sum, item) => sum + (parseFloat(item.qty) * parseFloat(item.harga_satuan) || 0), 0);
            },

            calculateActiveTaskModal() {
                if(!this.activeTask || !this.activeTask.task_items) return 0;
                return this.activeTask.task_items.reduce((sum, item) => sum + (parseFloat(item.qty) * parseFloat(item.modal_satuan) || 0), 0);
            },

            calculateActiveTaskProfit() {
                return this.calculateActiveTaskTotal() - this.calculateActiveTaskModal();
            },

            calculateActiveTaskProfitPercentage() {
                const total = this.calculateActiveTaskTotal();
                if(total === 0) return 0;
                return ((this.calculateActiveTaskProfit() / total) * 100).toFixed(1);
            },

            saveActiveTask() {
                let p = parseInt(this.activeTask.progress_percentage) || 0;
                if (p === 0) this.activeTask.status = 'TODO';
                else if (p >= 100) this.activeTask.status = 'DONE';
                else this.activeTask.status = 'IN_PROGRESS';

                const taskData = {
                    project_id: this.projectId,
                    name: this.activeTask.name,
                    start_date: this.activeTask.start_date,
                    end_date: this.activeTask.end_date,
                    status: this.activeTask.status,
                    priority: this.activeTask.priority,
                    progress_percentage: this.activeTask.progress_percentage
                };

                const endpoint = this.isEditingTask ? `/api/tasks/${this.activeTask.id}` : '/api/tasks';
                const method = this.isEditingTask ? 'PUT' : 'POST';

                fetch(endpoint, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(taskData)
                })
                .then(res => res.json())
                .then(savedTask => {
                    const taskId = savedTask.id || this.activeTask.id;
                    
                    fetch(`/api/tasks/${taskId}/sync-items`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({
                            task_items: this.activeTask.task_items.filter(rab => rab.master_item_id).map(rab => ({
                                master_item_id: rab.master_item_id,
                                qty: rab.qty,
                                harga_satuan: rab.harga_satuan,
                                modal_satuan: rab.modal_satuan || 0
                            }))
                        })
                    }).then(() => {
                        this.closeSlideover();
                        this.refreshProject();
                        
                        setTimeout(() => {
                            this.fetchSCurve();
                        }, 500);
                    });
                });
            },

            formatDateShort(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            },

            updateProgress(taskId, progress) {
                let newStatus = 'IN_PROGRESS';
                let p = parseInt(progress) || 0;
                if (p === 0) newStatus = 'TODO';
                else if (p >= 100) newStatus = 'DONE';

                fetch(`/api/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ progress_percentage: progress, status: newStatus })
                }).then(res => res.json()).then(data => {
                    let task = this.project.tasks.find(t => t.id === taskId);
                    if(task) {
                        task.progress_percentage = data.progress_percentage;
                        task.status = data.status; 
                    }
                    this.fetchSCurve(); 
                });
            },

            getIndirectCostSatuan(master_id) {
                if (!master_id) return '';
                const item = this.masterIndirectCosts.find(i => i.id == master_id);
                return item ? item.satuan : '';
            },

            addIndirectCostRow() {
                if (!this.project.indirect_costs) this.project.indirect_costs = [];
                this.project.indirect_costs.push({ master_indirect_cost_id: '', qty: 1, harga_satuan: 0, harga_total: 0 });
            },

            removeIndirectCostRow(index) {
                if (confirm('Are you sure you want to remove this row?')) {
                    this.project.indirect_costs.splice(index, 1);
                }
            },

            syncIndirectCosts() {
                const costsToSync = (this.project.indirect_costs || []).filter(c => c.master_indirect_cost_id);
                fetch(`/api/projects/${this.projectId}/indirect-costs/sync`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ indirect_costs: costsToSync })
                })
                .then(res => res.json())
                .then(data => {
                    this.refreshProject();
                    this.$dispatch('notify', { msg: 'Indirect costs saved successfully!', type: 'success' });
                })
                .catch(err => {
                    this.$dispatch('notify', { msg: 'Failed to save indirect costs.', type: 'error' });
                    console.error(err);
                });
            },

            addPaymentRow(type, subkonId = null) {
                if(!this.project.payments) this.project.payments = [];
                this.project.payments.push({
                    id: null,
                    type: type,
                    subkon_id: subkonId,
                    invoice: '',
                    keterangan: '',
                    nilai: 0,
                    tanggal: null,
                    tanggal_payment: null,
                    nilai_payment: 0
                });
            },

            removePaymentRow(index) {
                this.project.payments.splice(index, 1);
            },

            savePayments() {
                const subkonsData = (this.project.subkons || []).map(sub => ({
                    subkon_id: sub.id,
                    payment_term: sub.pivot ? sub.pivot.payment_term : ''
                }));

                fetch(`/api/projects/${this.projectId}/payments/sync`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        payment_term: this.project.payment_term,
                        subkons: subkonsData,
                        payments: this.project.payments || []
                    })
                }).then(res => {
                    if (res.ok) {
                        this.$dispatch('notify', { msg: 'Payments saved successfully!', type: 'success' });
                        this.refreshProject();
                    } else {
                        this.$dispatch('notify', { msg: 'Failed to save payments.', type: 'error' });
                    }
                });
            },
            
            updateProjectSettings() {
                fetch(`/api/projects/${this.projectId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        name: this.project.name,
                        start_date: this.project.start_date,
                        end_date: this.project.end_date,
                        spk_no: this.project.spk_no,
                        lokasi: this.project.lokasi,
                        jenis_project_id: this.project.jenis_project_id,
                        bowheer_id: this.project.bowheer_id,
                        customer_id: this.project.customer_id,
                        account_manager_id: this.project.account_manager_id,
                        subkon_ids: this.project.subkon_ids,
                        pm_ids: this.project.pm_ids
                    })
                }).then(res => {
                    if(res.ok) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Project settings updated successfully!', type: 'success' } }));
                        this.refreshProject();
                    }
                });
            },

            calculateProjectTotal() {
                if (!this.project || !this.project.tasks) return 0;
                return this.project.tasks.reduce((total, task) => total + this.calculateTaskTotal(task), 0);
            },

            calculateTaskTotal(task) {
                if (!task.task_items) return 0;
                return task.task_items.reduce((sum, item) => sum + parseFloat(item.total_harga), 0);
            },

            // --- ISSUES LOGIC ---
            openNewIssue() {
                this.isEditingIssue = false;
                this.activeIssue = { id: null, project_task_id: null, title: '', category: '', impact: 'LOW', status: 'OPEN', reported_date: new Date().toISOString().split('T')[0], resolution: '' };
                this.showIssueModal = true;
            },

            editIssue(issue) {
                this.isEditingIssue = true;
                this.activeIssue = { ...issue };
                this.showIssueModal = true;
            },

            saveIssue() {
                const isEdit = this.isEditingIssue;
                const url = isEdit ? `/api/issues/${this.activeIssue.id}` : '/api/issues';
                const method = isEdit ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        ...this.activeIssue,
                        project_id: this.projectId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.showIssueModal = false;
                    this.refreshProject();
                    this.$dispatch('notify', { msg: isEdit ? 'Issue updated!' : 'Issue reported!', type: 'success' });
                });
            },

            updateIssueStatus(issue, newStatus) {
                const url = `/api/issues/${issue.id}`;
                fetch(url, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        ...issue,
                        status: newStatus
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.refreshProject();
                    this.$dispatch('notify', { msg: 'Issue status updated to ' + newStatus, type: 'success' });
                });
            },

            deleteIssue(id) {
                if (confirm('Are you sure you want to delete this issue log?')) {
                    fetch(`/api/issues/${id}`, { method: 'DELETE' })
                        .then(() => {
                            this.refreshProject();
                            this.$dispatch('notify', { msg: 'Issue deleted.', type: 'info' });
                        });
                }
            },

            fetchSCurve() {
                fetch(`/api/projects/${this.projectId}/s-curve`)
                    .then(res => res.json())
                    .then(data => {
                        this.sCurveData = data;
                        this.renderChart();
                    });
            },

            renderChart() {
                const ctx = document.getElementById('scurveChart');
                if (!ctx) return;
                
                let existingChart = Chart.getChart(ctx);
                if (existingChart) {
                    existingChart.destroy();
                }

                if(!this.sCurveData.planned_curve) return;

                let rawLabels = this.sCurveData.planned_curve.map(c => c.date);
                let rawPlannedData = this.sCurveData.planned_curve.map(c => c.planned_cumulative);
                let rawActualData = new Array(rawLabels.length).fill(null);
                
                if (this.sCurveData.actual_curve && this.sCurveData.actual_curve.length > 0) {
                    rawActualData[0] = 0;
                    this.sCurveData.actual_curve.forEach(history => {
                        let idx = rawLabels.indexOf(history.date);
                        if (idx !== -1) {
                            rawActualData[idx] = history.actual_cumulative;
                        }
                    });
                }
                
                const actualProgress = parseFloat(this.sCurveData.current_actual_progress);
                if (actualProgress >= 0 && rawLabels.length > 0 && !rawActualData.some(d => d !== null)) {
                    rawActualData[0] = 0; 
                    
                    const today = new Date().toISOString().split('T')[0];
                    let todayIndex = rawLabels.indexOf(today);
                    
                    if (todayIndex === -1) {
                        if (new Date(today) > new Date(rawLabels[rawLabels.length - 1])) {
                            todayIndex = rawLabels.length - 1;
                        } else {
                            todayIndex = 0;
                        }
                    }
                    
                    if (todayIndex > 0) {
                        rawActualData[todayIndex] = actualProgress;
                    } else {
                        rawActualData[0] = actualProgress;
                    }
                }

                let labels = [];
                let plannedData = [];
                let actualData = [];

                if (this.sCurveViewMode === 'weekly') {
                    let filledActual = [];
                    let lastKnown = null;
                    const todayStr = new Date().toISOString().split('T')[0];
                    let pastToday = false;

                    for (let i = 0; i < rawLabels.length; i++) {
                        if (rawLabels[i] > todayStr) pastToday = true;
                        
                        if (rawActualData[i] !== null) {
                            lastKnown = rawActualData[i];
                        }
                        
                        if (pastToday && rawActualData[i] === null) {
                            filledActual.push(null);
                        } else {
                            filledActual.push(lastKnown);
                        }
                    }

                    for (let i = 0; i < rawLabels.length; i += 7) {
                        let weekEndIndex = Math.min(i + 6, rawLabels.length - 1);
                        labels.push(`Week ${Math.floor(i/7) + 1} (${rawLabels[weekEndIndex]})`);
                        plannedData.push(rawPlannedData[weekEndIndex]);
                        actualData.push(filledActual[weekEndIndex]);
                    }
                } else {
                    labels = rawLabels;
                    plannedData = rawPlannedData;
                    actualData = rawActualData;
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Rencana (Planned %)',
                                data: plannedData,
                                borderColor: '#2563eb', 
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                tension: 0.4,
                                cubicInterpolationMode: 'monotone',
                                fill: true
                            },
                            {
                                label: 'Realisasi (Actual %)',
                                data: actualData,
                                borderColor: '#10b981',
                                backgroundColor: 'transparent',
                                tension: 0.4,
                                cubicInterpolationMode: 'monotone',
                                borderDash: [5, 5],
                                spanGaps: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { max: 100 } }
                    }
                });
            }
        }));
    });
</script>
@endpush
