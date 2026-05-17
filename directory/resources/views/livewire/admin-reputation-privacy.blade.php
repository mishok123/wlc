<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-6">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-6" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <label for="category_select" class="block text-sm font-medium text-gray-700 mb-2">Select Category to Configure</label>
                <select id="category_select" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                    wire:model.live="selectedCategoryId">
                    <option value="">-- Choose a Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedCategory)
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Scoring Configuration: <span class="text-blue-600 font-bold">{{ $selectedCategory->name }}</span></h3>
                        
                        <div class="grid grid-cols-1 gap-4 w-full md:w-auto bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
                            <div>
                                <h4 class="text-xs font-bold text-blue-800 uppercase tracking-widest mb-3">Current Category Maximums (Dynamic)</h4>
                                <div class="flex flex-wrap gap-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-blue-600 font-bold uppercase mb-1">Reputation</span>
                                        <div class="bg-white px-4 py-2 rounded-lg border border-blue-200 shadow-sm max-w-fit">
                                            <span class="font-black text-blue-700 text-lg">{{ number_format($categoryDBMaxRep, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-green-600 font-bold uppercase mb-1">Privacy</span>
                                        <div class="bg-white px-4 py-2 rounded-lg border border-green-200 shadow-sm max-w-fit">
                                            <span class="font-black text-green-700 text-lg">{{ number_format($categoryDBMaxPriv, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-blue-600 mt-4 italic font-medium">
                                    <svg class="w-4 h-4 inline-block mr-1 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    All projects in this category are automatically scaled against these record-holding scores.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if(count($categoryFields) > 0)
                        <form wire:submit.prevent="saveScores">
                            <div class="space-y-8">
                                @foreach($categoryFields as $field)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                                        <h4 class="text-md font-bold text-gray-800 mb-3 border-b pb-2">{{ $field->name }} <span class="text-gray-400 font-normal text-sm">({{ $field->key }})</span></h4>
                                        <div class="space-y-4">
                                            @if(isset($scores[$field->id]))
                                                @foreach($scores[$field->id] as $optName => $scoreData)
                                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                                        <div class="sm:w-1/2">
                                                            <span class="text-sm font-medium text-gray-700">{{ $optName }}</span>
                                                        </div>
                                                        <div class="flex-1 flex space-x-4">
                                                            <div class="flex-1">
                                                                <label class="block text-xs text-gray-500 mb-1">Reputation Score</label>
                                                                <input type="number" step="0.01" wire:model="scores.{{ $field->id }}.{{ $optName }}.reputation" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                            </div>
                                                            <div class="flex-1">
                                                                <label class="block text-xs text-gray-500 mb-1">Privacy Score</label>
                                                                <input type="number" step="0.01" wire:model="scores.{{ $field->id }}.{{ $optName }}.privacy" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-sm text-gray-500 italic">No options defined for this field.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                    Save Category Scores
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-gray-500">No scoring fields are attached to this category.</p>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
