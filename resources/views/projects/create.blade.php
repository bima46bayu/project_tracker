@extends('layouts.app')
@section('title', 'Register New Project')

@section('content')



<div class="px-8 py-6 bg-slate-50 min-h-screen">
    <div class="mb-6 flex items-center">
        <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-slate-600 mr-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Register New Project</h2>
            <p class="text-sm text-slate-500">Fill in the main project details to register</p>
        </div>
    </div>

    <div class="clean-card p-8 shadow-sm">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Project ID <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(Auto-generated)</span></label>
                    <input type="text" disabled value="TPN-XXXX" class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded px-4 py-2.5 text-sm outline-none">
                </div>

                <!-- RIGHT COLUMN -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="text" x-data x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" name="start_date" required class="w-full border border-slate-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-white cursor-pointer" placeholder="Pilih tanggal...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="text" x-data x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" name="end_date" required class="w-full border border-slate-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-white cursor-pointer" placeholder="Pilih tanggal...">
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Nama Project <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full border border-slate-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" placeholder="Project Name">
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">SPK/PO No <span class="text-red-500">*</span></label>
                    <input type="text" name="spk_no" required class="w-full border border-slate-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" placeholder="SPK/PO Number">
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Lokasi Project <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" required class="w-full border border-slate-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" placeholder="Project Location">
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Jenis Project <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select name="jenis_project_id" id="jenis_project_id" required placeholder="-- Select Jenis Project --"></select>
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Bowheer <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select name="bowheer_id" id="bowheer_id" required placeholder="-- Select Bowheer --"></select>
                    </div>
                    <p class="text-[10px] text-amber-600 mt-1 hidden" id="bowheer_warning">No bowheers in master data. <a href="{{ route('master-data.index') }}" class="underline">Add in Master Data</a></p>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Customer <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select name="customer_id" id="customer_id" required placeholder="-- Select Customer --"></select>
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Nilai Project</label>
                    <input type="text" disabled value="Will be calculated from BOQ Total Harga" class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded px-4 py-2.5 text-sm outline-none">
                    <p class="text-[10px] text-slate-500 mt-1">Nilai Project is automatically calculated from the sum of Total Harga in BOQ</p>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Subkon <span class="text-slate-400 font-normal">(optional)</span></label>
                    <div wire:ignore>
                        <select name="subkon_ids[]" id="subkon_ids" multiple placeholder="Select one or more subcons..."></select>
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Account Manager (A/M) <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select name="account_manager_id" id="account_manager_id" required placeholder="-- Select Account Manager --"></select>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Project Manager(s) (P/M) <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select name="pm_ids[]" id="pm_ids" multiple required placeholder="Select project managers..."></select>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end border-t border-slate-100 mt-8">
                <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-6 py-2.5 rounded text-sm font-medium transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Register Project
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('turbo:load', function() {
        // Only run on the create project page
        if (!document.getElementById('jenis_project_id')) return;
        
        // Cleanup existing instances if Turbo cached them
        ['#jenis_project_id', '#customer_id', '#bowheer_id', '#subkon_ids', '#pm_ids', '#account_manager_id'].forEach(id => {
            let el = document.querySelector(id);
            if (el && el.tomselect) el.tomselect.destroy();
        });

        // 1. Jenis Project
        let jenisTs = new TomSelect("#jenis_project_id", {
            valueField: 'id',
            labelField: 'value',
            searchField: 'value',
            create: function(input, callback) {
                fetch('/api/miscs', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({ type: 'jenis_project', value: input, display_order: 0 })
                })
                .then(res => res.json())
                .then(data => callback({id: data.id, value: data.value}))
                .catch(() => callback(false));
            }
        });
        fetch('/api/miscs?type=jenis_project').then(res => res.json()).then(data => jenisTs.addOptions(data));

        // 2. Customer
        let customerTs = new TomSelect("#customer_id", { valueField: 'id', labelField: 'name', searchField: 'name', create: false });
        fetch('/api/customers').then(res => res.json()).then(data => customerTs.addOptions(data));

        // 3. Bowheer
        let bowheerTs = new TomSelect("#bowheer_id", { valueField: 'id', labelField: 'name', searchField: 'name', create: false });
        fetch('/api/bowheers').then(res => res.json()).then(data => {
            if(data.length === 0) {
                let warn = document.getElementById('bowheer_warning');
                if (warn) warn.classList.remove('hidden');
            }
            bowheerTs.addOptions(data);
        });

        // 4. Subkon
        let subkonTs = new TomSelect("#subkon_ids", { valueField: 'id', labelField: 'name', searchField: 'name', plugins: ['remove_button'], create: false });
        fetch('/api/subkons').then(res => res.json()).then(data => subkonTs.addOptions(data));

        // 5. Managers
        let amTs = new TomSelect("#account_manager_id", { valueField: 'id', labelField: 'name', searchField: 'name', create: false });
        let pmTs = new TomSelect("#pm_ids", { valueField: 'id', labelField: 'name', searchField: 'name', plugins: ['remove_button'], create: false });
        
        fetch('/api/users').then(res => res.json()).then(users => {
            amTs.addOptions(users.filter(u => u.type === 'AM'));
            pmTs.addOptions(users.filter(u => u.type === 'PM'));
        });
    });
</script>
@endsection
