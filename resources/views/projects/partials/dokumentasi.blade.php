<div class="clean-card p-4 sm:p-6" x-data="projectDocumentation">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-800">Dokumentasi Project</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola berkas progress lapangan, invoice & kwitansi, serta kontrak legalitas.</p>
        </div>
        <button @click="openDocModal(activeSubTab === 'ALL' ? 'PROGRESS' : activeSubTab)" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-xs transition-colors flex items-center shadow-xs self-start sm:self-auto flex-shrink-0">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Dokumen
        </button>
    </div>

    <!-- Category Sub-Tabs Navigation (Responsive horizontal scroll with no wrapping text) -->
    <div class="border-b border-slate-200 mb-6 overflow-x-auto hide-scrollbar">
        <nav class="-mb-px flex space-x-4 sm:space-x-6 text-xs font-semibold whitespace-nowrap">
            <!-- All Docs -->
            <button @click="activeSubTab = 'ALL'" :class="activeSubTab === 'ALL' ? 'border-primary text-primary border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="pb-3 transition-colors flex items-center flex-shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 01-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Semua Dokumen</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600" x-text="(project && project.documentations ? project.documentations.length : 0)"></span>
            </button>
            <!-- Progress Lapangan -->
            <button @click="activeSubTab = 'PROGRESS'" :class="activeSubTab === 'PROGRESS' ? 'border-primary text-primary border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="pb-3 transition-colors flex items-center flex-shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4 mr-1.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Progress Lapangan</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600" x-text="(project && project.documentations ? project.documentations.filter(d => (d.category || 'PROGRESS') === 'PROGRESS').length : 0)"></span>
            </button>
            <!-- Invoice & Keuangan -->
            <button @click="activeSubTab = 'INVOICE'" :class="activeSubTab === 'INVOICE' ? 'border-primary text-primary border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="pb-3 transition-colors flex items-center flex-shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4 mr-1.5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                <span>Invoice & Keuangan</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600" x-text="(project && project.documentations ? project.documentations.filter(d => d.category === 'INVOICE').length : 0)"></span>
            </button>
            <!-- Kontrak & BAST -->
            <button @click="activeSubTab = 'CONTRACT'" :class="activeSubTab === 'CONTRACT' ? 'border-primary text-primary border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="pb-3 transition-colors flex items-center flex-shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4 mr-1.5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>Kontrak & BAST</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600" x-text="(project && project.documentations ? project.documentations.filter(d => d.category === 'CONTRACT').length : 0)"></span>
            </button>
            <!-- Lain-lain -->
            <button @click="activeSubTab = 'OTHER'" :class="activeSubTab === 'OTHER' ? 'border-primary text-primary border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="pb-3 transition-colors flex items-center flex-shrink-0 whitespace-nowrap">
                <svg class="w-4 h-4 mr-1.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                <span>Lain-lain</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600" x-text="(project && project.documentations ? project.documentations.filter(d => d.category === 'OTHER').length : 0)"></span>
            </button>
        </nav>
    </div>

    <!-- Empty State -->
    <template x-if="filteredDocs.length === 0">
        <div class="bg-slate-50 border border-slate-100 rounded-lg p-8 sm:p-10 text-center">
            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-slate-600 font-medium text-xs sm:text-sm mb-1">Belum ada dokumen di kategori ini</h3>
            <p class="text-slate-400 text-[11px] sm:text-xs">Klik tombol "Tambah Dokumen" di atas untuk melampirkan file berkas.</p>
        </div>
    </template>

    <!-- TABLE VIEW (For Invoice, Contract, or Financial Docs with horizontal scrolling) -->
    <div x-show="filteredDocs.length > 0 && (activeSubTab === 'INVOICE' || activeSubTab === 'CONTRACT' || activeSubTab === 'OTHER')" class="overflow-x-auto border border-slate-100 rounded-lg bg-white shadow-xs">
        <table class="w-full min-w-[700px] divide-y divide-slate-100 text-xs">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2.5 text-center font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                    <th class="px-3 py-2.5 text-left font-semibold text-slate-500 uppercase tracking-wider w-28">TANGGAL</th>
                    <th class="px-3 py-2.5 text-left font-semibold text-slate-500 uppercase tracking-wider w-36">NO. DOKUMEN</th>
                    <th class="px-3 py-2.5 text-left font-semibold text-slate-500 uppercase tracking-wider w-32">KATEGORI</th>
                    <th class="px-3 py-2.5 text-left font-semibold text-slate-500 uppercase tracking-wider">JUDUL & KETERANGAN</th>
                    <th class="px-3 py-2.5 text-left font-semibold text-slate-500 uppercase tracking-wider w-48">LAMPIRAN FILE</th>
                    <th class="px-3 py-2.5 text-center font-semibold text-slate-500 uppercase tracking-wider w-24">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(doc, idx) in filteredDocs" :key="doc.id">
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-3 align-top text-center text-slate-500" x-text="idx + 1"></td>
                        <td class="px-3 py-3 align-top text-slate-600 font-medium whitespace-nowrap" x-text="formatDateShort(doc.logged_date)"></td>
                        <td class="px-3 py-3 align-top text-slate-800 font-mono" x-text="doc.document_number || '-'"></td>
                        <td class="px-3 py-3 align-top">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase"
                                  :class="{
                                      'bg-amber-50 text-amber-700 border border-amber-200': doc.category === 'INVOICE',
                                      'bg-purple-50 text-purple-700 border border-purple-200': doc.category === 'CONTRACT',
                                      'bg-blue-50 text-blue-700 border border-blue-200': doc.category === 'PROGRESS',
                                      'bg-slate-100 text-slate-700 border border-slate-200': doc.category === 'OTHER'
                                  }">
                                <template x-if="doc.category === 'INVOICE'">
                                    <svg class="w-3 h-3 mr-1 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                                </template>
                                <template x-if="doc.category === 'CONTRACT'">
                                    <svg class="w-3 h-3 mr-1 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </template>
                                <template x-if="doc.category === 'PROGRESS'">
                                    <svg class="w-3 h-3 mr-1 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                </template>
                                <template x-if="doc.category === 'OTHER'">
                                    <svg class="w-3 h-3 mr-1 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                </template>
                                <span x-text="doc.category === 'INVOICE' ? 'Invoice/Kwitansi' : (doc.category === 'CONTRACT' ? 'Kontrak/BAST' : (doc.category === 'PROGRESS' ? 'Progress' : 'Lainnya'))"></span>
                            </span>
                        </td>
                        <td class="px-3 py-3 align-top">
                            <p class="font-bold text-slate-800" x-text="doc.title"></p>
                            <p class="text-slate-500 text-xs mt-0.5 whitespace-pre-wrap line-clamp-2" x-text="doc.description || '-'"></p>
                        </td>
                        <td class="px-3 py-3 align-top">
                            <div class="flex flex-col gap-1.5">
                                <template x-for="file in (doc.files || [])" :key="file.id">
                                    <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded px-2 py-1 hover:bg-slate-100 transition-colors">
                                        <!-- PDF Icon Badge -->
                                        <template x-if="file.file_type && file.file_type.includes('pdf')">
                                            <span class="inline-flex items-center gap-0.5 text-red-600 bg-red-50 border border-red-200 font-bold text-[9px] px-1.5 py-0.5 rounded flex-shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                PDF
                                            </span>
                                        </template>
                                        <!-- Image Icon Badge -->
                                        <template x-if="file.file_type && file.file_type.startsWith('image/')">
                                            <span class="inline-flex items-center gap-0.5 text-blue-600 bg-blue-50 border border-blue-200 font-bold text-[9px] px-1.5 py-0.5 rounded flex-shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                IMG
                                            </span>
                                        </template>
                                        <!-- Video Icon Badge -->
                                        <template x-if="file.file_type && file.file_type.startsWith('video/')">
                                            <span class="inline-flex items-center gap-0.5 text-purple-600 bg-purple-50 border border-purple-200 font-bold text-[9px] px-1.5 py-0.5 rounded flex-shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                VID
                                            </span>
                                        </template>
                                        <span class="text-[11px] text-slate-700 truncate max-w-[120px]" x-text="file.file_name"></span>
                                        <button @click="openPreview(file)" class="ml-auto text-primary hover:underline text-[10px] font-semibold flex-shrink-0">Preview</button>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-3 py-3 align-top text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="openEditDocModal(doc)" title="Edit Dokumen" class="text-slate-400 hover:text-blue-600 transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="openDeleteModal(doc)" title="Hapus Dokumen" class="text-slate-400 hover:text-red-600 transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- TIMELINE VIEW (For Progress Lapangan or All Dokumen view) -->
    <div class="relative max-w-4xl border-l-2 border-slate-200 ml-3 sm:ml-6 mt-6 space-y-6 sm:space-y-8 pb-8" x-show="filteredDocs.length > 0 && (activeSubTab === 'PROGRESS' || activeSubTab === 'ALL')">
        <template x-for="doc in filteredDocs" :key="doc.id">
            <div class="relative pl-6 sm:pl-10">
                <!-- Timeline Dot -->
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white border-2 border-primary ring-4 ring-white"></div>
                
                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800" x-text="doc.title"></h3>
                    <template x-if="doc.document_number">
                        <span class="text-[11px] font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded" x-text="'No: ' + doc.document_number"></span>
                    </template>
                    <span class="text-[11px] font-semibold text-primary bg-blue-50 px-2.5 py-0.5 rounded" x-text="formatDateShort(doc.logged_date)"></span>
                </div>
                
                <div class="bg-white border border-slate-100 rounded-xl p-3.5 sm:p-4 shadow-xs mt-2 relative group">
                    <div class="absolute top-3 right-3 flex items-center space-x-1.5 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                        <button @click="openEditDocModal(doc)" title="Edit Dokumen" class="text-slate-400 hover:text-blue-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button @click="openDeleteModal(doc)" title="Hapus Dokumen" class="text-slate-400 hover:text-red-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    
                    <p class="text-xs text-slate-600 whitespace-pre-wrap leading-relaxed mb-3 pr-12 sm:pr-0" x-text="doc.description || ''"></p>
                    
                    <!-- Attachments Gallery -->
                    <template x-if="doc.files && doc.files.length > 0">
                        <div class="flex flex-wrap gap-2.5 sm:gap-3 mt-3 pt-3 border-t border-slate-50">
                            <template x-for="file in doc.files" :key="file.id">
                                <div @click="openPreview(file)" class="cursor-pointer rounded-lg border border-slate-200 overflow-hidden hover:border-primary transition-all bg-slate-50 flex items-center justify-center group relative" style="width: 100px; height: 80px;">
                                    <template x-if="file.file_type && file.file_type.startsWith('image/')">
                                        <div class="w-full h-full bg-cover bg-center" :style="'background-image: url(/storage/' + file.file_path + ')'"></div>
                                    </template>
                                    <template x-if="file.file_type && file.file_type.startsWith('video/')">
                                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white">
                                            <svg class="w-6 h-6 text-white opacity-80 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg>
                                            <span class="text-[9px] font-semibold mt-1">Video</span>
                                        </div>
                                    </template>
                                    <template x-if="file.file_type && file.file_type.includes('pdf')">
                                        <div class="text-center p-2">
                                            <svg class="w-6 h-6 text-red-500 mx-auto mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-[9px] font-semibold text-slate-700 truncate max-w-[85px] block" x-text="file.file_name"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- MODAL FORM DOKUMENTASI (CREATE / EDIT) -->
    <div x-show="showDocModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs" @click="if(!isUploading) showDocModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative z-10 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-5 sm:px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm sm:text-base font-bold text-slate-800" x-text="isEditing ? 'Edit Dokumen Project' : 'Tambah Dokumen Project'"></h3>
                <button @click="showDocModal = false" :disabled="isUploading" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-5 sm:p-6 overflow-y-auto space-y-4 text-xs">
                <!-- Category Select -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kategori Dokumen *</label>
                    <select x-model="newDoc.category" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs outline-none focus:border-primary bg-white">
                        <option value="PROGRESS">Progress Lapangan (Foto/Video Kegiatan)</option>
                        <option value="INVOICE">Invoice & Keuangan (Faktur, Kwitansi, Struk)</option>
                        <option value="CONTRACT">Kontrak & Legalitas (SPK, BAST, Adendum)</option>
                        <option value="OTHER">Lain-lain</option>
                    </select>
                </div>

                <!-- Document Number -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">No. Dokumen / Invoice (Opsional)</label>
                    <input type="text" x-model="newDoc.document_number" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs outline-none focus:border-primary" placeholder="Misal: INV/2026/09/001 atau SPK-002">
                </div>

                <!-- Date -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Tanggal *</label>
                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="newDoc.logged_date" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs outline-none focus:border-primary">
                </div>

                <!-- Title -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Judul / Nama Dokumen *</label>
                    <input type="text" x-model="newDoc.title" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs outline-none focus:border-primary" placeholder="Misal: Invoice Pembelian Besi Beton">
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
                    <textarea x-model="newDoc.description" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs outline-none focus:border-primary" placeholder="Keterangan rincian atau catatan khusus..."></textarea>
                </div>

                <!-- Existing Attached Files (For Edit Mode) -->
                <template x-if="isEditing && existingFiles.length > 0">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">File Terlampir Saat Ini</label>
                        <div class="space-y-1.5">
                            <template x-for="file in existingFiles" :key="file.id">
                                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded px-2.5 py-1.5">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="text-[10px] font-bold uppercase text-slate-500 flex-shrink-0" x-text="file.file_type && file.file_type.includes('pdf') ? 'PDF' : (file.file_type && file.file_type.startsWith('image/') ? 'IMG' : 'FILE')"></span>
                                        <span class="text-xs text-slate-700 truncate" x-text="file.file_name"></span>
                                    </div>
                                    <button @click="deleteExistingFile(file.id)" title="Hapus Berkas Ini" class="text-red-400 hover:text-red-600 transition-colors ml-2 flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- New File Attachments Input -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1" x-text="isEditing ? 'Tambah Lampiran Baru (Opsional)' : 'Lampiran File (PDF, Foto, Video)'"></label>
                    <input type="file" id="docFiles" multiple @change="handleFileSelect" accept="image/*,video/*,application/pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[10px] text-slate-400 mt-1">Foto dikompresi otomatis. Dukungan file: JPG, PNG, PDF, & MP4 Video.</p>
                    
                    <template x-if="newDoc.files.length > 0">
                        <div class="mt-2 flex gap-2 flex-wrap">
                            <span class="text-[11px] bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-semibold" x-text="newDoc.files.length + ' file baru dipilih'"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="px-5 sm:px-6 py-3 border-t border-slate-100 bg-slate-50 rounded-b-xl flex justify-end space-x-2">
                <button @click="showDocModal = false" :disabled="isUploading" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors">Batal</button>
                <button @click="saveDocumentation()" :disabled="isUploading" class="bg-primary hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-xs font-semibold transition-colors flex items-center shadow-xs disabled:opacity-50">
                    <span x-show="isUploading" class="mr-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    <span x-text="isUploading ? (isEditing ? 'Updating Dokumen...' : 'Menyimpan & Mengunggah...') : (isEditing ? 'Perbarui Dokumen' : 'Simpan Dokumen')"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS DOKUMEN -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs" @click="if(!isDeleting) showDeleteModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm relative z-10 overflow-hidden p-5 sm:p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Dokumen Project?</h3>
            <p class="text-xs text-slate-500 mb-2">Apakah Anda yakin ingin menghapus dokumen ini?</p>
            
            <div class="bg-slate-50 border border-slate-100 rounded-lg p-2.5 mb-5 text-left">
                <p class="text-xs font-bold text-slate-800 truncate" x-text="deletingDoc ? deletingDoc.title : ''"></p>
                <p class="text-[11px] text-slate-400 mt-0.5" x-text="deletingDoc && deletingDoc.document_number ? 'No: ' + deletingDoc.document_number : ''"></p>
                <p class="text-[10px] text-rose-500 mt-1 font-semibold">⚠️ Seluruh berkas fisik terkait akan terhapus secara permanen.</p>
            </div>
            
            <div class="flex justify-center space-x-3">
                <button @click="showDeleteModal = false" :disabled="isDeleting" class="w-1/2 px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                    Batal
                </button>
                <button @click="executeDeleteDoc()" :disabled="isDeleting" class="w-1/2 px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors flex items-center justify-center shadow-xs disabled:opacity-50">
                    <span x-show="isDeleting" class="mr-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    <span x-text="isDeleting ? 'Menghapus...' : 'Hapus Dokumen'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- PREVIEW MODAL (PDF / Image / Video Lightbox) -->
    <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4">
        <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-xs" @click="showPreviewModal = false"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center px-4 sm:px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="flex items-center gap-2 truncate pr-2">
                    <span class="font-bold text-xs sm:text-sm text-slate-800 truncate" x-text="previewFile ? previewFile.file_name : ''"></span>
                    <span class="text-[11px] sm:text-xs text-slate-400 flex-shrink-0" x-text="previewFile ? formatFileSize(previewFile.file_size) : ''"></span>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <template x-if="previewFile">
                        <a :href="'/storage/' + previewFile.file_path" download class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download
                        </a>
                    </template>
                    <button @click="showPreviewModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <div class="p-3 sm:p-4 bg-slate-900 flex-1 flex items-center justify-center overflow-auto min-h-[350px] sm:min-h-[400px]">
                <template x-if="previewFile && previewFile.file_type && previewFile.file_type.startsWith('image/')">
                    <img :src="'/storage/' + previewFile.file_path" class="max-h-[75vh] max-w-full object-contain rounded">
                </template>
                <template x-if="previewFile && previewFile.file_type && previewFile.file_type.startsWith('video/')">
                    <video controls autoplay class="max-h-[75vh] max-w-full rounded">
                        <source :src="'/storage/' + previewFile.file_path" :type="previewFile.file_type">
                        Browser Anda tidak mendukung player video.
                    </video>
                </template>
                <template x-if="previewFile && previewFile.file_type && previewFile.file_type.includes('pdf')">
                    <iframe :src="'/storage/' + previewFile.file_path" class="w-full h-[75vh] rounded bg-white"></iframe>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('projectDocumentation', () => ({
            activeSubTab: 'ALL',
            showDocModal: false,
            showPreviewModal: false,
            showDeleteModal: false,
            previewFile: null,
            deletingDoc: null,
            isUploading: false,
            isDeleting: false,
            isEditing: false,
            editingDocId: null,
            existingFiles: [],
            newDoc: {
                category: 'PROGRESS',
                title: '',
                document_number: '',
                description: '',
                logged_date: new Date().toISOString().split('T')[0],
                files: []
            },
            
            openDocModal(category = 'PROGRESS') {
                this.isEditing = false;
                this.editingDocId = null;
                this.existingFiles = [];
                this.newDoc = {
                    category: category,
                    title: '',
                    document_number: '',
                    description: '',
                    logged_date: new Date().toISOString().split('T')[0],
                    files: []
                };
                this.showDocModal = true;
                
                setTimeout(() => {
                    const fileInput = document.getElementById('docFiles');
                    if (fileInput) fileInput.value = '';
                }, 100);
            },

            openEditDocModal(doc) {
                this.isEditing = true;
                this.editingDocId = doc.id;
                this.existingFiles = doc.files ? [...doc.files] : [];
                this.newDoc = {
                    category: doc.category || 'PROGRESS',
                    title: doc.title || '',
                    document_number: doc.document_number || '',
                    description: doc.description || '',
                    logged_date: doc.logged_date ? doc.logged_date.split('T')[0] : new Date().toISOString().split('T')[0],
                    files: []
                };
                this.showDocModal = true;

                setTimeout(() => {
                    const fileInput = document.getElementById('docFiles');
                    if (fileInput) fileInput.value = '';
                }, 100);
            },

            openPreview(file) {
                this.previewFile = file;
                this.showPreviewModal = true;
            },

            openDeleteModal(doc) {
                this.deletingDoc = doc;
                this.showDeleteModal = true;
            },

            formatDateShort(dateStr) {
                if (!dateStr) return '-';
                try {
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                } catch(e) {
                    return dateStr;
                }
            },
            
            async handleFileSelect(event) {
                const files = event.target.files;
                if (!files || !files.length) return;
                
                this.newDoc.files = [];
                
                for (let i = 0; i < files.length; i++) {
                    let file = files[i];
                    
                    if (file.type.startsWith('image/')) {
                        try {
                            const compressedFile = await this.compressImage(file);
                            this.newDoc.files.push(compressedFile);
                        } catch(e) {
                            this.newDoc.files.push(file);
                        }
                    } else {
                        this.newDoc.files.push(file);
                    }
                }
            },
            
            compressImage(file) {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = (event) => {
                        const img = new Image();
                        img.src = event.target.result;
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            const MAX_WIDTH = 1600;
                            const MAX_HEIGHT = 1600;
                            let width = img.width;
                            let height = img.height;
                            
                            if (width > height) {
                                if (width > MAX_WIDTH) {
                                    height *= MAX_WIDTH / width;
                                    width = MAX_WIDTH;
                                }
                            } else {
                                if (height > MAX_HEIGHT) {
                                    width *= MAX_HEIGHT / height;
                                    height = MAX_HEIGHT;
                                }
                            }
                            
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            canvas.toBlob((blob) => {
                                if (!blob) {
                                    resolve(file);
                                    return;
                                }
                                const compressedFile = new File([blob], file.name, {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                resolve(compressedFile);
                            }, 'image/jpeg', 0.75);
                        };
                        img.onerror = () => resolve(file);
                    };
                    reader.onerror = () => resolve(file);
                });
            },
            
            saveDocumentation() {
                if(!this.newDoc.title || !this.newDoc.logged_date) {
                    alert('Judul/Nama Kegiatan dan Tanggal wajib diisi!');
                    return;
                }

                const targetProjectId = this.projectId || (this.project ? this.project.id : null);
                if (!targetProjectId) {
                    alert('Project ID tidak ditemukan.');
                    return;
                }
                
                this.isUploading = true;
                
                const formData = new FormData();
                formData.append('project_id', targetProjectId);
                formData.append('category', this.newDoc.category || 'PROGRESS');
                formData.append('title', this.newDoc.title);
                formData.append('document_number', this.newDoc.document_number || '');
                formData.append('description', this.newDoc.description || '');
                formData.append('logged_date', this.newDoc.logged_date);
                
                if (this.isEditing) {
                    formData.append('_method', 'PUT');
                }

                if (this.newDoc.files && this.newDoc.files.length) {
                    this.newDoc.files.forEach((file) => {
                        formData.append('files[]', file);
                    });
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const url = this.isEditing ? `/api/documentations/${this.editingDocId}` : '/api/documentations';

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async res => {
                    let data = {};
                    try {
                        data = await res.json();
                    } catch(e) {
                        data = { message: 'Terjadi kesalahan pada server (Status ' + res.status + ').' };
                    }

                    if (!res.ok) {
                        let errorMsg = data.message || 'Terjadi kesalahan saat menyimpan.';
                        if (data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('\n');
                        }
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    this.isUploading = false;
                    this.showDocModal = false;
                    window.location.reload();
                })
                .catch(err => {
                    this.isUploading = false;
                    alert(err.message || 'Terjadi kesalahan saat mengunggah dokumen.');
                });
            },

            deleteExistingFile(fileId) {
                if(confirm('Hapus lampiran ini secara permanen?')) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    fetch(`/api/documentation-files/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(async res => {
                        if (!res.ok) throw new Error('Gagal menghapus file.');
                        this.existingFiles = this.existingFiles.filter(f => f.id !== fileId);
                    })
                    .catch(err => {
                        alert(err.message || 'Gagal menghapus file.');
                    });
                }
            },

            executeDeleteDoc() {
                if (!this.deletingDoc) return;
                
                this.isDeleting = true;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                fetch(`/api/documentations/${this.deletingDoc.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async res => {
                    this.isDeleting = false;
                    if (!res.ok) {
                        throw new Error('Gagal menghapus dokumen.');
                    }
                    this.showDeleteModal = false;
                    window.location.reload();
                })
                .catch(err => {
                    this.isDeleting = false;
                    alert(err.message || 'Gagal menghapus dokumen.');
                });
            },

            formatFileSize(bytes) {
                if (!bytes) return '';
                if (bytes < 1024) return bytes + ' B';
                else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                else return (bytes / 1048576).toFixed(1) + ' MB';
            },

            get filteredDocs() {
                if (!this.project || !this.project.documentations) return [];
                if (this.activeSubTab === 'ALL') return this.project.documentations;
                return this.project.documentations.filter(d => (d.category || 'PROGRESS') === this.activeSubTab);
            }
        }));
    });
</script>
