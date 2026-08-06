<div class="clean-card p-4 sm:p-6">
    <h3 class="text-sm font-bold text-slate-800 mb-1">Bobot Project</h3>
    <p class="text-xs text-slate-500 mb-4">Ringkasan bobot (weight) untuk setiap tugas berdasarkan RAB.</p>
    <div class="overflow-x-auto border border-slate-100 rounded">
        <table class="min-w-full text-xs text-left">
            <thead class="text-[10px] font-semibold text-slate-500 bg-slate-50 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5">Task Name</th>
                    <th class="px-4 py-2.5 text-center">Total QTY RAB</th>
                    <th class="px-4 py-2.5">Total Harga</th>
                    <th class="px-4 py-2.5 text-right">Bobot (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="ts in sCurveData.tasks_summary" :key="ts.id">
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2 font-medium text-slate-700" x-text="ts.name"></td>
                        <td class="px-4 py-2 text-center text-slate-600 font-medium" x-text="parseFloat(ts.total_qty || 0).toLocaleString('id-ID')"></td>
                        <td class="px-4 py-2 text-slate-600" x-text="'Rp ' + parseFloat(ts.total_harga).toLocaleString('id-ID')"></td>
                        <td class="px-4 py-2 font-bold text-primary text-right" x-text="ts.bobot + '%'"></td>
                    </tr>
                </template>
                <template x-if="!sCurveData.tasks_summary || sCurveData.tasks_summary.length === 0">
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada data bobot. Pastikan RAB telah diisi.</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
