<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
                <h2 class="text-2xl font-bold">Manage Categories (Industries)</h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <button wire:click="create()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out">Create New Category</button>
                </div>
            </div>

            @if($isModalOpen)
            <div class="absolute z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full relative">
                        <form wire:submit.prevent="store">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="">
                                    <div class="mb-4">
                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name:</label>
                                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" wire:model.live="name" wire:change="generateSlug">
                                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug:</label>
                                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="slug" wire:model="slug">
                                        @error('slug') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" wire:model="description"></textarea>
                                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h3 class="block text-gray-700 text-sm font-bold mb-2">Assign Custom Fields:</h3>
                                        <div class="border rounded p-3 h-64 overflow-y-auto">
                                            @foreach($availableFields as $field)
                                                <div class="mb-2 p-2 border-b">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="field_{{ $field->id }}" wire:model.live="selectedFields.{{ $field->id }}.selected" class="mr-2 leading-tight">
                                                        <label for="field_{{ $field->id }}" class="text-gray-700 font-medium">{{ $field->name }} ({{ $field->type }})</label>
                                                    </div>
                                                    
                                                    @if(isset($selectedFields[$field->id]['selected']) && $selectedFields[$field->id]['selected'])
                                                        <div class="ml-6 mt-2 flex items-center space-x-4">
                                                            <label class="inline-flex items-center">
                                                                <input type="checkbox" wire:model="selectedFields.{{ $field->id }}.is_visible_in_card" class="form-checkbox h-4 w-4 text-blue-600">
                                                                <span class="ml-2 text-sm text-gray-600">Show in Card</span>
                                                            </label>
                                                            <div class="flex items-center">
                                                                <label class="text-sm text-gray-600 mr-2">Order:</label>
                                                                <input type="number" wire:model="selectedFields.{{ $field->id }}.order" class="shadow w-16 border rounded py-1 px-2 text-sm text-gray-700">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">SL No</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Slug</th>
                            <th class="px-4 py-2 text-left">Fields Count</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="border px-4 py-2 font-medium text-gray-900">{{ $loop->iteration + ($categories->firstItem() - 1) }}</td>
                            <td class="border px-4 py-2">{{ $category->name }}</td>
                            <td class="border px-4 py-2">{{ $category->slug }}</td>
                            <td class="border px-4 py-2">{{ $category->fields->count() }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="edit({{ $category->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Edit</button>
                                <button wire:click="delete({{ $category->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
