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

            @if (session()->has('error'))
                <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
                <h2 class="text-2xl font-bold">Manage Custom Fields</h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search fields..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <button wire:click="create()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out">Create New Field</button>
                </div>
            </div>

            @if($isModalOpen)
            <div class="absolute z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
                        <form wire:submit.prevent="store">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="">
                                    <div class="mb-4">
                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Field Name:</label>
                                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" wire:model="name">
                                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="key" class="block text-gray-700 text-sm font-bold mb-2">Key (Unique identifier):</label>
                                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="key" wire:model="key">
                                        @error('key') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="type" wire:model.live="type">
                                            <option value="text">Text</option>
                                            <option value="number">Number</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="select">Select Dropdown</option>
                                            <option value="checkbox">Checkbox</option>
                                            <option value="radio">Radio</option>
                                            <option value="date">Date</option>
                                            <option value="system">System Generated</option>
                                        </select>
                                        @error('type') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    @if(in_array($type, ['select', 'radio', 'system']))
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Options & Scores:</label>
                                        <div class="space-y-3">
                                            @foreach($optionItems as $index => $item)
                                                <div class="flex items-center space-x-2 bg-gray-50 p-2 rounded border">
                                                    <div class="flex-1">
                                                        <input type="text" wire:model="optionItems.{{ $index }}.name" placeholder="Option Name" class="w-full text-sm border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                    <div class="w-24">
                                                        <input type="number" step="0.01" wire:model="optionItems.{{ $index }}.reputation" placeholder="Rep" class="w-full text-sm border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" title="Reputation Score">
                                                    </div>
                                                    <div class="w-24">
                                                        <input type="number" step="0.01" wire:model="optionItems.{{ $index }}.privacy" placeholder="Priv" class="w-full text-sm border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" title="Privacy Score">
                                                    </div>
                                                    <button type="button" wire:click="removeOption({{ $index }})" class="text-red-500 hover:text-red-700">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" wire:click="addOption" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add Option</button>
                                    </div>
                                    @endif
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
                            <th class="px-4 py-2 text-left">Key</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $index => $field)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="border px-4 py-2 font-medium text-gray-900">{{ $loop->iteration + ($fields->firstItem() - 1) }}</td>
                            <td class="border px-4 py-2">{{ $field->name }}</td>
                            <td class="border px-4 py-2">{{ $field->key }}</td>
                            <td class="border px-4 py-2">{{ ucfirst($field->type) }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="edit({{ $field->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Edit</button>
                                @if(!$this->isProtected($field))
                                    <button wire:click="delete({{ $field->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $fields->links() }}
            </div>
        </div>
    </div>
</div>
