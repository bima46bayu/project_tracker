<div class="space-y-4 pb-20">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Task Board</h2>
            <p class="text-sm text-slate-500">Manage tasks and Rencana Anggaran Biaya (RAB)</p>
        </div>
        <button @click="openNewTask()" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            New Task
        </button>
    </div>
    
    <!-- Table Header (Notion Style) -->
    <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 border-b border-slate-200 text-xs font-semibold text-slate-400 uppercase tracking-wider">
        <div class="col-span-4">Task Name</div>
        <div class="col-span-2">Total RAB</div>
        <div class="col-span-2">Status</div>
        <div class="col-span-2">Timeline</div>
        <div class="col-span-2">Priority</div>
    </div>

    <template x-if="!project.tasks || project.tasks.length === 0">
        <div class="text-center py-16 border border-slate-200 border-dashed rounded-lg bg-slate-50 mt-4">
            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-sm text-slate-600 font-medium">No tasks yet</p>
            <p class="text-xs text-slate-500 mt-1 mb-4">Create your first task to start planning.</p>
            <button @click="openNewTask()" class="text-primary text-sm font-medium hover:underline">Create Task</button>
        </div>
    </template>

    <!-- Grouping by Status -->
    <template x-for="group in ['TODO', 'IN_PROGRESS', 'DONE']" :key="group">
        <div class="mt-4" x-data="{ expanded: true }" x-show="project.tasks && project.tasks.filter(t => t.status === group).length > 0">
            <!-- Accordion Header -->
            <button @click="expanded = !expanded" class="flex items-center w-full text-left py-2 mb-1 group">
                <svg :class="{'rotate-90': expanded}" class="w-4 h-4 text-slate-400 transition-transform mr-1 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <div class="flex items-center">
                    <span class="text-sm font-semibold mr-2" 
                          :class="{
                              'text-slate-700': group === 'TODO',
                              'text-blue-700': group === 'IN_PROGRESS',
                              'text-emerald-700': group === 'DONE'
                          }" 
                          x-text="group === 'TODO' ? 'To Do' : (group === 'IN_PROGRESS' ? 'In Progress' : 'Done')"></span>
                    <span class="text-xs font-medium bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full" x-text="project.tasks.filter(t => t.status === group).length"></span>
                </div>
            </button>

            <!-- Task Rows -->
            <div x-show="expanded" class="space-y-[1px] ml-1 border-l border-slate-200 pl-3">
                <template x-for="task in project.tasks.filter(t => t.status === group)" :key="task.id">
                    <!-- Row -->
                    <div @click="window.location.href = `/projects/${projectId}/tasks/${task.id}`" class="grid grid-cols-1 md:grid-cols-12 gap-4 px-3 py-2.5 bg-white border border-slate-100 hover:border-slate-300 rounded shadow-sm hover:shadow cursor-pointer transition-all items-center group/row">
                        
                        <!-- Task Name & Progress -->
                        <div class="col-span-4 flex flex-col justify-center">
                            <span class="text-sm font-medium text-slate-800 group-hover/row:text-primary transition-colors" x-text="task.name"></span>
                            <div class="flex items-center mt-1 w-full max-w-[8rem] sm:max-w-[12rem]">
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex-1 mr-2">
                                    <div class="h-full rounded-full" :class="task.progress_percentage == 100 ? 'bg-emerald-500' : 'bg-primary'" :style="`width: ${task.progress_percentage}%`"></div>
                                </div>
                                <span class="text-[10px] font-medium text-slate-500" x-text="task.progress_percentage + '%'"></span>
                            </div>
                        </div>

                        <!-- Total RAB -->
                        <div class="col-span-2 flex items-center">
                            <span class="text-xs font-semibold text-slate-700" x-text="'Rp ' + calculateTaskTotal(task).toLocaleString('id-ID')"></span>
                        </div>

                        <!-- Status Badge -->
                        <div class="col-span-2 flex items-center">
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium border" 
                                  :class="{
                                      'bg-slate-50 text-slate-600 border-slate-200': task.status === 'TODO',
                                      'bg-blue-50 text-blue-700 border-blue-200': task.status === 'IN_PROGRESS',
                                      'bg-emerald-50 text-emerald-700 border-emerald-200': task.status === 'DONE'
                                  }" 
                                  x-text="task.status === 'TODO' ? 'To Do' : (task.status === 'IN_PROGRESS' ? 'In Progress' : 'Done')">
                            </span>
                        </div>

                        <!-- Timeline -->
                        <div class="col-span-2 text-xs text-slate-500 flex items-center font-medium">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span x-text="formatDateShort(task.start_date) + ' → ' + formatDateShort(task.end_date)"></span>
                        </div>

                        <!-- Priority Badge -->
                        <div class="col-span-2 flex items-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider" 
                                  :class="{
                                      'bg-slate-100 text-slate-600': !task.priority || task.priority === 'LOW',
                                      'bg-amber-100 text-amber-700': task.priority === 'MEDIUM',
                                      'bg-red-100 text-red-700': task.priority === 'HIGH'
                                  }" 
                                  x-text="task.priority || 'LOW'">
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>    <!-- Slide-over Modal for New Task Creation -->
    <div x-show="showTaskSlideover" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Background overlay -->
            <div x-show="showTaskSlideover" @click="closeSlideover()" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900 bg-opacity-30 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex">
                <!-- Slide-over panel -->
                <div x-show="showTaskSlideover" 
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="w-screen max-w-full sm:max-w-md md:max-w-lg">
                     
                    <div class="h-full flex flex-col bg-white shadow-2xl overflow-y-scroll">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center sticky top-0 bg-white/90 backdrop-blur z-10">
                            <div class="flex items-center space-x-3">
                                <span class="bg-primary-light text-primary px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">New Task</span>
                                <span class="text-sm text-slate-500" x-text="project.project_code"></span>
                            </div>
                            <button @click="closeSlideover()" type="button" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-full hover:bg-slate-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 px-6 py-6 space-y-6">
                            <!-- Basic Info -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Task</label>
                                <input type="text" x-model="activeTask.name" class="w-full text-base font-medium text-slate-800 border border-slate-300 rounded-lg px-3.5 py-2 hover:border-slate-400 focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all bg-white shadow-sm" placeholder="Contoh: Pekerjaan Pondasi & Struktur">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Start Date</label>
                                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="activeTask.start_date" class="w-full text-sm border border-slate-300 hover:border-slate-400 focus:border-primary rounded-md py-1.5 px-3 outline-none transition-colors cursor-pointer shadow-sm bg-white" placeholder="Pilih tanggal...">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1.5">End Date</label>
                                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="activeTask.end_date" class="w-full text-sm border border-slate-300 hover:border-slate-400 focus:border-primary rounded-md py-1.5 px-3 outline-none transition-colors cursor-pointer shadow-sm bg-white" placeholder="Pilih tanggal...">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Priority</label>
                                <select x-model="activeTask.priority" class="w-full text-sm border border-slate-300 hover:border-slate-400 focus:border-primary shadow-sm rounded-md py-1.5 px-3 cursor-pointer outline-none transition-colors font-medium bg-white"
                                    :class="{
                                        'text-slate-600': activeTask.priority === 'LOW',
                                        'text-amber-600': activeTask.priority === 'MEDIUM',
                                        'text-red-600': activeTask.priority === 'HIGH'
                                    }">
                                    <option value="LOW">Low</option>
                                    <option value="MEDIUM">Medium</option>
                                    <option value="HIGH">High</option>
                                </select>
                            </div>

                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-700">
                                <p class="font-medium">Catatan:</p>
                                <p class="mt-1 text-blue-600">Rencana Anggaran Biaya (RAB) dan Progress Timeline dapat diatur langsung di halaman detail task setelah task dibuat.</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-white border-t border-slate-200 flex justify-end space-x-3 sticky bottom-0 z-10">
                            <button @click="closeSlideover()" class="px-5 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors focus:ring-2 focus:ring-slate-200 outline-none">
                                Cancel
                            </button>
                            <button @click="saveActiveTask()" class="px-5 py-2 bg-primary rounded-lg text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm focus:ring-2 focus:ring-blue-500 outline-none flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Buat Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
