<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm font-bold">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm font-bold">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <div class="w-1/3">
                    <input wire:model.live="search" type="text" class="block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm" placeholder="Search pages...">
                </div>
                <a href="{{ route('admin.pages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 inline-block">
                    Add New Page
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Title / Slug
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr wire:key="page-{{ $page->id }}">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex flex-col">
                                        <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $page->title }}</p>
                                        <p class="text-gray-500 text-xs whitespace-no-wrap">{{ $page->slug }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold {{ $page->status === 'published' ? 'text-green-900' : 'text-yellow-900' }} leading-tight">
                                        <span aria-hidden class="absolute inset-0 {{ $page->status === 'published' ? 'bg-green-200' : 'bg-yellow-200' }} opacity-50 rounded-full"></span>
                                        <span class="relative text-xs uppercase">{{ $page->status }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex gap-2 text-center items-center">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-blue-600 hover:text-blue-900 font-bold uppercase text-xs">Edit</a>
                                        <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete({{ $page->id }})" wire:loading.attr="disabled" wire:target="delete" class="text-red-600 hover:text-red-900 font-bold uppercase text-xs disabled:opacity-50">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pages->links() }}
            </div>
        </div>
    </div>
</div>
