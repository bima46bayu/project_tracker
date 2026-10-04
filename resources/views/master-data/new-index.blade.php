@extends('layouts.app')
@section('title', 'Master Data')

@section('content')
<meta name="turbo-cache-control" content="no-cache">
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('masterDataApp', () => ({
            activeTab: '{{ $type ?? 'managers' }}',
            isModalOpen: false,
            modalType: '',
            formData: {},
            errorMsg: '',
            searchQuery: '',
            isLoading: false,
            
            managers: @json(isset($type) && $type === 'managers' ? $initialData : null),
            customers: @json(isset($type) && $type === 'customers' ? $initialData : null),
            subkons: @json(isset($type) && $type === 'subkons' ? $initialData : null),
            bowheers: @json(isset($type) && $type === 'bowheers' ? $initialData : null),
            miscs: @json(isset($type) && $type === 'misc' ? $initialData : null),
            
            initMiscTypeSelect(el) {
                if (el.tomselect) el.tomselect.destroy();
                let ts = new TomSelect(el, {
                    valueField: 'type', labelField: 'type', searchField: 'type', create: true,
                    onChange: (value) => { this.formData.type = value; }
                });
                fetch('/api/miscs').then(res => res.json()).then(data => {
                    const uniqueTypes = new Set(data.map(item => item.type));
                    uniqueTypes.add('jenis_project');
                    ts.addOptions(Array.from(uniqueTypes).map(t => ({type: t})));
                    if (this.formData.type) ts.setValue(this.formData.type);
                });
            },

            init() {
                this.loadTabData(this.activeTab);
                this.$watch('activeTab', (value) => {
                    this.searchQuery = '';
                    this.loadTabData(value);
                });
            },

            loadTabData(tab) {
                let endpoint = tab === 'managers' ? 'users' : tab;
                let prop = tab === 'misc' ? 'miscs' : tab;
                if (tab === 'misc') endpoint = 'miscs';
                if (this[prop] === null) this.fetchData(endpoint, prop);
            },

            fetchData(endpoint, property = null) {
                let prop = property || endpoint;
                this.isLoading = true;
                fetch(`/api/${endpoint}`)
                    .then(res => res.json())
                    .then(data => { this[prop] = data; this.isLoading = false; })
                    .catch(() => { this.isLoading = false; });
            },

            get filteredData() {
                let prop = this.activeTab === 'misc' ? 'miscs' : this.activeTab;
                let data = this[prop] || [];
                if (!this.searchQuery) return data;
                let q = this.searchQuery.toLowerCase();
                return data.filter(item => Object.values(item).some(val => val && String(val).toLowerCase().includes(q)));
            },

            openModal(type) { this.modalType = type; this.formData = {}; this.errorMsg = ''; this.isModalOpen = true; },
            editItem(type, item) { this.modalType = type; this.formData = { ...item }; this.errorMsg = ''; this.isModalOpen = true; },

            submitForm() {
                let endpoint = this.modalType;
                let prop = this.modalType;
                if (this.modalType === 'managers') { endpoint = 'users'; prop = 'managers'; }
                else if (this.modalType === 'misc') { endpoint = 'miscs'; prop = 'miscs'; }
                
                let method = this.formData.id ? 'PUT' : 'POST';
                let url = this.formData.id ? `/api/${endpoint}/${this.formData.id}` : `/api/${endpoint}`;
                
                fetch(url, {
                    method, headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(this.formData)
                }).then(res => res.json().then(data => ({ status: res.status, ok: res.ok, body: data })))
                .then(result => {
                    if (result.ok) {
                        this.isModalOpen = false;
                        this[prop] = null;
                        this.fetchData(endpoint, prop);
                    } else {
                        this.errorMsg = result.status === 422 && result.body.errors
                            ? Object.values(result.body.errors).flat().join(', ')
                            : (result.body.message || 'Error saving data');
                    }
                }).catch(() => { this.errorMsg = 'Network error occurred.'; });
            },

            deleteItem(endpoint, id) {
                if (!confirm('Are you sure?')) return;
                fetch(`/api/${endpoint}/${id}`, { method: 'DELETE' }).then(() => {
                    let prop = endpoint === 'users' ? 'managers' : endpoint;
                    this[prop] = null;
                    this.fetchData(endpoint, prop);
                });
            },

            resetPassword(id) {
                let pw = prompt("Enter new password (min 6 chars):");
                if (pw && pw.length >= 6) {
                    fetch(`/api/users/${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ password: pw })
                    }).then(res => {
                        let msg = res.ok ? 'Password reset!' : 'Failed to reset.';
                        let type = res.ok ? 'success' : 'error';
                        window.dispatchEvent(new CustomEvent('notify', { detail: { msg, type } }));
                    });
                } else if (pw) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Password too short!', type: 'error' } }));
                }
            }
        }));
    });
</script>
<div x-data="masterDataApp()" class="px-4 py-4 md:px-8 md:py-6 min-h-screen bg-white">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 capitalize">Master {{ $type ?? 'Managers' }}</h2>
        <p class="text-sm text-slate-500">Manage {{ $type ?? 'Managers' }} data</p>
    </div>

    <!-- Tab Contents -->
    <div class="clean-card">
        
        <!-- Managers Tab -->
        <div x-show="activeTab === 'managers'" x-cloak class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Search managers..." class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button @click="openModal('managers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors whitespace-nowrap">
                    + Add Manager
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-if="isLoading">
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">Loading data...</td></tr>
                        </template>
                        <template x-if="!isLoading && filteredData.length === 0">
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">No data found.</td></tr>
                        </template>
                        <template x-for="item in filteredData" :key="item.id">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-800 font-medium" x-text="item.name"></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium border" 
                                          :class="item.type === 'PM' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'" 
                                          x-text="item.type"></span>
                                </td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.email"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.phone || '-'"></td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <button @click="resetPassword(item.id)" class="text-[11px] text-amber-500 font-medium hover:underline">Reset Password</button>
                                    <button @click="editItem('managers', item)" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteItem('users', item.id)" class="text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customers Tab -->
        <div x-show="activeTab === 'customers'" x-cloak class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Search customers..." class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button @click="openModal('customers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors whitespace-nowrap">
                    + Add Customer
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Customer ID</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">PIC</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Address</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-if="isLoading">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">Loading data...</td></tr>
                        </template>
                        <template x-if="!isLoading && filteredData.length === 0">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">No data found.</td></tr>
                        </template>
                        <template x-for="item in filteredData" :key="item.id">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 text-xs font-medium" x-text="item.customer_code"></td>
                                <td class="px-4 py-3 text-slate-800 font-medium" x-text="item.name"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.pic || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.email || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.phone || '-'"></td>
                                <td class="px-4 py-3 text-slate-500 text-xs" x-text="item.address || '-'"></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="editItem('customers', item)" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteItem('customers', item.id)" class="text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subkons Tab -->
        <div x-show="activeTab === 'subkons'" x-cloak class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Search subkons..." class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button @click="openModal('subkons')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors whitespace-nowrap">
                    + Add Subkon
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Subkon ID</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">PIC</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Address</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-if="isLoading">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">Loading data...</td></tr>
                        </template>
                        <template x-if="!isLoading && filteredData.length === 0">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">No data found.</td></tr>
                        </template>
                        <template x-for="item in filteredData" :key="item.id">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 text-xs font-medium" x-text="item.subkon_code"></td>
                                <td class="px-4 py-3 text-slate-800 font-medium" x-text="item.name"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.pic || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.email || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.phone || '-'"></td>
                                <td class="px-4 py-3 text-slate-500 text-xs" x-text="item.address || '-'"></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="editItem('subkons', item)" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteItem('subkons', item.id)" class="text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bowheers Tab -->
        <div x-show="activeTab === 'bowheers'" x-cloak class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Search bowheers..." class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button @click="openModal('bowheers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors whitespace-nowrap">
                    + Add Bowheer
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Bowheer ID</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">PIC (Person in Charge)</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">No HP</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Address</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-if="isLoading">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">Loading data...</td></tr>
                        </template>
                        <template x-if="!isLoading && filteredData.length === 0">
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs">No data found.</td></tr>
                        </template>
                        <template x-for="item in filteredData" :key="item.id">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 text-xs font-medium" x-text="item.bowheer_code"></td>
                                <td class="px-4 py-3 text-slate-800 font-medium" x-text="item.name"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.pic || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.email || '-'"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.phone || '-'"></td>
                                <td class="px-4 py-3 text-slate-500 text-xs" x-text="item.address || '-'"></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="editItem('bowheers', item)" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteItem('bowheers', item.id)" class="text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Misc Tab (e.g., Jenis Project) -->
        <div x-show="activeTab === 'misc'" x-cloak class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Search misc..." class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button @click="openModal('misc')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors whitespace-nowrap">
                    + Add Jenis Project
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Value</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Display Order</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-if="isLoading">
                            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs">Loading data...</td></tr>
                        </template>
                        <template x-if="!isLoading && filteredData.length === 0">
                            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs">No data found.</td></tr>
                        </template>
                        <template x-for="item in filteredData" :key="item.id">
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-800 font-medium" x-text="item.value"></td>
                                <td class="px-4 py-3 text-slate-500 text-xs uppercase" x-text="item.type"></td>
                                <td class="px-4 py-3 text-slate-500" x-text="item.display_order"></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="editItem('misc', item)" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteItem('miscs', item.id)" class="text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reusable Modal Form -->
    <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="isModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4" x-text="(formData.id ? 'Edit ' : 'Add New ') + modalType"></h3>
                    
                    <div x-show="errorMsg" class="mb-4 bg-red-50 text-red-600 p-3 rounded text-xs" x-text="errorMsg"></div>

                    <form @submit.prevent="submitForm">
                        
                        <!-- Common Name Field -->
                        <template x-if="modalType !== 'misc'">
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-slate-700 mb-1">Name *</label>
                                <input type="text" x-model="formData.name" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none">
                            </div>
                        </template>


                        <!-- Managers Only -->
                        <template x-if="modalType === 'managers'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Type *</label>
                                    <select x-model="formData.type" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none">
                                        <option value="AM">Account Manager (AM)</option>
                                        <option value="PM">Project Manager (PM)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Email * (for login)</label>
                                    <input type="email" x-model="formData.email" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1" x-text="formData.id ? 'Password (leave blank to keep current)' : 'Password *'"></label>
                                    <input type="password" x-model="formData.password" :required="!formData.id" class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none" placeholder="Min 6 chars">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Phone</label>
                                    <input type="text" x-model="formData.phone" class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none">
                                </div>
                            </div>
                        </template>

                        <!-- Customers/Subkons/Bowheers Only -->
                        <template x-if="['customers', 'subkons', 'bowheers'].includes(modalType)">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">PIC (Person In Charge)</label>
                                    <input type="text" x-model="formData.pic" class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
                                    <input type="email" x-model="formData.email" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">No HP</label>
                                    <input type="text" x-model="formData.phone" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Address</label>
                                    <textarea x-model="formData.address" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none"></textarea>
                                </div>
                            </div>
                        </template>

                        <!-- Misc Only -->
                        <template x-if="modalType === 'misc'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Value * (e.g. Last Mile)</label>
                                    <input type="text" x-model="formData.value" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Type *</label>
                                    <div wire:ignore>
                                        <select x-init="initMiscTypeSelect($el)" x-model="formData.type" required class="w-full" placeholder="Select or type a new one..."></select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Display Order</label>
                                    <input type="number" x-model="formData.display_order" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none" value="0">
                                </div>
                            </div>
                        </template>

                        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
