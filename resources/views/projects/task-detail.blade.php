@extends('layouts.app')
@section('title', $task->name . ' - ' . $project->name)

@section('content')
<div x-data="taskDetail()" class="min-h-screen bg-white">

    <!-- Header -->
    <div class="px-3 sm:px-8 pt-4 pb-2">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
            <div class="flex items-center">
                <button onclick="history.back()" class="text-slate-400 hover:text-slate-600 mr-2 sm:mr-4 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </button>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 mr-2 sm:mr-4 leading-tight">{{ $task->name }}</h1>
            </div>

            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border self-start sm:self-auto"
                  :class="{
                      'bg-emerald-50 text-emerald-600 border-emerald-100': task.status === 'DONE',
                      'bg-slate-50 text-slate-600 border-slate-200': task.status === 'TODO',
                      'bg-blue-50 text-blue-600 border-blue-100': task.status === 'IN_PROGRESS'
                  }">
                <span x-text="task.status === 'TODO' ? 'To Do' : (task.status === 'IN_PROGRESS' ? 'In Progress' : 'Done')"></span>
                (<span x-text="parseFloat(task.progress_percentage).toFixed(1)"></span>%)
            </span>
        </div>
        <div class="flex items-center text-xs text-slate-500 pl-6 sm:pl-9 space-x-2">
            <span class="uppercase font-medium">{{ $project->project_code }}</span>
            <span class="text-slate-300">•</span>
            <span class="font-medium text-slate-600">{{ \Carbon\Carbon::parse($task->start_date)->translatedFormat('d M Y') }} → {{ \Carbon\Carbon::parse($task->end_date)->translatedFormat('d M Y') }}</span>
            <span class="text-slate-300">•</span>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider"
                  :class="{
                      'bg-slate-100 text-slate-600': task.priority === 'LOW',
                      'bg-amber-100 text-amber-700': task.priority === 'MEDIUM',
                      'bg-red-100 text-red-700': task.priority === 'HIGH'
                  }" x-text="task.priority || 'LOW'">
            </span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="px-3 sm:px-8 border-b border-slate-200 mt-4 sm:mt-6 relative">
        <nav class="-mb-px flex flex-row space-x-4 sm:space-x-8 text-xs sm:text-sm">
            <button @click="activeTab = 'timeline'" :class="activeTab === 'timeline' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Timeline
            </button>
            <button @click="activeTab = 'rab'" :class="activeTab === 'rab' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                RAB
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="px-3 py-3 sm:px-4 sm:py-4 md:px-6 md:py-6 lg:px-8 lg:py-8 bg-slate-50 min-h-screen">

        <!-- Timeline Tab -->
        <div x-show="activeTab === 'timeline'" x-cloak class="space-y-6">

            <!-- PLAN Section -->
            <div class="clean-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Plan</h2>
                        <template x-if="task.is_plan_locked">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Locked
                            </span>
                        </template>
                    </div>
                    <template x-if="task.is_plan_locked">
                        <button @click="unlockPlan()" class="text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-md font-medium transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            Ubah
                        </button>
                    </template>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <table class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-16">Week</th>
                                <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Periode</th>
                                <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-32">Progress (%)</th>
                                <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <template x-for="(week, index) in planWeeks" :key="week.id">
                                <tr class="hover:bg-slate-50 transition-colors" :class="week.is_extra ? 'bg-orange-50' : ''">
                                    <td class="px-4 py-2.5 text-sm font-medium text-slate-700" :class="week.is_extra ? 'text-orange-600' : ''">
                                        <span x-text="'W' + week.week_number"></span>
                                        <template x-if="week.is_extra">
                                            <span class="ml-2 px-1.5 py-0.5 rounded text-[9px] font-bold bg-orange-100 text-orange-600">EXTRA</span>
                                        </template>
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-slate-500">
                                        <span x-text="formatDate(week.start_date) + ' — ' + formatDate(week.end_date)"></span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <template x-if="!task.is_plan_locked">
                                            <input type="number" x-model="week.progress_percentage" min="0" max="100" step="0.01"
                                                   class="w-20 text-xs text-center border border-slate-300 rounded px-2 py-1.5 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                                        </template>
                                        <template x-if="task.is_plan_locked">
                                            <span class="text-xs font-medium text-slate-700" x-text="parseFloat(week.progress_percentage).toFixed(2) + '%'"></span>
                                        </template>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <template x-if="week.is_extra && !task.is_plan_locked">
                                            <button @click="deletePlanWeek(week, index)" class="text-slate-400 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-between space-x-3">
                    <template x-if="!task.is_plan_locked">
                        <button @click="addExtraPlanWeek()" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md font-medium transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Week
                        </button>
                    </template>
                    <template x-if="task.is_plan_locked">
                        <div></div>
                    </template>
                    <template x-if="!task.is_plan_locked">
                        <div class="flex space-x-3">
                            <button @click="savePlan()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Plan
                            </button>
                            <button @click="lockPlan()" class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors shadow-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Simpan Permanen
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- REALISASI Section -->
            <div class="clean-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Realisasi</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-medium text-slate-500">Progress Task:</span>
                        <span class="text-primary font-bold text-lg bg-blue-50 px-2 py-0.5 rounded" x-text="parseFloat(task.progress_percentage).toFixed(1) + '%'"></span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <table class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-16">Week</th>
                                <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Periode</th>
                                <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-32">Progress (%)</th>
                                <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <template x-for="(week, index) in realisasiWeeks" :key="week.id">
                                <tr class="hover:bg-slate-50 transition-colors" :class="week.is_extra ? 'bg-orange-50/50' : ''">
                                    <td class="px-4 py-2.5 text-sm font-medium" :class="week.is_extra ? 'text-orange-700' : 'text-slate-700'">
                                        <span x-text="'W' + week.week_number"></span>
                                        <template x-if="week.is_extra">
                                            <span class="ml-1 text-[9px] bg-orange-100 text-orange-600 px-1 py-0.5 rounded font-semibold">EXTRA</span>
                                        </template>
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-slate-500">
                                        <span x-text="formatDate(week.start_date) + ' — ' + formatDate(week.end_date)"></span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <input type="number" x-model="week.progress_percentage" min="0" max="100" step="0.01"
                                               class="w-20 text-xs text-center border border-slate-300 rounded px-2 py-1.5 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <template x-if="week.is_extra">
                                            <button @click="deleteRealisasiWeek(week, index)" class="text-slate-400 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-between">
                    <button @click="addExtraWeek()" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md font-medium transition-colors flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Week
                    </button>
                    <button @click="saveRealisasi()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Realisasi
                    </button>
                </div>
            </div>
        </div>

        <!-- RAB Tab -->
        <div x-show="activeTab === 'rab'" x-cloak class="space-y-6">
            <div class="clean-card overflow-hidden">
                <div class="border-b border-slate-200">
                    <nav class="flex -mb-px px-6 space-x-6 text-sm">
                        <button @click="activeRabTab = 'plan'" :class="activeRabTab === 'plan' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-4 border-b-2 transition-colors">
                            Plan RAB
                        </button>
                        <button @click="activeRabTab = 'realisasi'" :class="activeRabTab === 'realisasi' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-4 border-b-2 transition-colors">
                            Realisasi RAB
                        </button>
                    </nav>
                </div>

                <!-- Plan RAB Tab -->
                <div x-show="activeRabTab === 'plan'" class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Plan RAB Items (Budget)</label>
                        <button @click="addRabRow()" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md font-medium transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Item
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm overflow-x-auto">
                        <table class="min-w-[900px] w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[150px]">Item</th>
                                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[80px]">Qty</th>
                                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[120px]">Harga Jual</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Total Jual</th>
                                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[120px]">Harga Modal</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Total Modal</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Profit</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[60px]">%</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[40px]"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="(rab, index) in task.task_items" :key="index">
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-3 py-2">
                                            <select x-model="rab.master_item_id" @change="updateRabPrice(index)" class="w-full text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none bg-white min-w-[150px]">
                                                <option value="">-- Select Item --</option>
                                                <template x-for="item in masterItems" :key="item.id">
                                                    <option :value="item.id" x-text="item.name + ' (' + item.satuan + ')'"></option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" x-model="rab.qty" min="1" class="w-full min-w-[60px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-center">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" x-model="rab.harga_satuan" min="0" class="w-full min-w-[90px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-right">
                                        </td>
                                        <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap" x-text="'Rp ' + (rab.qty * rab.harga_satuan).toLocaleString('id-ID')"></td>
                                        <td class="px-3 py-2">
                                            <input type="number" x-model="rab.modal_satuan" min="0" class="w-full min-w-[90px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-right">
                                        </td>
                                        <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap" x-text="'Rp ' + (rab.qty * (rab.modal_satuan || 0)).toLocaleString('id-ID')"></td>
                                        <td class="px-3 py-2 text-right text-xs font-bold text-emerald-600 whitespace-nowrap" x-text="'Rp ' + ((rab.qty * rab.harga_satuan) - (rab.qty * (rab.modal_satuan || 0))).toLocaleString('id-ID')"></td>
                                        <td class="px-3 py-2 text-center text-[10px] font-bold text-blue-600" x-text="rab.harga_satuan > 0 ? (((rab.harga_satuan - (rab.modal_satuan || 0)) / rab.harga_satuan) * 100).toFixed(1) + '%' : '0%'"></td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" @click="task.task_items.splice(index, 1)" class="text-slate-300 hover:text-red-500 p-1 rounded hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="!task.task_items || task.task_items.length === 0">
                                    <tr>
                                        <td colspan="9" class="px-4 py-8 text-center">
                                            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            <p class="text-sm font-medium text-slate-500">No Plan RAB items</p>
                                            <p class="text-xs text-slate-400 mt-1">Add items to define the budget for this task.</p>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Totals Footer -->
                                <tr class="bg-slate-50">
                                    <td colspan="3" class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Total Proyeksi</td>
                                    <td class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap" x-text="'Rp ' + calculateRabTotal().toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Total Modal</td>
                                    <td class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap" x-text="'Rp ' + calculateRabModal().toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-right text-xs font-bold text-emerald-600 whitespace-nowrap" x-text="'Rp ' + calculateRabProfit().toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-center text-[10px] font-bold text-blue-600 bg-blue-50" x-text="calculateRabProfitPercentage() + '%'"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button @click="saveRab()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Plan RAB
                        </button>
                    </div>
                </div>

                <!-- Realisasi RAB Tab -->
                <div x-show="activeRabTab === 'realisasi'" class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Realisasi RAB Items</label>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-slate-500">Pilih Minggu:</span>
                            <select x-model="selectedRealisasiWeekId" class="text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none">
                                <template x-for="week in realisasiWeeks" :key="week.id">
                                    <option :value="week.id" x-text="'W' + week.week_number + ' (' + formatDate(week.start_date) + ' - ' + formatDate(week.end_date) + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm overflow-x-auto">
                        <table class="min-w-[1000px] w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[150px]" rowspan="2">Item</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-x border-slate-200" colspan="3">Plan</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-x border-slate-200" colspan="3">Realisasi (Minggu Ini)</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-r border-slate-200" rowspan="2">Total Realisasi Keseluruhan</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider" rowspan="2">Selisih</th>
                                </tr>
                                <tr>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[80px]">Qty</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Harga Jual</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px] border-r border-slate-200">Total Jual</th>
                                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[80px]">Qty</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Harga Jual</th>
                                    <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px] border-r border-slate-200">Total Jual</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="(rab, index) in task.task_items" :key="index">
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-3 py-2 text-xs">
                                            <span x-text="getMasterItemName(rab.master_item_id)"></span>
                                        </td>
                                        <td class="px-3 py-2 text-center text-xs text-slate-500" x-text="rab.qty"></td>
                                        <td class="px-3 py-2 text-right text-xs text-slate-500" x-text="'Rp ' + parseFloat(rab.harga_satuan).toLocaleString('id-ID')"></td>
                                        <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + (rab.qty * rab.harga_satuan).toLocaleString('id-ID')"></td>

                                        <td class="px-3 py-2">
                                            <input type="number" x-model="getRealisasi(rab).qty_realisasi" min="0" class="w-full min-w-[60px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-center">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" x-model="getRealisasi(rab).harga_satuan_realisasi" min="0" class="w-full min-w-[90px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-right">
                                        </td>
                                        <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + (getRealisasi(rab).qty_realisasi * getRealisasi(rab).harga_satuan_realisasi).toLocaleString('id-ID')"></td>

                                        <td class="px-3 py-2 text-center text-xs font-medium text-slate-700 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + calculateTotalRealisasiItem(rab).toLocaleString('id-ID')"></td>

                                        <td class="px-3 py-2 text-center text-xs font-bold whitespace-nowrap"
                                            :class="(rab.qty * rab.harga_satuan) - calculateTotalRealisasiItem(rab) >= 0 ? 'text-emerald-600' : 'text-red-600'"
                                            x-text="'Rp ' + ((rab.qty * rab.harga_satuan) - calculateTotalRealisasiItem(rab)).toLocaleString('id-ID')">
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="!task.task_items || task.task_items.length === 0">
                                    <tr>
                                        <td colspan="9" class="px-4 py-8 text-center">
                                            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            <p class="text-sm font-medium text-slate-500">No RAB items</p>
                                            <p class="text-xs text-slate-400 mt-1">Add items in Plan RAB to see realisasi data.</p>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Totals Footer -->
                                <tr class="bg-slate-50 border-t-2 border-slate-200">
                                    <td class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Total</td>
                                    <td colspan="3" class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + calculateRabTotal().toLocaleString('id-ID')"></td>
                                    <td colspan="3" class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + calculateRabRealisasiCurrentWeekTotal().toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-center text-xs font-bold text-slate-800 whitespace-nowrap border-r border-slate-200" x-text="'Rp ' + calculateRabRealisasiTotal().toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-center text-xs font-bold whitespace-nowrap"
                                        :class="(calculateRabTotal() - calculateRabRealisasiTotal()) >= 0 ? 'text-emerald-600' : 'text-red-600'"
                                        x-text="'Rp ' + (calculateRabTotal() - calculateRabRealisasiTotal()).toLocaleString('id-ID')">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button @click="saveRab()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Realisasi RAB
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Task Footer -->
    <div class="px-3 sm:px-8 py-4 bg-white border-t border-slate-200">
        <div class="flex justify-between items-center">
            <button @click="deleteTask()" class="px-5 py-2 border border-red-200 text-red-600 bg-red-50 rounded-lg text-sm font-semibold hover:bg-red-100 transition-colors focus:ring-2 focus:ring-red-200 outline-none flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete Task
            </button>
            <a href="{{ route('projects.show', $project->id) }}#tasks" class="px-5 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                Kembali ke Task Board
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('taskDetail', () => ({
            activeTab: 'timeline',
            activeRabTab: 'plan',
            task: @json($task),
            planWeeks: @json($task->planWeeks),
            realisasiWeeks: @json($task->realisasiWeeks),
            masterItems: @json($masterItems),
            projectId: {{ $project->id }},
            taskId: {{ $task->id }},
            selectedRealisasiWeekId: null,

            init() {
                if(this.realisasiWeeks && this.realisasiWeeks.length > 0) {
                    this.selectedRealisasiWeekId = this.realisasiWeeks[0].id;
                }
                
                // Initialize realisasis arrays if missing
                if (this.task && this.task.task_items) {
                    this.task.task_items.forEach(item => {
                        if (!item.realisasis) {
                            item.realisasis = [];
                        }
                    });
                }
            },

            formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            },

            getMasterItemName(id) {
                const item = this.masterItems.find(i => i.id == id);
                return item ? item.name + ' (' + item.satuan + ')' : '-';
            },

            // --- PLAN ---
            savePlan() {
                const weeks = this.planWeeks.map(w => ({
                    id: w.id,
                    progress_percentage: parseFloat(w.progress_percentage) || 0
                }));

                fetch(`/api/tasks/${this.taskId}/timeline/plan`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ weeks })
                })
                .then(res => {
                    if (res.ok) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Plan saved successfully!', type: 'success' } }));
                    } else {
                        return res.json().then(data => {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { msg: data.message || 'Failed to save plan.', type: 'error' } }));
                        });
                    }
                });
            },

            lockPlan() {
                if (!confirm('Apakah Anda yakin ingin menyimpan plan secara permanen? Plan akan di-lock dan tidak bisa diubah tanpa menekan tombol "Ubah".')) return;

                // Save plan first, then lock
                const weeks = this.planWeeks.map(w => ({
                    id: w.id,
                    progress_percentage: parseFloat(w.progress_percentage) || 0
                }));

                fetch(`/api/tasks/${this.taskId}/timeline/plan`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ weeks })
                })
                .then(res => res.json())
                .then(() => {
                    return fetch(`/api/tasks/${this.taskId}/timeline/plan/lock`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                    });
                })
                .then(res => res.json())
                .then(data => {
                    this.task.is_plan_locked = data.is_plan_locked;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Plan locked permanently!', type: 'success' } }));
                });
            },

            unlockPlan() {
                if (!confirm('Apakah Anda yakin ingin membuka lock plan? Data plan bisa diubah kembali.')) return;

                fetch(`/api/tasks/${this.taskId}/timeline/plan/unlock`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    this.task.is_plan_locked = data.is_plan_locked;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Plan unlocked!', type: 'success' } }));
                });
            },

            addExtraPlanWeek() {
                fetch(`/api/tasks/${this.taskId}/timeline/plan/add-week`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(newWeek => {
                    if(newWeek.id) {
                        this.planWeeks.push(newWeek);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Extra plan week added!', type: 'success' } }));
                    } else {
                         window.dispatchEvent(new CustomEvent('notify', { detail: { msg: newWeek.message || 'Error adding week.', type: 'error' } }));
                    }
                });
            },

            deletePlanWeek(week, index) {
                if(!confirm('Delete this extra week?')) return;
                
                fetch(`/api/tasks/${this.taskId}/timeline/weeks/${week.id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    if (res.ok) {
                        this.planWeeks.splice(index, 1);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Week deleted successfully!', type: 'success' } }));
                    } else {
                         return res.json().then(data => {
                             window.dispatchEvent(new CustomEvent('notify', { detail: { msg: data.message || 'Error deleting week.', type: 'error' } }));
                         });
                    }
                });
            },


            // --- REALISASI ---
            saveRealisasi() {
                const weeks = this.realisasiWeeks.map(w => ({
                    id: w.id,
                    progress_percentage: parseFloat(w.progress_percentage) || 0
                }));

                fetch(`/api/tasks/${this.taskId}/timeline/realisasi`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ weeks })
                })
                .then(res => res.json())
                .then(data => {
                    this.task.progress_percentage = data.progress_percentage;
                    this.task.status = data.status;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Realisasi saved successfully!', type: 'success' } }));
                });
            },

            addExtraWeek() {
                fetch(`/api/tasks/${this.taskId}/timeline/realisasi/add-week`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(newWeek => {
                    if(newWeek.id) {
                        this.realisasiWeeks.push(newWeek);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Extra week added!', type: 'success' } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: newWeek.message || 'Error adding week.', type: 'error' } }));
                    }
                });
            },

            deleteRealisasiWeek(week, index) {
                if(!confirm('Delete this extra week?')) return;
                
                fetch(`/api/tasks/${this.taskId}/timeline/weeks/${week.id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    if (res.ok) {
                        this.realisasiWeeks.splice(index, 1);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Week deleted successfully!', type: 'success' } }));
                    } else {
                         return res.json().then(data => {
                             window.dispatchEvent(new CustomEvent('notify', { detail: { msg: data.message || 'Error deleting week.', type: 'error' } }));
                         });
                    }
                });
            },

            // --- RAB ---
            addRabRow() {
                if (!this.task.task_items) this.task.task_items = [];
                this.task.task_items.push({ master_item_id: '', qty: 1, harga_satuan: 0, modal_satuan: 0, qty_realisasi: 0, harga_satuan_realisasi: 0 });
            },

            updateRabPrice(index) {
                const rab = this.task.task_items[index];
                if (!rab.master_item_id) return;
                const selected = this.masterItems.find(i => i.id == rab.master_item_id);
                if (selected) {
                    if (!rab.harga_satuan || rab.harga_satuan == 0) {
                        rab.harga_satuan = parseFloat(selected.harga) || 0;
                    }
                    if (!rab.modal_satuan || rab.modal_satuan == 0) {
                        rab.modal_satuan = parseFloat(selected.modal) || 0;
                    }
                }
            },

            calculateRabTotal() {
                if (!this.task || !this.task.task_items) return 0;
                return this.task.task_items.reduce((sum, item) => sum + (parseFloat(item.qty) * parseFloat(item.harga_satuan) || 0), 0);
            },

            calculateRabModal() {
                if (!this.task || !this.task.task_items) return 0;
                return this.task.task_items.reduce((sum, item) => sum + (parseFloat(item.qty) * parseFloat(item.modal_satuan) || 0), 0);
            },

            calculateRabProfit() {
                return this.calculateRabTotal() - this.calculateRabModal();
            },

            calculateRabProfitPercentage() {
                const total = this.calculateRabTotal();
                if (total === 0) return 0;
                return ((this.calculateRabProfit() / total) * 100).toFixed(1);
            },

            getRealisasi(rab) {
                if (!this.selectedRealisasiWeekId) return { qty_realisasi: 0, harga_satuan_realisasi: 0 };
                let r = rab.realisasis.find(x => x.task_timeline_week_id == this.selectedRealisasiWeekId);
                if (!r) {
                    r = { task_timeline_week_id: this.selectedRealisasiWeekId, qty_realisasi: 0, harga_satuan_realisasi: 0 };
                    rab.realisasis.push(r);
                }
                return r;
            },

            calculateTotalRealisasiItem(rab) {
                if (!rab.realisasis) return 0;
                return rab.realisasis.reduce((sum, r) => sum + (parseFloat(r.qty_realisasi || 0) * parseFloat(r.harga_satuan_realisasi || 0) || 0), 0);
            },

            calculateRabRealisasiCurrentWeekTotal() {
                if (!this.task || !this.task.task_items) return 0;
                return this.task.task_items.reduce((sum, item) => {
                    let r = this.getRealisasi(item);
                    return sum + (parseFloat(r.qty_realisasi || 0) * parseFloat(r.harga_satuan_realisasi || 0) || 0);
                }, 0);
            },

            calculateRabRealisasiTotal() {
                if (!this.task || !this.task.task_items) return 0;
                return this.task.task_items.reduce((sum, item) => sum + this.calculateTotalRealisasiItem(item), 0);
            },

            saveRab() {
                fetch(`/api/tasks/${this.taskId}/sync-items`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        task_items: this.task.task_items.filter(rab => rab.master_item_id).map(rab => ({
                            master_item_id: rab.master_item_id,
                            qty: rab.qty,
                            harga_satuan: rab.harga_satuan,
                            modal_satuan: rab.modal_satuan || 0,
                            realisasis: rab.realisasis || []
                        }))
                    })
                }).then(res => {
                    if (res.ok) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'RAB saved successfully!', type: 'success' } }));
                    }
                });
            },

            // --- DELETE TASK ---
            deleteTask() {
                if (!confirm('Apakah Anda yakin ingin menghapus task ini? Semua data timeline dan RAB akan ikut terhapus.')) return;

                fetch(`/api/tasks/${this.taskId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                }).then(res => {
                    if (res.ok) {
                        window.location.href = '{{ route("projects.show", $project->id) }}#tasks';
                    }
                });
            }
        }));
    });
</script>
@endpush
