@extends('layouts.app')
@section('title', 'Master Data')

@section('content')
<meta name="turbo-cache-control" content="no-cache">
<script>
    const initMasterDataApp = () => {
        Alpine.data('masterDataApp', () => ({
            activeTab: 'managers',
            isModalOpen: false,
            modalType: '',
            formData: {},
            errorMsg: '',
            
            managers: [],
            customers: [],
            subkons: [],
            bowheers: [],
            miscs: [],
            
            initMiscTypeSelect(el) {
                  // Ensure previous instance is destroyed if re-mounted
                  if (el.tomselect) el.tomselect.destroy();
                  
                  let ts = new TomSelect(el, {
                      valueField: 'type',
                      labelField: 'type',
                      searchField: 'type',
                      create: true,
                      onChange: (value) => {
                          this.formData.type = value;
                      }
                  });
                  
                  fetch('/api/miscs')
                      .then(res => res.json())
                      .then(data => {
                          // Extract unique types and ensure jenis_project is always available
                        const uniqueTypes = new Set(data.map(item => item.type));
                        uniqueTypes.add('jenis_project');
                        
                        ts.addOptions(Array.from(uniqueTypes).map(t => ({type: t})));
                        
                        // Set value if editing
                        if (this.formData.type) {
                              ts.setValue(this.formData.type);
                          }
                      });
              },

            init() {
                this.fetchData('users', 'managers');
                this.fetchData('customers');
                this.fetchData('subkons');
                this.fetchData('bowheers');
                this.fetchData('miscs');
            },

            fetchData(endpoint, property = null) {
                let prop = property || endpoint;
                fetch(`/api/${endpoint}`)
                    .then(res => res.json())
                    .then(data => {
                        this[prop] = data;
                    });
            },

            openModal(type) {
                this.modalType = type;
                this.formData = {};
                this.isModalOpen = true;
            },

            editItem(type, item) {
                this.modalType = type;
                this.formData = { ...item };
                this.isModalOpen = true;
            },

            submitForm() {
                let endpoint = this.modalType;
                let prop = this.modalType;
                
                if (this.modalType === 'managers') {
                    endpoint = 'users';
                    prop = 'managers';
                } else if (this.modalType === 'misc') {
                    endpoint = 'miscs';
                    prop = 'miscs';
                }
                
                let method = this.formData.id ? 'PUT' : 'POST';
                let url = this.formData.id ? `/api/${endpoint}/${this.formData.id}` : `/api/${endpoint}`;
                
                fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(this.formData)
                }).then(res => {
                    return res.json().then(data => ({ status: res.status, ok: res.ok, body: data }));
                }).then(result => {
                    if (result.ok) {
                        this.isModalOpen = false;
                        this.fetchData(endpoint, prop);
                    } else {
                        if (result.status === 422 && result.body.errors) {
                            let msgs = Object.values(result.body.errors).flat();
                            this.errorMsg = msgs.join(', ');
                        } else {
                            this.errorMsg = result.body.message || 'Error saving data';
                        }
                    }
                }).catch(e => {
                    this.errorMsg = 'Network error occurred.';
                });
            },


            deleteItem(endpoint, id) {
                if(!confirm('Are you sure?')) return;
                fetch(`/api/${endpoint}/${id}`, { method: 'DELETE' })
                    .then(() => {
                        let prop = endpoint === 'users' ? 'managers' : endpoint;
                        this.fetchData(endpoint, prop);
                    });
            },

            resetPassword(id) {
                let newPassword = prompt("Enter new password for this user (minimum 6 characters):");
                if (newPassword && newPassword.length >= 6) {
                    fetch(`/api/users/${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ password: newPassword })
                    }).then(res => {
                        if (res.ok) window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Password reset successfully!', type: 'success' } }));
                        else window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Failed to reset password.', type: 'error' } }));
                    });
                } else if (newPassword) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { msg: 'Password too short!', type: 'error' } }));
                }
            }
        }));
    };
    
    if (typeof Alpine !== 'undefined') {
        initMasterDataApp();
    } else {
        document.addEventListener('alpine:init', initMasterDataApp);
    }
</script>
<div x-data="masterDataApp()" class="px-4 py-4 md:px-8 md:py-6 min-h-screen bg-white">
    <div class="mb-2">
        <h2 class="text-xl font-bold text-slate-900">Master Data</h2>
        <p class="text-sm text-slate-500">Manage Account Managers, Project Managers, Customers, and Subkons</p>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-slate-200 mt-6 overflow-x-auto scrollbar-none">
        <nav class="-mb-px flex space-x-8 text-sm min-w-max">
            <button @click="activeTab = 'managers'" :class="activeTab === 'managers' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Account & Project Managers
            </button>
            <button @click="activeTab = 'customers'" :class="activeTab === 'customers' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Customers
            </button>
            <button @click="activeTab = 'subkons'" :class="activeTab === 'subkons' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Subkons
            </button>
            <button @click="activeTab = 'bowheers'" :class="activeTab === 'bowheers' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Bowheers
            </button>
            <button @click="activeTab = 'misc'" :class="activeTab === 'misc' ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Misc
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="clean-card">
        
        <!-- Managers Tab -->
        <div x-show="activeTab === 'managers'" x-cloak class="p-6">
            <div class="flex justify-end mb-4">
                <button @click="openModal('managers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
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
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="item in managers" :key="item.id">
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
            <div class="flex justify-end mb-4">
                <button @click="openModal('customers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
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
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="item in customers" :key="item.id">
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
            <div class="flex justify-end mb-4">
                <button @click="openModal('subkons')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
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
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="item in subkons" :key="item.id">
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
            <div class="flex justify-end mb-4">
                <button @click="openModal('bowheers')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
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
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="item in bowheers" :key="item.id">
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
            <div class="flex justify-end mb-4">
                <button @click="openModal('misc')" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
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
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="item in miscs" :key="item.id">
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
