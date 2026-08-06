<div class="clean-card p-4 sm:p-6" x-data="{
    showDocModal: false,
    isUploading: false,
    newDoc: {
        title: '',
        description: '',
        logged_date: new Date().toISOString().split('T')[0],
        files: []
    },
    
    openDocModal() {
        this.newDoc = {
            title: '',
            description: '',
            logged_date: new Date().toISOString().split('T')[0],
            files: []
        };
        this.showDocModal = true;
        
        // Reset file input if exists
        const fileInput = document.getElementById('docFiles');
        if (fileInput) fileInput.value = '';
    },
    
    // Client-side image compression
    async handleFileSelect(event) {
        const files = event.target.files;
        if (!files.length) return;
        
        this.newDoc.files = [];
        
        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            
            // If it's an image, compress it. Otherwise (like PDF), keep as is.
            if (file.type.startsWith('image/')) {
                const compressedFile = await this.compressImage(file);
                this.newDoc.files.push(compressedFile);
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
                    const MAX_WIDTH = 1200;
                    const MAX_HEIGHT = 1200;
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
                    
                    // Compress to JPEG with 0.7 quality (~150KB - 300KB)
                    canvas.toBlob((blob) => {
                        // Create a new file object from the blob
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', 0.7);
                };
            };
        });
    },
    
    saveDocumentation() {
        if(!this.newDoc.title || !this.newDoc.logged_date) {
            alert('Judul dan Tanggal wajib diisi!');
            return;
        }
        
        this.isUploading = true;
        
        const formData = new FormData();
        formData.append('project_id', this.projectId);
        formData.append('title', this.newDoc.title);
        formData.append('description', this.newDoc.description);
        formData.append('logged_date', this.newDoc.logged_date);
        
        this.newDoc.files.forEach((file) => {
            formData.append('files[]', file);
        });
        
        fetch('/api/documentations', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            this.isUploading = false;
            this.showDocModal = false;
            this.refreshProject(); // Reload project data to fetch new documentations
            this.$dispatch('notify', { msg: 'Dokumentasi berhasil ditambahkan!', type: 'success' });
        })
        .catch(err => {
            this.isUploading = false;
            alert('Terjadi kesalahan saat mengunggah.');
        });
    },

    deleteDocumentation(id) {
        if(confirm('Hapus log dokumentasi ini? File yang terkait juga akan terhapus permanen.')) {
            fetch(`/api/documentations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                }
            })
            .then(() => {
                this.refreshProject();
                this.$dispatch('notify', { msg: 'Dokumentasi dihapus.', type: 'success' });
            });
        }
    }
}">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Timeline Dokumentasi</h2>
            <p class="text-sm text-slate-500 mt-1">Catatan progress lapangan dan file terkait.</p>
        </div>
        <button @click="openDocModal()" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Log
        </button>
    </div>

    <!-- Empty State -->
    <template x-if="!project.documentations || project.documentations.length === 0">
        <div class="bg-slate-50 border border-slate-100 rounded-lg p-10 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-slate-600 font-medium mb-1">Belum ada dokumentasi</h3>
            <p class="text-slate-400 text-sm">Tambahkan log kegiatan harian/mingguan untuk melihat timeline.</p>
        </div>
    </template>

    <!-- Timeline Container -->
    <div class="relative max-w-3xl border-l-2 border-slate-200 ml-4 md:ml-6 mt-6 space-y-10 pb-8" x-show="project.documentations && project.documentations.length > 0">
        
        <template x-for="doc in project.documentations" :key="doc.id">
            <div class="relative pl-8 md:pl-10">
                <!-- Timeline Dot -->
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white border-2 border-primary ring-4 ring-white"></div>
                
                <div class="flex flex-col sm:flex-row sm:items-baseline mb-2">
                    <h3 class="text-lg font-bold text-slate-800 mr-3" x-text="doc.title"></h3>
                    <span class="text-xs font-semibold text-primary bg-blue-50 px-2.5 py-1 rounded" x-text="formatDateShort(doc.logged_date)"></span>
                </div>
                
                <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm mt-3 relative group">
                    <button @click="deleteDocumentation(doc.id)" class="absolute top-4 right-4 text-slate-300 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    
                    <p class="text-sm text-slate-600 whitespace-pre-wrap leading-relaxed mb-4" x-text="doc.description"></p>
                    
                    <!-- Attachments Gallery -->
                    <template x-if="doc.files && doc.files.length > 0">
                        <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-slate-50">
                            <template x-for="file in doc.files" :key="file.id">
                                <a :href="'/storage/' + file.file_path" target="_blank" class="block rounded border border-slate-200 overflow-hidden hover:border-primary transition-colors bg-slate-50 flex items-center justify-center group" style="width: 100px; height: 80px;">
                                    <template x-if="file.file_type.startsWith('image/')">
                                        <div class="w-full h-full bg-cover bg-center" :style="'background-image: url(/storage/' + file.file_path + ')'"></div>
                                    </template>
                                    <template x-if="!file.file_type.startsWith('image/')">
                                        <div class="text-center p-2">
                                            <svg class="w-6 h-6 text-slate-400 mx-auto mb-1 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-[9px] font-medium text-slate-500 break-all line-clamp-1" x-text="file.file_name"></span>
                                        </div>
                                    </template>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Modal Form Dokumentasi -->
    <div x-show="showDocModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="if(!isUploading) showDocModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl w-full sm:max-w-lg relative z-10 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Add Documentation Log</h3>
                <button @click="showDocModal = false" :disabled="isUploading" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal *</label>
                    <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" x-model="newDoc.logged_date" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul / Kegiatan *</label>
                    <input type="text" x-model="newDoc.title" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary" placeholder="Misal: Pengecoran Atap Lantai 2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catatan</label>
                    <textarea x-model="newDoc.description" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary" placeholder="Catatan progress atau kendala lapangan..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Lampiran Foto/Dokumen</label>
                    <input type="file" id="docFiles" multiple @change="handleFileSelect" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[10px] text-slate-400 mt-1">Foto akan dikompresi otomatis sebelum diunggah.</p>
                    
                    <template x-if="newDoc.files.length > 0">
                        <div class="mt-2 flex gap-2 flex-wrap">
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded" x-text="newDoc.files.length + ' file dipilih'"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 rounded-b-xl flex justify-end space-x-2">
                <button @click="showDocModal = false" :disabled="isUploading" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">Cancel</button>
                <button @click="saveDocumentation()" :disabled="isUploading" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <span x-show="isUploading" class="mr-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    <span x-text="isUploading ? 'Menyimpan...' : 'Save Log'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
