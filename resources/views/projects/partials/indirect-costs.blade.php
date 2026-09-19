<div class="clean-card p-4 sm:p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-sm font-bold text-slate-800">Indirect Cost</h2>
            <p class="text-xs text-slate-500 mt-1">Project ID: <span x-text="project.project_code || '{{ $project->project_code ?? '' }}'"></span></p>
        </div>
        <!-- Lock Plan Button (Triggers Validation Modal) -->
        <button @click="openLockPlanModal()" class="flex items-center px-3 py-1.5 border rounded text-xs font-medium transition-colors shadow-xs"
                :class="project.is_indirect_cost_locked ? 'border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-slate-300 text-slate-700 hover:bg-slate-50'">
            <template x-if="project.is_indirect_cost_locked">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Plan Locked
                </span>
            </template>
            <template x-if="!project.is_indirect_cost_locked">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    Lock Plan
                </span>
            </template>
        </button>
    </div>

    <!-- Summary Bar (Total Plan, Total Realisasi, Sisa Dana) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 border border-slate-100 rounded p-4">
        <div>
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL PLAN</p>
            <p class="text-base font-bold text-slate-900 mt-1" x-text="'Rp ' + calculateIndirectCostPlanTotal().toLocaleString('id-ID')"></p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL REALISASI</p>
            <p class="text-base font-bold text-slate-900 mt-1" x-text="'Rp ' + calculateIndirectCostActualTotal().toLocaleString('id-ID')"></p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">SISA DANA</p>
            <p class="text-base font-bold mt-1" :class="calculateIndirectCostSisaTotal() >= 0 ? 'text-emerald-600' : 'text-red-600'" x-text="'Rp ' + calculateIndirectCostSisaTotal().toLocaleString('id-ID')"></p>
        </div>
    </div>

    <!-- SECTION 1: PLAN INDIRECT COST -->
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Plan Indirect Cost</h3>
        </div>
        
        <div class="overflow-x-auto border border-slate-100 rounded bg-white shadow-xs">
            <table class="w-full divide-y divide-slate-100 table-fixed">
                <colgroup>
                    <col style="width: 4%;">
                    <col style="width: 10%;">
                    <col style="width: 30%;">
                    <col style="width: 12%;">
                    <col style="width: 8%;">
                    <col style="width: 16%;">
                    <col style="width: 16%;">
                    <col style="width: 4%;">
                </colgroup>
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-2.5 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">NO</th>
                        <th class="px-2.5 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">ITEM ID</th>
                        <th class="px-2.5 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">ITEM</th>
                        <th class="px-2.5 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">SATUAN</th>
                        <th class="px-2.5 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">QTY</th>
                        <th class="px-2.5 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">HARGA SATUAN</th>
                        <th class="px-3 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL HARGA</th>
                        <th class="px-2 py-2.5 text-center"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(cost, index) in project.indirect_costs" :key="index">
                        <tr class="hover:bg-slate-50">
                            <td class="px-2.5 py-2.5 align-top text-center text-xs text-slate-500 pt-3" x-text="index + 1"></td>
                            <td class="px-2.5 py-2.5 align-top text-left text-xs font-mono text-slate-500 pt-3" x-text="'IC-' + String(cost.id || index + 1).padStart(3, '0')"></td>
                            <td class="px-2.5 py-2.5 align-top text-left">
                                <select x-model.number="cost.master_indirect_cost_id" :disabled="project.is_indirect_cost_locked" class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs outline-none focus:border-primary bg-white disabled:bg-slate-50 disabled:text-slate-500">
                                    <option value="">-- Select Item --</option>
                                    <template x-for="type in masterIndirectCosts" :key="type.id">
                                        <option :value="type.id" :selected="cost.master_indirect_cost_id == type.id" x-text="type.name"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="px-2.5 py-2.5 align-top text-center">
                                <input type="text" readonly :value="getIndirectCostSatuan(cost.master_indirect_cost_id)" class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded px-2 py-1.5 text-xs outline-none text-center">
                            </td>
                            <td class="px-2.5 py-2.5 align-top text-center">
                                <input type="number" step="1" min="1" x-model.number="cost.qty" :disabled="project.is_indirect_cost_locked" @input="cost.qty = Math.max(1, parseInt(cost.qty) || 1); cost.harga_total = cost.qty * cost.harga_satuan" class="w-full border border-slate-300 rounded px-2 py-1.5 text-xs outline-none focus:border-primary text-center disabled:bg-slate-50 disabled:text-slate-500">
                            </td>
                            <td class="px-2.5 py-2.5 align-top text-right">
                                <input type="number" min="0" x-model.number="cost.harga_satuan" :disabled="project.is_indirect_cost_locked" @input="cost.harga_total = cost.qty * cost.harga_satuan" class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs outline-none focus:border-primary text-right disabled:bg-slate-50 disabled:text-slate-500">
                            </td>
                            <td class="px-3 py-2.5 align-top text-right text-xs font-semibold text-slate-800 pt-3" x-text="(cost.qty * cost.harga_satuan).toLocaleString('id-ID')"></td>
                            <td class="px-2 py-2.5 align-top text-center pt-2.5">
                                <button @click="removeIndirectCostRow(index)" :disabled="project.is_indirect_cost_locked" class="text-red-400 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!project.indirect_costs || project.indirect_costs.length === 0">
                        <tr><td colspan="8" class="px-6 py-8 text-center text-xs text-slate-500">No indirect costs recorded yet.</td></tr>
                    </template>
                    
                    <!-- Totals -->
                    <tr class="bg-slate-50 border-t-2 border-slate-200 font-semibold">
                        <td colspan="6" class="px-3 py-3 text-right text-xs text-slate-600 uppercase tracking-wider">TOTAL:</td>
                        <td class="px-3 py-3 text-right text-xs font-bold text-slate-900" 
                            x-text="calculateIndirectCostPlanTotal().toLocaleString('id-ID')">
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <button @click="addIndirectCostRow()" :disabled="project.is_indirect_cost_locked" class="flex items-center px-4 py-2 border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-xs bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Row
            </button>
            <button @click="syncIndirectCosts()" :disabled="project.is_indirect_cost_locked" class="flex items-center px-4 py-2 bg-primary text-white rounded text-xs font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save Indirect Costs
            </button>
        </div>
    </div>

    <!-- DIVIDER -->
    <div class="border-t border-slate-100 my-6"></div>

    <!-- SECTION 2: ACTUAL / REALISASI INDIRECT COST -->
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Realisasi Indirect Cost</h3>
        </div>

        <div class="overflow-x-auto border border-slate-100 rounded bg-white shadow-xs">
            <table class="w-full divide-y divide-slate-100 table-fixed">
                <colgroup>
                    <col style="width: 4%;">
                    <col style="width: 12%;">
                    <col style="width: 22%;">
                    <col style="width: 22%;">
                    <col style="width: 8%;">
                    <col style="width: 14%;">
                    <col style="width: 14%;">
                    <col style="width: 4%;">
                </colgroup>
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-2.5 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">NO</th>
                        <th class="px-2.5 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TANGGAL</th>
                        <th class="px-2.5 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">ITEM PLAN</th>
                        <th class="px-2.5 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">PENJELASAN ITEM</th>
                        <th class="px-2.5 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">QTY</th>
                        <th class="px-2.5 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">HARGA SATUAN</th>
                        <th class="px-3 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL HARGA</th>
                        <th class="px-2 py-2.5 text-center"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(actual, index) in project.actual_indirect_costs" :key="index">
                        <tr class="hover:bg-slate-50">
                            <td class="px-2.5 py-2.5 align-top text-center text-xs text-slate-500 pt-3" x-text="index + 1"></td>
                            
                            <!-- Tanggal Transaction Field -->
                            <td class="px-2.5 py-2.5 align-top text-left">
                                <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="actual.tanggal" placeholder="YYYY-MM-DD" class="w-full border border-slate-300 rounded px-2 py-1.5 text-xs outline-none focus:border-primary bg-white text-center">
                            </td>
                            
                            <!-- Select Item Plan -->
                            <td class="px-2.5 py-2.5 align-top text-left">
                                <select x-model.number="actual.indirect_cost_id" class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs outline-none focus:border-primary bg-white">
                                    <option value="">-- Select Item Plan --</option>
                                    <template x-for="(plan, pIdx) in project.indirect_costs" :key="plan.id || pIdx">
                                        <option :value="plan.id" :selected="actual.indirect_cost_id == plan.id" x-text="'IC-' + String(plan.id || pIdx + 1).padStart(3, '0') + ' - ' + getIndirectCostItemLabel(plan.id)"></option>
                                    </template>
                                </select>
                                <template x-if="actual.indirect_cost_id">
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium flex items-center gap-1">
                                        <span>Sisa budget:</span>
                                        <span class="font-semibold" :class="getItemSisaDana({id: actual.indirect_cost_id, qty: (project.indirect_costs.find(c => c.id == actual.indirect_cost_id) || {}).qty, harga_satuan: (project.indirect_costs.find(c => c.id == actual.indirect_cost_id) || {}).harga_satuan}) >= 0 ? 'text-emerald-600' : 'text-red-600'" x-text="'Rp ' + getItemSisaDana({id: actual.indirect_cost_id, qty: (project.indirect_costs.find(c => c.id == actual.indirect_cost_id) || {}).qty, harga_satuan: (project.indirect_costs.find(c => c.id == actual.indirect_cost_id) || {}).harga_satuan}).toLocaleString('id-ID')"></span>
                                    </p>
                                </template>
                            </td>

                            <!-- Penjelasan Item -->
                            <td class="px-2.5 py-2.5 align-top text-left">
                                <input type="text" x-model="actual.sub_item" placeholder="Keterangan rincian..." class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs outline-none focus:border-primary">
                            </td>

                            <!-- Qty -->
                            <td class="px-2.5 py-2.5 align-top text-center">
                                <input type="number" step="1" min="1" x-model.number="actual.qty" @input="actual.qty = Math.max(1, parseInt(actual.qty) || 1); actual.harga_total = actual.qty * actual.harga_satuan" class="w-full border border-slate-300 rounded px-2 py-1.5 text-xs outline-none focus:border-primary text-center">
                            </td>

                            <!-- Harga Satuan -->
                            <td class="px-2.5 py-2.5 align-top text-right">
                                <input type="number" min="0" x-model.number="actual.harga_satuan" @input="actual.harga_total = actual.qty * actual.harga_satuan" class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs outline-none focus:border-primary text-right">
                            </td>

                            <!-- Total Harga -->
                            <td class="px-3 py-2.5 align-top text-right text-xs font-semibold text-slate-800 pt-3" x-text="((actual.qty || 0) * (actual.harga_satuan || 0)).toLocaleString('id-ID')"></td>

                            <!-- Action -->
                            <td class="px-2 py-2.5 align-top text-center pt-2.5">
                                <button @click="removeActualIndirectCostRow(index)" class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!project.actual_indirect_costs || project.actual_indirect_costs.length === 0">
                        <tr><td colspan="8" class="px-6 py-8 text-center text-xs text-slate-500">No actual indirect costs recorded yet.</td></tr>
                    </template>
                    
                    <!-- Totals -->
                    <tr class="bg-slate-50 border-t-2 border-slate-200 font-semibold">
                        <td colspan="6" class="px-3 py-3 text-right text-xs text-slate-600 uppercase tracking-wider">TOTAL REALISASI:</td>
                        <td class="px-3 py-3 text-right text-xs font-bold text-slate-900" 
                            x-text="calculateIndirectCostActualTotal().toLocaleString('id-ID')">
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <button @click="addActualIndirectCostRow()" class="flex items-center px-4 py-2 border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition-colors shadow-xs bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Row
            </button>
            <button @click="syncActualIndirectCosts()" class="flex items-center px-4 py-2 bg-primary text-white rounded text-xs font-semibold hover:bg-blue-700 transition-colors shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save Realisasi Costs
            </button>
        </div>
    </div>

    <!-- LOCK / UNLOCK PLAN VALIDATION MODAL -->
    <div x-show="showLockPlanModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showLockPlanModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showLockPlanModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showLockPlanModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full" :class="project.is_indirect_cost_locked ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600'">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800" x-text="project.is_indirect_cost_locked ? 'Buka Kunci Plan Indirect Cost?' : 'Kunci Plan Indirect Cost?'"></h3>
                        <p class="text-xs text-slate-500 mt-2" x-show="!project.is_indirect_cost_locked">
                            Mengunci Plan akan menonaktifkan pengeditan alokasi anggaran (Section 1), sehingga alokasi dana tidak sengaja terubah saat pencatatan pengeluaran riil.
                        </p>
                        <p class="text-xs text-slate-500 mt-2" x-show="project.is_indirect_cost_locked">
                            Membuka kunci Plan akan mengizinkan Anda untuk mengubah kembali item alokasi anggaran pada Section 1.
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showLockPlanModal = false" class="px-4 py-2 border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="toggleLockPlan()" class="px-4 py-2 rounded text-xs font-semibold text-white transition-colors" :class="project.is_indirect_cost_locked ? 'bg-primary hover:bg-blue-700' : 'bg-amber-600 hover:bg-amber-700'">
                        <span x-text="project.is_indirect_cost_locked ? 'Ya, Buka Kunci' : 'Ya, Kunci Plan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
