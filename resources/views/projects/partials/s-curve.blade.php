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
    <div class="w-full overflow-x-auto">
        <div class="h-[350px]" :style="{ width: sCurveViewMode === 'daily' ? (sCurveData.planned_curve ? (sCurveData.planned_curve.length * 60) + 'px' : '1000px') : '100%' }">
            <canvas id="scurveChart"></canvas>
        </div>
    </div>
</div>


