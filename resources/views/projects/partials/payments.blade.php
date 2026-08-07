<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Payment</h2>
        <p class="text-sm text-slate-500">Project ID: {{ $project->id }}</p>
    </div>

    <!-- MAIN PROJECT PAYMENT (IN) -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Payment</h3>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
            <label class="text-sm font-medium text-slate-700 sm:w-32 shrink-0">Payment Term:</label>
            <input type="text" x-model="project.payment_term" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary" placeholder="e.g. 30% DP, 40% Progress, 30% Final">
        </div>

        <div class="overflow-x-auto border border-slate-100 rounded-lg bg-white shadow-sm mb-4">
            <table class="min-w-[1000px] w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">INVOICE</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">TANGGAL</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">KETERANGAN</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">NILAI</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">TANGGAL PAYMENT</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">NILAI PAYMENT</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-32">SELISIH</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-24">STATUS</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-12"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(pay, index) in (project.payments || [])" :key="index">
                        <template x-if="pay.type === 'IN'">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 text-center text-xs text-slate-500" x-text="project.payments.filter((p, i) => p.type === 'IN' && i <= index).length"></td>
                                <td class="px-4 py-2">
                                    <input type="text" x-model="pay.invoice" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1" placeholder="Invoice No.">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="pay.tanggal" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" x-model="pay.keterangan" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1" placeholder="Description">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" x-model.number="pay.nilai" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1 text-right">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="pay.tanggal_payment" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" x-model.number="pay.nilai_payment" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1 text-right">
                                </td>
                                <td class="px-4 py-2 text-right text-xs font-medium" :class="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) > 0 ? 'text-rose-600' : 'text-emerald-600'" x-text="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)).toLocaleString('id-ID')"></td>
                                <td class="px-4 py-2 text-center text-xs font-bold">
                                    <span x-show="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) <= 0 && parseFloat(pay.nilai || 0) > 0" class="text-emerald-600">Lunas</span>
                                    <span x-show="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) > 0 || parseFloat(pay.nilai || 0) === 0" class="text-amber-500" x-text="parseFloat(pay.nilai || 0) > 0 ? ((parseFloat(pay.nilai_payment || 0) / parseFloat(pay.nilai || 1)) * 100).toFixed(1) + '%' : '-'"></span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button @click="removePaymentRow(index)" class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <!-- Totals Row -->
                    <tr class="bg-slate-50 font-semibold border-t-2 border-slate-200">
                        <td colspan="4" class="px-4 py-3 text-right text-xs text-slate-700">Total:</td>
                        <td class="px-4 py-3 text-right text-xs text-slate-800" x-text="(project.payments || []).filter(p => p.type === 'IN').reduce((sum, p) => sum + parseFloat(p.nilai || 0), 0).toLocaleString('id-ID')"></td>
                        <td></td>
                        <td class="px-4 py-3 text-right text-xs text-slate-800" x-text="(project.payments || []).filter(p => p.type === 'IN').reduce((sum, p) => sum + parseFloat(p.nilai_payment || 0), 0).toLocaleString('id-ID')"></td>
                        <td class="px-4 py-3 text-right text-xs text-rose-600" x-text="(project.payments || []).filter(p => p.type === 'IN').reduce((sum, p) => sum + (parseFloat(p.nilai || 0) - parseFloat(p.nilai_payment || 0)), 0).toLocaleString('id-ID')"></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button @click="addPaymentRow('IN')" class="text-xs font-semibold px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors flex items-center shadow-sm bg-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add Row
        </button>
    </div>

    <template x-if="!project.subkons || project.subkons.length === 0">
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start text-sm text-amber-700 mb-8">
            Assign subkons in <strong>Project details</strong> (edit project -> Subkon) to see separate sections: Payment Subcon A, Payment Subcon B, etc.
        </div>
    </template>

    <!-- SUBCON PAYMENTS (OUT) -->
    <template x-for="subkon in (project.subkons || [])" :key="subkon.id">
        <div class="mb-8 pt-4 border-t border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4" x-text="'Payment ' + subkon.name"></h3>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
                <label class="text-sm font-medium text-slate-700 sm:w-44 shrink-0">Payment Term Subcon:</label>
                <input type="text" x-model="subkon.pivot.payment_term" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary" placeholder="e.g. 50% DP, 50% Final">
            </div>

            <div class="overflow-x-auto border border-slate-100 rounded-lg bg-white shadow-sm mb-4">
                <table class="min-w-[1000px] w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                            <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">INVOICE</th>
                            <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">TANGGAL</th>
                            <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">KETERANGAN</th>
                            <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">NILAI</th>
                            <th class="px-4 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">TANGGAL PAYMENT</th>
                            <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-40">NILAI PAYMENT</th>
                            <th class="px-4 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-32">SELISIH</th>
                            <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-24">STATUS</th>
                            <th class="px-4 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(pay, index) in (project.payments || [])" :key="index">
                            <template x-if="pay.type === 'OUT' && pay.subkon_id === subkon.id">
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-2 text-center text-xs text-slate-500" x-text="project.payments.filter((p, i) => p.type === 'OUT' && p.subkon_id === subkon.id && i <= index).length"></td>
                                    <td class="px-4 py-2">
                                        <input type="text" x-model="pay.invoice" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1" placeholder="Invoice">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="pay.tanggal" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" x-model="pay.keterangan" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1" placeholder="Description">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" x-model.number="pay.nilai" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1 text-right">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="pay.tanggal_payment" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" x-model.number="pay.nilai_payment" class="w-full text-xs bg-transparent outline-none border border-transparent focus:border-slate-300 rounded px-2 py-1 text-right">
                                    </td>
                                    <td class="px-4 py-2 text-right text-xs font-medium" :class="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) > 0 ? 'text-rose-600' : 'text-emerald-600'" x-text="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)).toLocaleString('id-ID')"></td>
                                    <td class="px-4 py-2 text-center text-xs font-bold">
                                        <span x-show="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) <= 0 && parseFloat(pay.nilai || 0) > 0" class="text-emerald-600">Lunas</span>
                                        <span x-show="(parseFloat(pay.nilai || 0) - parseFloat(pay.nilai_payment || 0)) > 0 || parseFloat(pay.nilai || 0) === 0" class="text-amber-500" x-text="parseFloat(pay.nilai || 0) > 0 ? ((parseFloat(pay.nilai_payment || 0) / parseFloat(pay.nilai || 1)) * 100).toFixed(1) + '%' : '-'"></span>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button @click="removePaymentRow(index)" class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </template>
                        <!-- Totals Row -->
                        <tr class="bg-slate-50 font-semibold border-t-2 border-slate-200">
                            <td colspan="4" class="px-4 py-3 text-right text-xs text-slate-700">Total:</td>
                            <td class="px-4 py-3 text-right text-xs text-slate-800" x-text="(project.payments || []).filter(p => p.type === 'OUT' && p.subkon_id === subkon.id).reduce((sum, p) => sum + parseFloat(p.nilai || 0), 0).toLocaleString('id-ID')"></td>
                            <td></td>
                            <td class="px-4 py-3 text-right text-xs text-slate-800" x-text="(project.payments || []).filter(p => p.type === 'OUT' && p.subkon_id === subkon.id).reduce((sum, p) => sum + parseFloat(p.nilai_payment || 0), 0).toLocaleString('id-ID')"></td>
                            <td class="px-4 py-3 text-right text-xs text-rose-600" x-text="(project.payments || []).filter(p => p.type === 'OUT' && p.subkon_id === subkon.id).reduce((sum, p) => sum + (parseFloat(p.nilai || 0) - parseFloat(p.nilai_payment || 0)), 0).toLocaleString('id-ID')"></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button @click="addPaymentRow('OUT', subkon.id)" class="text-xs font-semibold px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors flex items-center shadow-sm bg-white">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add Row
            </button>
        </div>
    </template>

    <!-- FLOATING SAVE BUTTON -->
    <div class="fixed bottom-6 right-6 z-40">
        <button @click="savePayments()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-bold shadow-lg hover:shadow-xl transition-all flex items-center space-x-2 focus:outline-none focus:ring-4 focus:ring-blue-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            <span>Save Payments</span>
        </button>
    </div>
</div>
