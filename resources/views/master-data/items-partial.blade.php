<div class="clean-card p-6" x-data="{ 
    isModalOpen: false,
    editId: null,
    formData: { name: '', satuan: '', master_category_id: '' },
    formAction: '{{ route('master-items.store') }}',
    openModal(id = null, name = '', satuan = '', category_id = '') {
        this.editId = id;
        this.formData.name = name;
        this.formData.satuan = satuan;
        this.formData.master_category_id = category_id;
        this.formAction = id ? `/master-items/${id}` : '{{ route('master-items.store') }}';
        this.isModalOpen = true;
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-sm font-bold text-slate-800">Materials & Services Directory</h2>
        </div>
        
        <button @click="openModal()" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors">
            + Add Item
        </button>
    </div>

    <!-- Modal -->
    <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" @click="isModalOpen = false" class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title" x-text="editId ? 'Edit Item' : 'Add New Item'"></h3>
                    
                    <form :action="formAction" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <template x-if="editId">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Item Name</label>
                            <input type="text" name="name" x-model="formData.name" placeholder="e.g. Semen" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Unit</label>
                            <input type="text" name="satuan" x-model="formData.satuan" placeholder="e.g. Sak" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Category</label>
                            <select name="master_category_id" x-model="formData.master_category_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary outline-none bg-white">
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
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

    <div class="overflow-x-auto border border-slate-200 rounded">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Unit</th>
                    <th class="px-6 py-2.5 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-slate-700">{{ $item->name }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 inline-flex text-[10px] font-medium rounded border bg-slate-50 border-slate-200 text-slate-600">
                            {{ $item->category ? $item->category->name : 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-xs text-slate-500 font-medium">{{ $item->satuan }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-right text-xs font-medium">
                        <button type="button" @click="openModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->satuan) }}', '{{ $item->master_category_id }}')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                        <form action="{{ route('master-items.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-xs text-slate-500">No items available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
