<div class="w-full">
    <div class="clean-card p-8">
        <h2 class="text-lg font-bold text-slate-800 mb-6">Project Settings</h2>
        
        <form @submit.prevent="updateProjectSettings" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Project ID</label>
                    <input type="text" disabled x-model="project.project_code" class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded px-4 py-2.5 text-sm outline-none">
                </div>

                <!-- RIGHT COLUMN -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="project.start_date" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary cursor-pointer bg-white" placeholder="Select date...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="project.end_date" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary cursor-pointer bg-white" placeholder="Select date...">
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Nama Project <span class="text-red-500">*</span></label>
                    <input type="text" x-model="project.name" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">SPK/PO No <span class="text-red-500">*</span></label>
                    <input type="text" x-model="project.spk_no" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Lokasi Project <span class="text-red-500">*</span></label>
                    <input type="text" x-model="project.lokasi" required class="w-full border border-slate-300 rounded px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Jenis Project <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select id="edit_jenis_project_id" x-model="project.jenis_project_id" required placeholder="-- Select Jenis Project --"></select>
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Bowheer <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select id="edit_bowheer_id" x-model="project.bowheer_id" required placeholder="-- Select Bowheer --"></select>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Customer <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select id="edit_customer_id" x-model="project.customer_id" required placeholder="-- Select Customer --"></select>
                    </div>
                </div>
                
                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Subkon <span class="text-slate-400 font-normal">(optional)</span></label>
                    <div wire:ignore>
                        <select id="edit_subkon_ids" multiple placeholder="Select one or more subcons..."></select>
                    </div>
                </div>
                
                <!-- RIGHT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Account Manager (A/M) <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select id="edit_account_manager_id" x-model="project.account_manager_id" required placeholder="-- Select Account Manager --"></select>
                    </div>
                </div>

                <!-- LEFT COLUMN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Project Manager(s) (P/M) <span class="text-red-500">*</span></label>
                    <div wire:ignore>
                        <select id="edit_pm_ids" multiple required placeholder="Select project managers..."></select>
                    </div>
                </div>
            </div>

            <!-- Warning Alert -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm text-amber-700">Changing the start or end dates may affect the S-Curve calculations and Task timeline mappings. Proceed with caution.</p>
            </div>

            <div class="border-t border-slate-200 pt-6 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
    
    <!-- Danger Zone -->
    <div class="mt-8 border border-red-200 bg-white rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-red-100 bg-red-50">
            <h3 class="text-red-800 font-bold">Danger Zone</h3>
        </div>
        <div class="p-6 flex justify-between items-center">
            <div>
                <h4 class="text-sm font-bold text-slate-800">Delete Project</h4>
                <p class="text-xs text-slate-500 mt-1">Once you delete a project, there is no going back. Please be certain.</p>
            </div>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you strictly sure you want to delete this project? All associated tasks, overheads, and payments will be permanently removed.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="border border-red-500 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-lg text-sm transition-colors focus:outline-none">
                    Delete Project
                </button>
            </form>
        </div>
    </div>
</div>
