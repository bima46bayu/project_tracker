<div class="clean-card p-4 sm:p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-sm font-bold text-slate-800">Indirect Cost</h2>
            <p class="text-xs text-slate-500 mt-1">Project ID: <span x-text="project.project_id || '{{ $project->project_id ?? '' }}'"></span></p>
        </div>
    </div>
    
    <div class="overflow-x-auto border border-slate-100 rounded">
        <table class="min-w-[900px] w-full divide-y divide-slate-100 table-fixed">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-10">NO</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-20">ITEM ID</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-60">ITEM</th>
                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-24">SATUAN</th>
                    <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-20">QTY</th>
                    <th class="px-4 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">HARGA SATUAN</th>
                    <th class="px-4 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">TOTAL HARGA</th>
                    <th class="px-3 py-2.5 text-center w-10"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                <template x-for="(cost, index) in project.indirect_costs" :key="index">
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-500 text-center" x-text="index + 1"></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500" x-text="'IC-' + String(index + 1).padStart(3, '0')"></td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <select x-model.number="cost.master_indirect_cost_id" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm outline-none focus:border-primary bg-white">
                                <option value="">-- Select Item --</option>
                                <template x-for="type in masterIndirectCosts" :key="type.id">
                                    <option :value="type.id" :selected="cost.master_indirect_cost_id == type.id" x-text="type.name"></option>
                                </template>
                            </select>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <input type="text" readonly :value="getIndirectCostSatuan(cost.master_indirect_cost_id)" class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded px-2 py-1.5 text-sm outline-none text-center">
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <input type="number" min="1" x-model.number="cost.qty" @input="cost.harga_total = cost.qty * cost.harga_satuan" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm outline-none focus:border-primary text-center">
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <input type="number" min="0" x-model.number="cost.harga_satuan" @input="cost.harga_total = cost.qty * cost.harga_satuan" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm outline-none focus:border-primary text-right">
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium text-slate-800" x-text="(cost.qty * cost.harga_satuan).toLocaleString('id-ID')"></td>
                        <td class="px-3 py-3 whitespace-nowrap text-center">
                            <button @click="removeIndirectCostRow(index)" class="text-red-500 hover:text-red-700">
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
                <tr class="bg-slate-50">
                    <td colspan="6" class="px-4 py-3 whitespace-nowrap text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Total:</td>
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-bold text-slate-900" 
                        x-text="(project.indirect_costs || []).reduce((sum, cost) => sum + ((cost.qty || 0) * (cost.harga_satuan || 0)), 0).toLocaleString('id-ID')">
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <button @click="addIndirectCostRow()" class="flex items-center px-4 py-2 border border-slate-300 text-slate-700 rounded text-sm font-medium hover:bg-slate-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Row
        </button>
        <button @click="syncIndirectCosts()" class="flex items-center px-4 py-2 bg-primary text-white rounded text-sm font-medium hover:bg-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save Indirect Costs
        </button>
    </div>
</div>
