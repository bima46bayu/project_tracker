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
                    <div @click="openTaskDetail(task)" class="grid grid-cols-1 md:grid-cols-12 gap-4 px-3 py-2.5 bg-white border border-slate-100 hover:border-slate-300 rounded shadow-sm hover:shadow cursor-pointer transition-all items-center group/row">
                        
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
    </template>

    <!-- Slide-over Modal for Task Details -->
    <div x-show="showTaskSlideover" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Background overlay -->
            <div x-show="showTaskSlideover" @click="closeSlideover()" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900 bg-opacity-30 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <div class="fixed inset-y-0 right-0 w-full md:w-1/2 flex">
                <!-- Slide-over panel -->
                <div x-show="showTaskSlideover" 
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="w-full">
                     
                    <div class="h-full flex flex-col bg-white shadow-2xl overflow-y-scroll">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center sticky top-0 bg-white/90 backdrop-blur z-10">
                            <div class="flex items-center space-x-3">
                                <span class="bg-primary-light text-primary px-2 py-1 rounded text-xs font-bold uppercase tracking-wider" x-text="isEditingTask ? 'Edit Task' : 'New Task'"></span>
                                <span class="text-sm text-slate-500" x-text="project.project_code"></span>
                            </div>
                            <button @click="closeSlideover()" type="button" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-full hover:bg-slate-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 px-6 py-6 space-y-8">
                            
                            <!-- Basic Info -->
                            <div>
                                <input type="text" x-model="activeTask.name" class="w-full text-xl font-bold text-slate-800 border border-slate-300 rounded-lg px-4 py-2 mb-6 hover:border-slate-400 focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all bg-white shadow-sm" placeholder="Task Name...">
                                
                                <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium text-slate-500 w-20">Start Date</span>
                                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="activeTask.start_date" class="flex-1 text-sm border border-slate-300 hover:border-slate-400 focus:border-primary rounded-md py-1.5 px-3 outline-none transition-colors cursor-pointer shadow-sm bg-white" placeholder="Select date...">
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-medium text-slate-500 w-20">End Date</span>
                                        <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="activeTask.end_date" class="flex-1 text-sm border border-slate-300 hover:border-slate-400 focus:border-primary rounded-md py-1.5 px-3 outline-none transition-colors cursor-pointer shadow-sm bg-white" placeholder="Select date...">
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-xs font-medium text-slate-500 w-20">Status</span>
                                        <select x-model="activeTask.status" disabled class="flex-1 text-sm border border-slate-300 hover:border-slate-400 rounded-md py-1.5 px-3 shadow-sm outline-none font-medium bg-slate-50 opacity-70 cursor-not-allowed"
                                            :class="{
                                                'text-slate-600': activeTask.status === 'TODO',
                                                'text-blue-600': activeTask.status === 'IN_PROGRESS',
                                                'text-emerald-600': activeTask.status === 'DONE'
                                            }">
                                            <option value="TODO">To Do (0%)</option>
                                            <option value="IN_PROGRESS">In Progress</option>
                                            <option value="DONE">Done (100%)</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-xs font-medium text-slate-500 w-20">Priority</span>
                                        <select x-model="activeTask.priority" class="flex-1 text-sm border border-slate-300 hover:border-slate-400 focus:border-primary shadow-sm rounded-md py-1.5 px-3 cursor-pointer outline-none transition-colors font-medium bg-white"
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
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Progress Tracking -->
                            <div>
                                <label class="flex justify-between items-center text-xs font-semibold text-slate-800 uppercase tracking-wider mb-4">
                                    <span>Progress Tracking</span>
                                    <span class="text-primary font-bold text-lg bg-blue-50 px-2 py-0.5 rounded" x-text="activeTask.progress_percentage + '%'"></span>
                                </label>
                                <input type="range" min="0" max="100" step="1" x-model="activeTask.progress_percentage" 
                                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary mb-2 transition-all">
                                <p class="text-xs text-slate-500 mt-2">Slide to update task completion percentage. This will automatically update the Task Status.</p>
                            </div>

                            <hr class="border-slate-100">

                            <!-- RAB / Task Items -->
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <label class="text-xs font-semibold text-slate-800 uppercase tracking-wider">RAB Items (Budget)</label>
                                    <button @click="addActiveRabRow()" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md font-medium transition-colors flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Add Item
                                    </button>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[150px]">Item</th>
                                                <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[80px]">Qty</th>
                                                <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[120px]">Harga Jual</th>
                                                <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Total Jual</th>
                                                <th class="px-3 py-2 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[120px]">Harga Modal</th>
                                                <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Total Modal</th>
                                                <th class="px-3 py-2 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[100px]">Profit</th>
                                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[60px]">%</th>
                                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider min-w-[40px]"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100">
                                            <template x-for="(rab, index) in activeTask.task_items" :key="index">
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="px-3 py-2">
                                                        <select x-model="rab.master_item_id" @change="updateActiveRabPrice(index)" class="w-full text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none bg-white min-w-[150px]">
                                                            <option value="">-- Select Item --</option>
                                                            <template x-for="item in masterItems" :key="item.id">
                                                                <option :value="item.id" x-text="item.name + ' (' + item.satuan + ')'"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="number" x-model="rab.qty" min="1" class="w-full min-w-[60px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-center">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="number" x-model="rab.harga_satuan" min="0" class="w-full min-w-[90px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-right">
                                                    </td>
                                                    <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap" x-text="'Rp ' + (rab.qty * rab.harga_satuan).toLocaleString('id-ID')"></td>
                                                    <td class="px-3 py-2">
                                                        <input type="number" x-model="rab.modal_satuan" min="0" class="w-full min-w-[90px] text-xs border border-slate-300 rounded px-2 py-1.5 focus:border-primary outline-none text-right">
                                                    </td>
                                                    <td class="px-3 py-2 text-right text-xs font-medium text-slate-700 whitespace-nowrap" x-text="'Rp ' + (rab.qty * (rab.modal_satuan || 0)).toLocaleString('id-ID')"></td>
                                                    <td class="px-3 py-2 text-right text-xs font-bold text-emerald-600 whitespace-nowrap" x-text="'Rp ' + ((rab.qty * rab.harga_satuan) - (rab.qty * (rab.modal_satuan || 0))).toLocaleString('id-ID')"></td>
                                                    <td class="px-3 py-2 text-center text-[10px] font-bold text-blue-600" x-text="rab.harga_satuan > 0 ? (((rab.harga_satuan - (rab.modal_satuan || 0)) / rab.harga_satuan) * 100).toFixed(1) + '%' : '0%'"></td>
                                                    <td class="px-3 py-2 text-center">
                                                        <button type="button" @click="activeTask.task_items.splice(index, 1)" class="text-slate-300 hover:text-red-500 p-1 rounded hover:bg-red-50 transition-colors">
                                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            
                                            <template x-if="!activeTask.task_items || activeTask.task_items.length === 0">
                                                <tr>
                                                    <td colspan="9" class="px-4 py-8 text-center">
                                                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                        <p class="text-sm font-medium text-slate-500">No RAB items</p>
                                                        <p class="text-xs text-slate-400 mt-1">Add items to define the budget for this task.</p>
                                                    </td>
                                                </tr>
                                            </template>

                                            <!-- Totals Footer -->
                                            <tr class="bg-slate-50">
                                                <td colspan="3" class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Total Proyeksi</td>
                                                <td class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap" x-text="'Rp ' + calculateActiveTaskTotal().toLocaleString('id-ID')"></td>
                                                <td class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Total Modal</td>
                                                <td class="px-3 py-3 text-right text-xs font-bold text-slate-800 whitespace-nowrap" x-text="'Rp ' + calculateActiveTaskModal().toLocaleString('id-ID')"></td>
                                                <td class="px-3 py-3 text-right text-xs font-bold text-emerald-600 whitespace-nowrap" x-text="'Rp ' + calculateActiveTaskProfit().toLocaleString('id-ID')"></td>
                                                <td class="px-3 py-3 text-center text-[10px] font-bold text-blue-600 bg-blue-50" x-text="calculateActiveTaskProfitPercentage() + '%'"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-white border-t border-slate-200 flex justify-between space-x-3 sticky bottom-0 z-10">
                            <div>
                                <template x-if="isEditingTask">
                                    <button @click="deleteTask(activeTask.id)" class="px-5 py-2 border border-red-200 text-red-600 bg-red-50 rounded-lg text-sm font-semibold hover:bg-red-100 transition-colors focus:ring-2 focus:ring-red-200 outline-none flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete Task
                                    </button>
                                </template>
                            </div>
                            <div class="flex space-x-3">
                                <button @click="closeSlideover()" class="px-5 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors focus:ring-2 focus:ring-slate-200 outline-none">
                                    Cancel
                                </button>
                                <button @click="saveActiveTask()" class="px-5 py-2 bg-primary rounded-lg text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm focus:ring-2 focus:ring-blue-500 outline-none flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
