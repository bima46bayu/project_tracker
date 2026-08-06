<div class="clean-card p-4 sm:p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Issue Tracking</h2>
            <p class="text-sm text-slate-500 mt-1">Log and monitor project constraints or delays.</p>
        </div>
        <button @click="openNewIssue()" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded font-medium text-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Report Issue
        </button>
    </div>

    <div class="space-y-4">
        <template x-if="!project.issues || project.issues.length === 0">
            <div class="bg-slate-50 border border-slate-100 rounded-lg p-8 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="text-slate-600 font-medium mb-1">No Issues Reported</h3>
                <p class="text-slate-400 text-sm">Everything is running smoothly! Click 'Report Issue' if a constraint occurs.</p>
            </div>
        </template>

        <template x-for="issue in project.issues" :key="issue.id">
            <div class="border rounded-lg p-5 bg-white shadow-sm hover:shadow-md transition-shadow relative" :class="{
                'border-rose-200': issue.impact === 'HIGH' && issue.status !== 'RESOLVED',
                'border-amber-200': issue.impact === 'MEDIUM' && issue.status !== 'RESOLVED',
                'border-slate-200': issue.impact === 'LOW' && issue.status !== 'RESOLVED',
                'border-emerald-200 opacity-75': issue.status === 'RESOLVED'
            }">
                <div class="absolute top-4 right-4 flex space-x-2">
                    <button @click="editIssue(issue)" class="text-slate-400 hover:text-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <button @click="deleteIssue(issue.id)" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded uppercase" :class="{
                        'bg-slate-100 text-slate-600': issue.status === 'OPEN',
                        'bg-blue-100 text-blue-700': issue.status === 'IN_PROGRESS',
                        'bg-emerald-100 text-emerald-700': issue.status === 'RESOLVED'
                    }" x-text="issue.status.replace('_', ' ')"></span>
                    
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded uppercase border" :class="{
                        'border-rose-200 text-rose-600 bg-rose-50': issue.impact === 'HIGH',
                        'border-amber-200 text-amber-600 bg-amber-50': issue.impact === 'MEDIUM',
                        'border-slate-200 text-slate-600 bg-slate-50': issue.impact === 'LOW'
                    }" x-text="'IMPACT: ' + issue.impact"></span>

                    <span class="text-xs text-slate-500 font-medium" x-text="issue.category"></span>
                </div>

                <h3 class="text-lg font-bold text-slate-800 mb-1" x-text="issue.title"></h3>
                <div class="text-sm text-slate-600 mb-4 flex items-center space-x-4">
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <span x-text="formatDateShort(issue.reported_date)"></span></span>
                    
                    <template x-if="issue.project_task_id">
                        <span class="flex items-center text-primary bg-blue-50 px-2 py-0.5 rounded">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg> 
                            <span class="text-xs font-semibold" x-text="(project.tasks.find(t => t.id == issue.project_task_id) || {}).name || 'Unknown Task'"></span>
                        </span>
                    </template>
                </div>

                <template x-if="issue.resolution">
                    <div class="mt-4 p-3 bg-slate-50 rounded border border-slate-100">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-1">Resolution / Action Plan:</p>
                        <p class="text-sm text-slate-700" x-text="issue.resolution"></p>
                    </div>
                </template>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <template x-if="issue.status === 'OPEN'">
                        <button @click="updateIssueStatus(issue, 'IN_PROGRESS')" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition-colors">
                            Mark In Progress
                        </button>
                    </template>
                    <template x-if="issue.status !== 'RESOLVED'">
                        <button @click="updateIssueStatus(issue, 'RESOLVED')" class="px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded hover:bg-emerald-100 transition-colors">
                            Mark Resolved
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Issue Modal -->
    <div x-show="showIssueModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showIssueModal = false">
                <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-6 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg leading-6 font-bold text-slate-800" x-text="isEditingIssue ? 'Edit Issue' : 'Report New Issue'"></h3>
                        <button @click="showIssueModal = false" class="text-slate-400 hover:text-slate-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="saveIssue()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Issue Title *</label>
                            <input type="text" x-model="activeIssue.title" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                                <select x-model="activeIssue.category" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                                    <option value="">-- Select --</option>
                                    <option value="Cuaca">Cuaca</option>
                                    <option value="Material">Material</option>
                                    <option value="Tenaga Kerja">Tenaga Kerja</option>
                                    <option value="Perizinan">Perizinan</option>
                                    <option value="Desain">Desain</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Impact Level *</label>
                                <select x-model="activeIssue.impact" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                                    <option value="LOW">Low</option>
                                    <option value="MEDIUM">Medium</option>
                                    <option value="HIGH">High</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Reported Date *</label>
                                <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="activeIssue.reported_date" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
                                <select x-model="activeIssue.status" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                                    <option value="OPEN">Open</option>
                                    <option value="IN_PROGRESS">In Progress</option>
                                    <option value="RESOLVED">Resolved</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Related Task (Optional)</label>
                            <select x-model.number="activeIssue.project_task_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                                <option value="">-- Select Task --</option>
                                <template x-for="t in project.tasks" :key="t.id">
                                    <option :value="t.id" x-text="t.name"></option>
                                </template>
                            </select>
                            <p class="text-[10px] text-slate-500 mt-1">If this issue is delaying a specific task, please select it.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Resolution / Action Plan</label>
                            <textarea x-model="activeIssue.resolution" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary placeholder-slate-400" placeholder="What is the plan to fix this?"></textarea>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end space-x-3">
                            <button type="button" @click="showIssueModal = false" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium shadow-sm transition-colors text-sm">Save Issue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
