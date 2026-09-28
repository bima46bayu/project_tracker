<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="clean-card p-5 text-center">
        <h3 class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Total RAB (Plan)</h3>
        <p class="text-2xl font-bold text-slate-800">Rp <span x-text="sCurveData.total_rab ? sCurveData.total_rab.toLocaleString('id-ID') : 0"></span></p>
    </div>
    <div class="clean-card p-5 text-center">
        <h3 class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Planned Progress</h3>
        <p class="text-2xl font-bold text-primary">100%</p>
    </div>
    <div class="clean-card p-5 text-center">
        <h3 class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Actual Progress</h3>
        <p class="text-2xl font-bold text-emerald-600"><span x-text="sCurveData.current_actual_progress || 0"></span>%</p>
    </div>
</div>

<div class="clean-card p-4 sm:p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-1">S-Curve Plan vs Actual</h3>
            <p class="text-xs text-slate-500">Grafik otomatis dihasilkan berdasarkan bobot RAB dan durasi.</p>
        </div>
        <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-lg">
            <button @click="sCurveViewMode = 'daily'; renderChart()" 
                :class="sCurveViewMode === 'daily' ? 'bg-white shadow-sm text-slate-800 font-medium' : 'text-slate-500 hover:text-slate-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-all">
                Harian
            </button>
            <button @click="sCurveViewMode = 'weekly'; renderChart()" 
                :class="sCurveViewMode === 'weekly' ? 'bg-white shadow-sm text-slate-800 font-medium' : 'text-slate-500 hover:text-slate-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-all">
                Mingguan
            </button>
        </div>
    </div>
    <div class="w-full overflow-x-auto relative">
        <div x-show="!sCurveData.planned_curve || sCurveData.planned_curve.length === 0" x-cloak>
            <div class="h-[350px] w-full flex flex-col items-center justify-center bg-slate-50 border border-dashed border-slate-300 rounded-lg">
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                <p class="text-sm font-medium text-slate-500">Belum ada data S-Curve</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm text-center">Silakan tentukan plan timeline (Rencana) untuk setiap task pada Task Board agar grafik S-Curve dapat terbentuk otomatis.</p>
            </div>
        </div>
        <div x-show="sCurveData.planned_curve && sCurveData.planned_curve.length > 0" x-cloak>
            <div class="h-[350px]" :style="{ width: sCurveViewMode === 'daily' && sCurveData.planned_curve ? (sCurveData.planned_curve.length * 60) + 'px' : '100%' }">
                <canvas id="scurveChart"></canvas>
            </div>
        </div>
    </div>
</div>


