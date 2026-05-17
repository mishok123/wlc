<div x-data="{ filtersOpen: false }" class="bg-[var(--dir-bg)] min-h-screen font-sans text-[var(--dir-text)]">
    <div class="container mx-auto px-4 py-8">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- SIDEBAR FILTERS (Drawer on Mobile, Static on Desktop) --}}
            <div :class="filtersOpen ? 'fixed inset-0 z-50 lg:relative lg:inset-auto lg:z-0' : 'hidden lg:block lg:relative'" class="lg:w-64 flex-shrink-0">
                {{-- Backdrop for Mobile Drawer --}}
                <div x-show="filtersOpen" @click="filtersOpen = false" class="fixed inset-0 bg-black/60 lg:hidden transition-opacity"></div>
                
                {{-- Filter Container --}}
                <div :class="filtersOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
                     class="fixed inset-y-0 left-0 w-80 max-w-[80vw] bg-[var(--dir-bg)] border-r border-gray-800 p-6 overflow-y-auto transition-transform duration-300 ease-in-out z-50
                            lg:static lg:w-full lg:p-0 lg:border-none lg:overflow-visible lg:translate-x-0 lg:z-0">
                    
                    {{-- Mobile Header --}}
                    <div class="flex justify-between items-center mb-6 lg:hidden">
                        <h2 class="text-xl font-bold text-gray-100">Filters</h2>
                        <button @click="filtersOpen = false" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-8">
                        {{-- Header (Desktop) --}}
                        <div class="hidden lg:flex justify-between items-center mb-4">
                            <h2 class="text-[var(--dir-accent)] font-bold tracking-wider text-sm uppercase">Filters</h2>
                            <button wire:click="resetFilters" class="text-xs text-gray-400 hover:text-white transition">Clear all</button>
                        </div>

                        {{-- Categories (Type) --}}
                        <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                            <h3 class="text-[var(--dir-accent)] font-bold mb-4 tracking-tight uppercase text-xs">Business Industry</h3>
                            <div class="space-y-2.5">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="radio" wire:model.live="category" value="" class="hidden">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ $category == '' ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if($category == '') <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ $category == '' ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">All Industries</span>
                                     <span class="ml-auto text-xs font-bold {{ $category == '' ? 'text-green-500' : 'text-gray-500' }}">{{ $totalProjects }}</span>
                                </label>

                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-2.5 cursor-pointer group" wire:key="category-{{ $cat->id }}">
                                        <input type="radio" wire:model.live="category" value="{{ $cat->slug }}" class="hidden">
                                        <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ $category == $cat->slug ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                             @if($category == $cat->slug) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                        </div>
                                        <span class="text-sm font-medium {{ $category == $cat->slug ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">{{ $cat->name }}</span>
                                        <span class="ml-auto text-xs font-bold {{ $category == $cat->slug ? 'text-green-500' : 'text-gray-500' }}">{{ $cat->projects_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 1. KYC INFORMATION --}}
                        @if(isset($filterFields[31]))
                            <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                                <h3 class="text-[var(--dir-accent)] font-bold mb-4 tracking-tight uppercase text-xs">KYC INFORMATION</h3>
                                <div class="space-y-2.5">
                                    @foreach($filterFields[31]->options as $opt)
                                        @php $filterKey = "activeFilters.31.{$opt}"; @endphp
                                        <label class="flex items-center gap-2.5 cursor-pointer group" wire:key="filter-31-{{ $loop->index }}">
                                            <input type="checkbox" wire:model.live="{{ $filterKey }}" class="absolute opacity-0 w-0 h-0">
                                            <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[31][$opt]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                                @if(!empty($activeFilters[31][$opt])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                            </div>
                                            <span class="text-[11px] font-medium {{ !empty($activeFilters[31][$opt]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">{{ $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 2. GUARANTEE FUND --}}
                        @if(isset($filterFields[30]))
                            <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                                <h3 class="text-[var(--dir-accent)] font-bold mb-4 tracking-tight uppercase text-xs">GUARANTEE FUND</h3>
                                <div class="space-y-2.5">
                                    @foreach($filterFields[30]->options as $opt)
                                        @php $filterKey = "activeFilters.30.{$opt}"; @endphp
                                        <label class="flex items-center gap-2.5 cursor-pointer group" wire:key="filter-30-{{ $loop->index }}">
                                            <input type="checkbox" wire:model.live="{{ $filterKey }}" class="absolute opacity-0 w-0 h-0">
                                            <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[30][$opt]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                                @if(!empty($activeFilters[30][$opt])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                            </div>
                                            <span class="text-[11px] font-medium {{ !empty($activeFilters[30][$opt]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">{{ $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 3. Status Filters (Verified, Approved, Ownership) --}}
                        <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                            <div class="space-y-3">
                                {{-- Verified Only --}}
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="checkbox" wire:model.live="verified" class="absolute opacity-0 w-0 h-0">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ $verified ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if($verified) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ $verified ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">Verified Only</span>
                                </label>

                                {{-- Approved --}}
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="checkbox" wire:model.live="approved" class="absolute opacity-0 w-0 h-0">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ $approved ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if($approved) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ $approved ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">Approved Only</span>
                                </label>

                                {{-- Ownership Verified --}}
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="checkbox" wire:model.live="activeFilters.16" class="absolute opacity-0 w-0 h-0">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[16]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if(!empty($activeFilters[16])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ !empty($activeFilters[16]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">Ownership verified</span>
                                </label>
                            </div>
                        </div>

                        {{-- 4. Sliders Block (Business, Support, Fee) --}}
                        <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm space-y-6">
                            {{-- In Business --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    @php 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        $ageLabels = [
                                            0 => '0-1 YEAR',
                                            1 => '1+ YEAR',
                                            2 => '2+ YEAR',
                                            3 => '3+ YEAR',
                                            4 => '4+ YEAR',
                                            5 => '5 AND OVER',
                                        ];
                                        $currentAge = $activeFilters[28]['min'] ?? 0;
                                        $displayAge = $ageLabels[$currentAge] ?? '5 AND OVER';
                                    @endphp
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">IN BUSINESS ({{ $displayAge }})</label>
                                </div>
                                <input type="range" min="0" max="5" step="1" 
                                       wire:model.live.debounce.500ms="activeFilters.28.min" 
                                       value="{{ $currentAge }}"
                                       class="w-full h-1.5 bg-gray-700/50 rounded-lg appearance-none cursor-pointer accent-[var(--dir-accent)]">
                            </div>

                            {{-- Customer Support Rating --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    @php $currentRating = $activeFilters[57]['min'] ?? 1; @endphp
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-sm font-bold text-white">Customer Support Rating ({{ $currentRating }}★+)</label>
                                </div>
                                <input type="range" min="1" max="5" step="1" 
                                       wire:model.live.debounce.500ms="activeFilters.57.min" 
                                       value="{{ $currentRating }}"
                                       class="w-full h-1.5 bg-gray-700/50 rounded-lg appearance-none cursor-pointer accent-[var(--dir-accent)]">
                            </div>

                            {{-- Maximum Project Fee --}}
                            <div>
                                @php $currentMaxFee = $activeFilters['fees_range']['max'] ?? $maxFee; @endphp
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-sm font-bold text-white">Maximum Project Fee ({{ $currentMaxFee }}%)</label>
                                <input type="range" min="{{ $minFee }}" max="{{ $maxFee }}" step="0.01" 
                                       wire:model.live.debounce.500ms="activeFilters.fees_range.max" 
                                       value="{{ $currentMaxFee }}"
                                       class="w-full h-1.5 bg-gray-700/50 rounded-lg appearance-none cursor-pointer accent-[var(--dir-accent)]">
                            </div>
                        </div>

                        {{-- 5. Feature Checkboxes Block --}}
                        <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                            <div class="space-y-3">
                                {{-- Have Flat Rates (Field 48) --}}
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="checkbox" wire:model.live="activeFilters.48" class="absolute opacity-0 w-0 h-0">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[48]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if(!empty($activeFilters[48])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ !empty($activeFilters[48]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">Have Flat Rates</span>
                                </label>

                                {{-- Tor/Onion --}}
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                     <input type="checkbox" wire:model.live="onion" class="absolute opacity-0 w-0 h-0">
                                     <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ $onion ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                        @if($onion) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                     </div>
                                     <span class="text-sm font-medium {{ $onion ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">Tor/Onion</span>
                                </label>

                                @php
                                    $features = [
                                        32 => 'Letter of Guarantee',
                                        34 => 'No Log Policy',
                                        19 => 'No Registration Policy',
                                        35 => 'Own Liquidity/Bankroll',
                                        45 => 'Potential Risk',
                                        37 => 'Code Audited by Third Party'
                                    ];
                                @endphp
                                @foreach($features as $fid => $fname)
                                    <label class="flex items-center gap-2.5 cursor-pointer group" wire:key="feature-{{ $fid }}">
                                         <input type="checkbox" wire:model.live="activeFilters.{{ $fid }}" class="absolute opacity-0 w-0 h-0">
                                         <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[$fid]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                            @if(!empty($activeFilters[$fid])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                         </div>
                                         <span class="text-sm font-medium {{ !empty($activeFilters[$fid]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">{{ $fname }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 6. SERVICE FEE FEELING --}}
                        @if(isset($filterFields[7]))
                            <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 shadow-sm">
                                <h3 class="text-[var(--dir-accent)] font-bold mb-4 tracking-tight uppercase text-xs">SERVICE FEE FEELING</h3>
                                <div class="space-y-2.5">
                                    @foreach($filterFields[7]->options as $opt)
                                        @php $filterKey = "activeFilters.7.{$opt}"; @endphp
                                        <label class="flex items-center gap-2.5 cursor-pointer group" wire:key="filter-7-{{ $loop->index }}">
                                            <input type="checkbox" wire:model.live="{{ $filterKey }}" class="absolute opacity-0 w-0 h-0">
                                            <div class="w-4 h-4 border border-gray-600 rounded flex items-center justify-center {{ !empty($activeFilters[7][$opt]) ? 'bg-green-600 border-green-600' : 'group-hover:border-green-500' }}">
                                                @if(!empty($activeFilters[7][$opt])) <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg> @endif
                                            </div>
                                            <span class="text-[11px] font-medium {{ !empty($activeFilters[7][$opt]) ? 'text-white' : 'text-gray-400' }} group-hover:text-white transition">{{ $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Mobile Reset Button --}}
                        <div class="lg:hidden pt-4">
                            <button wire:click="resetFilters" @click="filtersOpen = false" class="w-full py-3 bg-gray-800 border border-gray-700 rounded-lg text-sm font-bold uppercase tracking-wider text-gray-300 hover:text-white transition">
                                Clear All Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="flex-1">
                
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-2">
                    <div class="flex items-center gap-3">
                        {{-- Mobile Filter Toggle --}}
                        <button @click="filtersOpen = true" class="lg:hidden p-2 bg-gray-800 border border-gray-700 rounded-lg text-[var(--dir-accent)] hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>

                        <div>
                            <p class="text-lg md:text-xl text-gray-100 mb-1">
                                We Help You Find Trusted Crypto Platforms & Services - Let’s Start by Avoiding Scams.
                            </p>
                            <h1 class="text-xs md:text-sm font-bold uppercase tracking-wider text-gray-400">
                            @if($verified && $approved)
                                Approved and Verified services
                            @elseif($verified)
                                Verified services
                            @elseif($approved)
                                Approved services
                            @else
                                Approved and Verified services
                            @endif
                        </h1>
                    </div>
                </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-400">{{ $projects->total() }} results</span>
                         <a href="{{ route('projects.submit') }}" class="px-4 py-2 bg-black border border-gray-700 hover:border-gray-500 rounded text-sm font-medium transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add service
                        </a>
                    </div>
                </div>

                {{-- TOP FILTERS BAR --}}
                <div class="bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-xl p-4 mb-8 flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-[var(--dir-accent)] mb-2 uppercase tracking-tight">Name</label>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." class="w-full bg-gray-800/50 border border-gray-700 text-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-1 focus:ring-[var(--dir-accent)] focus:border-[var(--dir-accent)] outline-none placeholder-gray-600 transition">
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-[var(--dir-accent)] mb-2 uppercase tracking-tight">Sort By</label>
                        <select wire:model.live="sort" class="w-full bg-gray-800/50 border border-gray-700 text-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-1 focus:ring-[var(--dir-accent)] focus:border-[var(--dir-accent)] outline-none transition cursor-pointer">
                            <option value="score_high_low">Score (High &rarr; Low)</option>
                            <option value="score_low_high">Score (Low &rarr; High)</option>
                            <option value="a_z">Name (A-Z)</option>
                            <option value="newest">Newest Added</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto flex flex-col">
                        <label class="block text-xs font-bold text-[var(--dir-accent)] mb-2 uppercase tracking-tight">View</label>
                        <div class="flex bg-gray-900 border border-gray-850 p-1 rounded-lg shadow-inner gap-1 w-fit">
                            <button wire:click="$set('viewMode', 'grid')" 
                                    class="p-1.5 rounded-md transition duration-200 flex items-center justify-center {{ $viewMode === 'grid' ? 'bg-green-600/20 text-white border border-green-850 shadow-sm shadow-green-900/50' : 'text-gray-500 hover:text-gray-300 border border-transparent' }}"
                                    title="Grid View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button wire:click="$set('viewMode', 'list')" 
                                    class="p-1.5 rounded-md transition duration-200 flex items-center justify-center {{ $viewMode === 'list' ? 'bg-green-600/20 text-white border border-green-850 shadow-sm shadow-green-900/50' : 'text-gray-500 hover:text-gray-300 border border-transparent' }}"
                                    title="List View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if($projects->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 px-4 text-center bg-[var(--sidebar-filter-bg)] border border-[var(--sidebar-filter-border)] rounded-2xl shadow-2xl">
                        <div class="w-24 h-24 bg-gray-800/80 rounded-full flex items-center justify-center mb-6 border border-gray-700/50 shadow-inner">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-100 mb-3 tracking-tight">No projects found</h3>
                        <p class="text-gray-400 mb-8 max-w-sm mx-auto leading-relaxed">
                            We couldn't find any services matching your current filters. Try broadening your criteria or search terms.
                        </p>
                        <button wire:click="resetFilters" class="group relative px-8 py-3 bg-[var(--dir-accent)] text-black font-bold rounded-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-3 shadow-[0_0_20px_rgba(34,197,94,0.3)]">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear all filters
                        </button>
                    </div>
                @else
                        <div class="{{ $viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6' : 'space-y-4' }}">
                            @foreach($projects as $p)
                                                            @php
                                                                $fieldValues = $p->fieldValues->keyBy('field_id');
                                                                $visibleFields = $p->category ? $p->category->fields->where('pivot.is_visible_in_card', true) : collect();

                                                                // Helper to get field value by name (case-insensitive) reliably from eager-loaded fields
                                                                $getVal = function ($name) use ($fieldValues) {
                                                                    $fv = $fieldValues->first(function ($v) use ($name) {
                                                                        return $v->field && (strtolower($v->field->name) === strtolower($name) || strtolower($v->field->key) === strtolower($name));
                                                                    });
                                                                    return $fv ? $fv->value : null;
                                                                };

                                                                // Calculate Age
                                                                $launchDate = $getVal('Launch Date');
                                                                $ageBracket = $getVal('Age');
                                                                $ageFormatted = null;
                                                                if ($launchDate) {
                                                                    try {
                                                                        $start = \Carbon\Carbon::parse($launchDate);
                                                                        $years = (int) $start->diffInYears(\Carbon\Carbon::now());
                                                                        $ageFormatted = $years . 'Y+';
                                                                    } catch (\Exception $e) {
                                                                    }
                                                                } elseif ($ageBracket) {
                                                                    // Fallback to existing age bracket if launch_date is missing
                                                                    $ageFormatted = str_replace(['Age ', ' year', 'year'], '', $ageBracket);
                                                                    $ageNum = (int) $ageFormatted;
                                                                    $ageFormatted = $ageNum . '+';
                                                                    if (!str_contains($ageFormatted, 'Y')) {
                                                                        $ageFormatted = $ageNum . 'Y+';
                                                                    }
                                                                }

                                                                // Calculate Scaled Scores (using category-specific dynamic maximums)
                                                                $maxRep = $categoryMaxes[$p->category_id]['rep'] ?? 100;
                                                                $maxPriv = $categoryMaxes[$p->category_id]['priv'] ?? 100;

                                                                $rawRep = $p->reputation_score ?? 0;
                                                                $rawPriv = $p->privacy_score ?? 0;

                                                                $scaledRep = ($rawRep / ($maxRep ?: 1)) * 10;
                                                                $scaledPriv = ($rawPriv / ($maxPriv ?: 1)) * 10;

                                                                // Formula: (((Reputation + Privacy) / (MaxRep + MaxPriv)) * 10)
                                                                $scaledWLC = (($rawRep + $rawPriv) / (($maxRep + $maxPriv) ?: 1)) * 10;

                                                                // Determine WLC Score Box Color and Label
                                                                if ($scaledWLC < 0.0) {
                                                                    $wlcBgColor = 'bg-[var(--score-poor-bg)]';
                                                                    $wlcTextColor = 'text-white';
                                                                    $wlcLabel = 'Very Poor';
                                                                } elseif ($scaledWLC < 5.0) {
                                                                    $wlcBgColor = 'bg-[var(--score-poor-bg)]';
                                                                    $wlcTextColor = 'text-white';
                                                                    $wlcLabel = 'Poor';
                                                                } elseif ($scaledWLC < 7.0) {
                                                                    $wlcBgColor = 'bg-[var(--score-fair-bg)]';
                                                                    $wlcTextColor = 'text-gray-900';
                                                                    $wlcLabel = 'Good';
                                                                } elseif ($scaledWLC < 8.5) {
                                                                    $wlcBgColor = 'bg-[var(--score-vgood-bg)]';
                                                                    $wlcTextColor = 'text-white';
                                                                    $wlcLabel = 'Very Good';
                                                                } else {
                                                                    $wlcBgColor = 'bg-[var(--score-excellent-bg)]';
                                                                    $wlcTextColor = 'text-white';
                                                                    $wlcLabel = 'Excellent';
                                                                }

                                                                // Potential Risk
                                                                $potentialRiskField = $visibleFields->first(fn($f) => strtolower($f->key) === 'potential_risk');
                                                                $hasRisk = false;
                                                                $riskMsg = '';
                                                                if ($potentialRiskField) {
                                                                    $val = $fieldValues->get($potentialRiskField->id)?->value;
                                                                    if ($val && strtolower($val) === 'yes') {
                                                                        $hasRisk = true;
                                                                        // Assuming the admin message would be stored here or we default to a standard message
                                                                        $riskMsg = "Admin potential risk message detected.";
                                                                    }
                                                                }

                                                                // Escrow Amount for Icon
                                                                $escrowField = $visibleFields->first(fn($f) => strtolower($f->key) === 'guarantee_fund');
                                                                $escrowAmount = null;
                                                                $escrowTooltip = '';
                                                                if ($escrowField) {
                                                                    $val = $fieldValues->get($escrowField->id)?->value;
                                                                    if ($val && !str_contains(strtolower($val), 'no ')) {
                                                                        if (str_contains($val, 'under $10k')) {
                                                                            $escrowAmount = '$10k';
                                                                            $escrowTooltip = 'A SMALL SUM of ESCROW secured';
                                                                        } elseif (str_contains($val, '$10k to $20k')) {
                                                                            $escrowAmount = '$20k';
                                                                            $escrowTooltip = 'A GOOD SUM of ESCROW secured';
                                                                        } elseif (str_contains($val, 'to $50k')) {
                                                                            $escrowAmount = '$50k';
                                                                            $escrowTooltip = 'A LARGE SUM of ESCROW secured';
                                                                        } elseif (str_contains($val, 'above $50k')) {
                                                                            $escrowAmount = '$50k+';
                                                                            $escrowTooltip = 'A VERY LARGE SUM of ESCROW secured';
                                                                        }
                                                                    }
                                                                }

                                                                // Specific fields to extract
                                                                $tor = $getVal('Have Tor') ?? $p->supported_networks['tor'] ?? false;
                                                                $i2p = $getVal('I2P') ?? $p->supported_networks['i2p'] ?? false;
                                                                $p2p = $getVal('P2P') ?? false; 
                                                            

                                                                    $getBool = function ($name) use ($getVal) {
                                                                        $val = $getVal($name);
                                                                        if (!$val)
                                                                            return null;
                                                                        if (in_array(strtolower($val), ['1', 'true', 'yes']))
                                                                            return true;
                                                                        if (in_array(strtolower($val), ['0', 'false', 'no']))
                                                                            return false;
                                                                        return filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                                                                    };

                                                                    // Row 1 Attributes
                                                                    $rawCoins = $getVal('Supported Coin') ?? $getVal('Cryptocurrency');
                                                                    $coins = $rawCoins ? array_filter(array_map('trim', explode(',', $rawCoins))) : [];

                                                                    $logVerifiable = $getBool('LOG Verifiable Feature');

                                                                    $feeMin = $getVal('Fees Min');
                                                                    $feeMax = $getVal('Fees Max');
                                                                    $feeFixed = $getVal('Fees Fixed');
                                                                    $feeStr = [];
                                                                    if ($feeMin || $feeMax) {
                                                                        $rng = "FEE ";
                                                                        if ($feeMin)
                                                                            $rng .= $feeMin . "%";
                                                                        if ($feeMin && $feeMax)
                                                                            $rng .= " TO ";
                                                                        if ($feeMax)
                                                                            $rng .= $feeMax . "%";
                                                                        $feeStr[] = $rng;
                                                                    }
                                                                    if ($feeFixed)
                                                                        $feeStr[] = "+ " . $feeFixed;
                                                                    $feeString = implode(' ', $feeStr);

                                                                    // Col 1 Policies
                                                                    $regPolicy = $getBool('No Registration Policy');
                                                                    $logPolicy = $getBool('No Log Policy');
                                                                    $ownLiquidity = $getBool('Own Liquidity/Bankroll');
                                                                    $ownLiquidityVal = $getVal('Own Liquidity/Bankroll');

                                                                    $codeAudited = $getBool('Code Audited by Third Party');
                                                                    $codeAuditedVal = $getVal('Code Audited by Third Party');

                                                                    // Col 2 
                                                                    $hasTor = ($getVal('Have Tor') === 'Yes') || (!empty($p->supported_networks['tor']));
                                                                    $torUrl = $getVal('Tor URLs') ?? $p->supported_networks['tor'] ?? null;
                                                                    $supportRatingRaw = $p->getDynamicField('customer_support_rating');
                                                                    $supportRating = $supportRatingRaw ? intval($supportRatingRaw) : null;

                                                                    // Support Rating Color/Border logic
                                                                    $ratingColor = 'text-[var(--score-poor)]';
                                                                    $ratingBorder = 'border-[var(--score-poor-border)]';
                                                                    if ($supportRating >= 5) {
                                                                        $ratingColor = 'text-[var(--score-excellent)]';
                                                                        $ratingBorder = 'border-[var(--score-excellent-border)]';
                                                                    } elseif ($supportRating >= 4) {
                                                                        $ratingColor = 'text-[var(--score-good)]';
                                                                        $ratingBorder = 'border-[var(--score-good-border)]';
                                                                    } elseif ($supportRating >= 3) {
                                                                        $ratingColor = 'text-[var(--score-fair)]';
                                                                        $ratingBorder = 'border-[var(--score-fair-border)]';
                                                                    }

                                                                    // Footer
                                                                    $kyc = $getVal('KYC Information');
                                                                    if ($kyc && preg_match('/(\d)/', $kyc, $kycMatches)) {
                                                                        $kycNum = $kycMatches[1];
                                                                        $kycBadge = $kycNum . '/4';
                                                                    } else {
                                                                        $kycBadge = '0/4';
                                                                        $kycNum = 0;
                                                                    }

                                                                    $communityText = $getVal('Community');

                                                                    // Reputation / Privacy Score Colors & Borders
                                                                    $repColor = 'text-[var(--score-poor)]';
                                                                    $repBorder = 'border-[var(--score-poor-border)]';
                                                                    if ($scaledRep >= 8.5) {
                                                                        $repColor = 'text-[var(--score-excellent)]';
                                                                        $repBorder = 'border-[var(--score-excellent-border)]';
                                                                    } elseif ($scaledRep >= 7) {
                                                                        $repColor = 'text-[var(--score-good)]';
                                                                        $repBorder = 'border-[var(--score-good-border)]';
                                                                    } elseif ($scaledRep >= 5) {
                                                                        $repColor = 'text-[var(--score-fair)]';
                                                                        $repBorder = 'border-[var(--score-fair-border)]';
                                                                    }

                                                                    $privColor = 'text-[var(--score-poor)]';
                                                                    $privBorder = 'border-[var(--score-poor-border)]';
                                                                    if ($scaledPriv >= 8.5) {
                                                                        $privColor = 'text-[var(--score-excellent)]';
                                                                        $privBorder = 'border-[var(--score-excellent-border)]';
                                                                    } elseif ($scaledPriv >= 7) {
                                                                        $privColor = 'text-[var(--score-good)]';
                                                                        $privBorder = 'border-[var(--score-good-border)]';
                                                                    } elseif ($scaledPriv >= 5) {
                                                                        $privColor = 'text-[var(--score-fair)]';
                                                                        $privBorder = 'border-[var(--score-fair-border)]';
                                                                    }
                                                                


                                                                    // Consolidate variables for both Grid & List Views
                                                                    $kycTooltip = match ((string) $kycNum) {
                                                                        '0' => 'Guaranteed no KYC. Report if ever asked for KYC',
                                                                        '1' => 'No clause about KYC in the terms. May or may not ask for KYC',
                                                                        '2' => 'Can ask for KYC when requested by authorities for investigation.',
                                                                        '3', '4' => 'Strict KYC policy requires to access features otherwise funds can be blocked',
                                                                        default => 'KYC Policy'
                                                                    };

                                                                    $launchDate = $p->getDynamicField('launch_date');
                                                                    $ageFormatted = null;
                                                                    if ($launchDate) {
                                                                        try {
                                                                            $launchCarbon = \Illuminate\Support\Carbon::parse($launchDate);
                                                                            $years = (int) $launchCarbon->diffInYears(now());
                                                                            if ($years >= 1) {
                                                                                $ageFormatted = $years . 'y+ old';
                                                                            } else {
                                                                                $months = (int) $launchCarbon->diffInMonths(now());
                                                                                if ($months >= 1) {
                                                                                    $ageFormatted = $months . 'm+ old';
                                                                                } else {
                                                                                    $ageFormatted = 'New';
                                                                                }
                                                                            }
                                                                        } catch (\Exception $e) {
                                                                        }
                                                                    }

                                                                    $ownLiquidityVal = $getVal('Own Liquidity/Bankroll');
                                                                    $liquidityAmount = $getVal('liquidity_amount');
                                                                    $ownLiquidityTooltipText = 'Platform does not have their own Liquidity for the business. May use a thirdpary application / API';
                                                                    if ($ownLiquidity) {
                                                                        if (strtolower($ownLiquidityVal) !== 'yes' && $ownLiquidityVal) {
                                                                            $ownLiquidityTooltipText = $ownLiquidityVal;
                                                                        } else {
                                                                            if ($liquidityAmount && is_numeric($liquidityAmount)) {
                                                                                $ownLiquidityTooltipText = 'Available: $' . number_format($liquidityAmount);
                                                                            } else {
                                                                                $ownLiquidityTooltipText = 'Available';
                                                                            }
                                                                        }
                                                                    }@endphp

                                                            @if($viewMode === 'grid')
                            <div wire:key="project-{{ $p->id }}" class="bg-[var(--card-bg)] rounded-xl border border-[var(--card-border)] hover:border-gray-600 transition duration-300 p-2 flex flex-col gap-2 relative group h-full">

                                                                <div class="flex gap-3 items-start">
                                                                    {{-- Logo --}}
                                                                    <div class="flex-shrink-0">
                                                                        <a href="{{ route('projects.show', $p) }}" class="block w-16 h-16 bg-gray-800 rounded-lg border border-gray-700 overflow-hidden flex items-center justify-center">
                                                                            @if($p->logo)
                                                                                <img src="{{ Storage::url($p->logo) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                                                            @else
                                                                                <span class="text-gray-500 text-xs font-bold">logo</span>
                                                                            @endif
                                                                        </a>
                                                                    </div>

                                                                    {{-- Header Content (Title, Cat, Status) --}}
                                                                    <div class="flex-grow min-w-0 flex justify-between items-start gap-3">
                                                                        <div>
                                                                            <div class="flex items-center gap-2 mb-1">
                                                                                <span class="flex-shrink-0 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-[var(--card-cat-bg)] text-[var(--card-cat-text)] border border-[var(--card-cat-border)] whitespace-nowrap">
                                                                                    {{ $p->category->name }}
                                                                                </span>
                                                                                @if($hasRisk)
                                                                                    <div x-data="{ showRisk: false }" class="relative flex-shrink-0 text-[10px] font-bold uppercase bg-[var(--card-risk-bg)] text-[var(--card-risk-text)] border border-red-800 cursor-pointer whitespace-nowrap rounded">
                                                                                        <button @click="showRisk = !showRisk" @click.away="showRisk = false" class="px-2 py-0.5 w-full h-full">
                                                                                            POTENTIAL RISK DETECTED
                                                                                        </button>
                                                                                        <div x-show="showRisk" style="display: none;" class="absolute z-50 top-[calc(100%+4px)] left-0 w-64 bg-gray-900 border border-gray-700 text-gray-200 text-xs p-3 rounded shadow-xl normal-case font-normal whitespace-normal">
                                                                                            {{ $riskMsg }}
                                                                                            <div class="absolute top-0 left-4 -mt-2 border-8 border-transparent border-b-gray-700"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                @if($p->source_code_availability)
                                                                                    <span class="flex-shrink-0 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-900/40 text-green-400 border border-green-800/50 whitespace-nowrap">
                                                                                        Open Source
                                                                                    </span>
                                                                                @endif
                                                                            </div>

                                                                            <div class="flex items-center gap-2">
                                                                                <h3 class="font-semibold text-lg text-[var(--card-title)] leading-tight max-w-[180px]" title="{{ $p->name }}">
                                                                                    <a href="{{ route('projects.show', $p) }}" class="hover:text-[var(--dir-accent)] transition truncate block">
                                                                                        {{ \Illuminate\Support\Str::limit($p->name, 20) }}
                                                                                    </a>
                                                                                </h3>

                                                                                {{-- Status Icons --}}
                                                                                @if($p->list_status === 'verified')
                                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex items-center justify-center cursor-help">
                                                                                                                                            <svg class="w-5 h-5 text-[var(--card-status-verified)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                      <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                      <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                    </svg>WLC Verified</div>
                                                                                                                                        </div>
                                                                                @elseif($p->list_status === 'approved')
                                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex items-center justify-center cursor-help">
                                                                                                                                            <svg class="w-5 h-5 text-[var(--card-status-approved)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                      <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                      <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                    </svg>WLC Approved</div>
                                                                                                                                        </div>
                                                                                @elseif($p->list_status === 'pending')
                                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="w-6 h-6 bg-[var(--card-status-pending)] rounded flex items-center justify-center text-white relative cursor-help">
                                                                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                      <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                      <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                    </svg>Waiting for WLC approval</div>
                                                                                                                                        </div>
                                                                                @elseif($p->list_status === 'scam')
                                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="w-6 h-6 bg-[var(--card-status-scam)] rounded flex items-center justify-center text-white relative cursor-help">
                                                                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                      <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                      <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                    </svg>Warning! Scam Platform.</div>
                                                                                                                                        </div>
                                                                                @endif

                                                                                {{-- Escrow Shield --}}
                                                                                @if($escrowAmount)
                                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex flex-col items-center justify-center cursor-help">
                                                                                                                                            <div class="w-7 h-8 bg-[var(--card-escrow)] flex items-center justify-center text-white text-[9px] font-bold" style="clip-path: polygon(50% 0%, 100% 0, 100% 75%, 50% 100%, 0% 75%, 0 0);">
                                                                                                                                                <span class="-mt-1">{{ $escrowAmount }}</span>
                                                                                                                                            </div>
                                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                      <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                      <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                    </svg>
                                                                                                                                                {{ $escrowTooltip }}
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                @endif
                                                                            </div>

                                                                            {{-- Ownership Verified Banner --}}
                                                                            @if($p->ownership_verified)
                                                                                <div class="inline-flex items-center gap-1.5">
                                                                                    <svg class="w-3.5 h-3.5 text-[var(--card-status-approved)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                                                    <span class="text-[11px] font-bold uppercase text-[var(--card-ownership)] tracking-wider">OWNERSHIP VERIFIED</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        {{-- WLC Score Box --}}
                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex-shrink-0 flex items-center justify-center px-1.5 py-0.5 rounded shadow-sm relative cursor-help {{ $wlcBgColor }}">
                                                                            <div class="text-[12px] font-black tracking-tight {{ $wlcTextColor }}">{{ number_format($scaledWLC, 2) }}</div>
                                                                            <div x-show="tooltip" class="absolute bottom-[calc(100%+4px)] right-0 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded shadow-lg whitespace-nowrap font-normal">
                                <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>{{ $wlcLabel }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Description --}}
                                                                <p class="text-[var(--card-text)] text-xs leading-relaxed line-clamp-2 h-[38px] overflow-hidden">
                                                                    {{ $p->description }}
                                                                </p>

                                                                

                                                                {{-- Row 1: Coins, Logs, Fee --}}
                                                                <div class="flex flex-wrap justify-between items-center w-full gap-2">
                                                                    {{-- Coins --}}
                                                                    @if(count($coins) > 0)
                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[10px] font-bold bg-[var(--card-coins-bg)] text-[var(--card-coins-text)] border border-[var(--card-coins-border)] flex items-center relative cursor-help">
                                                                            {{ $coins[0] }} {{ count($coins) > 1 ? '+' . (count($coins) - 1) : '' }}
                                                                            @if(count($coins) > 1)
                                                                                                                                <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white whitespace-nowrap">
                                                                                <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                                </svg>
                                                                                                                                    {{ implode(', ', array_slice($coins, 1)) }}
                                                                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif

                                                                    {{-- Log Verifiable --}}
                                                                    @if($logVerifiable !== null)
                                                                        @if($logVerifiable)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[10px] font-bold bg-[var(--card-log-true-bg)] text-[var(--card-log-true-text)] border border-[var(--card-log-true-border)] relative cursor-help">
                                                                                                                            LOG VERIFIABLE
                                                                                                                            <div x-show="tooltip" style="display: none;" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-3 rounded-lg shadow-xl text-xs text-white font-normal w-72 whitespace-normal flex flex-col gap-2" @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                                                                                                                <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                                                                                                <div class="font-bold border-b border-gray-800 pb-1 text-green-400">LOG VERIFIABLE DETAILS</div>
                                                                                                                                @if($p->bitcoin_address)
                                                                                                                                    <div class="flex flex-col gap-0.5">
                                                                                                                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Bitcoin Address</span>
                                                                                                                                        <div class="font-mono bg-gray-950 p-1.5 rounded border border-gray-800 break-all select-all text-[11px] text-gray-200">{{ $p->bitcoin_address }}</div>
                                                                                                                                    </div>
                                                                                                                                @else
                                                                                                                                    <div class="text-[10px] text-gray-500 italic">No Bitcoin Address provided.</div>
                                                                                                                                @endif
                                                                                                                                @if($p->pgp_public_key)
                                                                                                                                    <div class="mt-1">
                                                                                                                                        <a href="data:text/plain;charset=utf-8,{{ rawurlencode($p->pgp_public_key) }}" download="{{ \Illuminate\Support\Str::slug($p->name) }}-pgp-key.txt" class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-600 hover:bg-green-500 text-white font-bold rounded text-[10px] uppercase transition shadow-md w-full justify-center">
                                                                                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>Download PGP Key
                                                                                                                                        </a>
                                                                                                                                    </div>
                                                                                                                                @else
                                                                                                                                    <div class="text-[10px] text-gray-500 italic">No PGP Public Key provided.</div>
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @else
                                                                                                                        <div class="px-2 py-0.5 text-[9px] bg-[var(--card-log-false-bg)] text-[var(--card-log-false-text)] border border-[var(--card-log-false-border)] font-bold uppercase rounded">
                                                                                                                            LOG VERIFIABLE
                                                                                                                            
                                                                            
                                                                              
                                                                              
                                                                            
                                                                                                                        </div>
                                                                        @endif
                                                                    @endif

                                                                    {{-- Fee --}}
                                                                    @if($feeString)
                                                                        <div class="px-2 py-0.5 rounded text-[10px] font-bold bg-[var(--card-fee-bg)] text-[var(--card-fee-text)] border border-[var(--card-fee-border)] leading-tight flex items-center">
                                                                            {{ $feeString }}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- Col 1 & Col 2: Policies and Scores --}}
                                                                <div class="flex flex-row justify-between gap-2">

                                                                    {{-- Col 1 --}}
                                                                    <div class="flex flex-col gap-1.5 flex-1">
                                                                        {{-- Reg Policy --}}
                                                                        @if($regPolicy !== null)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help border" style="{{ $regPolicy ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                                                                                            {!! $regPolicy ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} NO REGISTRATION POLICY
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                {{ $regPolicy ? 'The platform does not require a registration to use the product and service' : 'Platform require registration, not good for privecy' }}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @endif

                                                                        {{-- Log Policy --}}
                                                                        @if($logPolicy !== null)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help border" style="{{ $logPolicy ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                                                                                            {!! $logPolicy ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} NO LOG POLICY
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                {{ $logPolicy ? 'The platform does not keep any user log/finger-print' : 'Platform keep log / finger-print of their users. not good for security and privecy' }}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @endif

                                                                        {{-- Own Liquidity --}}
                                                                        @if($ownLiquidity !== null)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help border" style="{{ $ownLiquidity ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                                                                                            {!! $ownLiquidity ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} HAVE OWN LIQUIDITY
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                {{ $ownLiquidityTooltipText }}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @endif

                                                                        {{-- Code Audited --}}
                                                                        @if($codeAudited !== null)
                                                                                                                         <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help border" style="{{ $codeAudited ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                                                                                            {!! $codeAudited ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} CODE AUDITED BY 3RD PARTY
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                {!! $codeAudited ? (strtolower($codeAuditedVal) !== 'yes' ? $codeAuditedVal : 'Audited') : 'The source code is not audited by a trusted third pary. You need good trust in the service.' !!}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @endif
                                                                    </div>

                                                                    {{-- Col 2 --}}
                                                                    <div class="flex flex-col gap-1.5 flex-1 items-end text-right">
                                                                        {{-- Tor --}}
                                                                        @if($hasTor)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help bg-[var(--card-tor-true-bg)] text-[var(--card-tor-true-text)] border border-[var(--card-tor-true-border)]">
                                                                                                                            HAVE TOR
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                {{ is_string($torUrl) ? $torUrl : 'TOR URL' }}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @else
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help bg-[var(--card-attr-bg)] text-[var(--card-tor-false-text)] border border-[var(--card-tor-false-border)]">
                                                                                                                            NO TOR
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                                            <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                No onion URL has recorded for this platform
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @endif
                                                                        
                                                                        {{-- Support Rating --}}
                                                                        @if($supportRating > 0)
                                                                            <div class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit bg-[var(--card-attr-bg)] text-[var(--card-attr-text)] border border-[var(--card-attr-border)]">
                                                                                Support Rating: <span class="{{ $ratingColor }} border-b {{ $ratingBorder }}">{{ $supportRating }}</span>/5
                                                                            </div>
                                                                        @endif

                                                                        {{-- Reputation Score --}}
                                                                        <div class="text-[9px] font-bold uppercase text-[var(--card-attr-text)] bg-[var(--card-attr-bg)] px-2 py-0.5 rounded border border-[var(--card-attr-border)]">
                                                                            REPUTATION SCORE: <span class="{{ $repColor }} border-b {{ $repBorder }}">{{ number_format($scaledRep, 2) }}</span>/10
                                                                        </div>

                                                                        {{-- Privacy Score --}}
                                                                        <div class="text-[9px] font-bold uppercase text-[var(--card-attr-text)] bg-[var(--card-attr-bg)] px-2 py-0.5 rounded border border-[var(--card-attr-border)]">
                                                                            PRIVACY SCORE: <span class="{{ $privColor }} border-b {{ $privBorder }}">{{ number_format($scaledPriv, 2) }}</span>/10
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Footer --}}
                                                                <div class="pt-2 border-t border-gray-800 flex items-center justify-between mt-auto mx-[-.5rem] px-4 pb-0">
                                                                    <div class="flex items-center gap-3">
                                                                        {{-- Feedback Counts --}}
                                                                        <div class="flex items-center gap-2 text-[10px] font-bold">
                                                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                                                <span class="text-[var(--review-pos)] font-mono tracking-tighter mr-0.5">+</span><span class="text-[var(--review-pos)]">{{ $p->positive_count ?? 0 }}</span>
                                                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>Positive Feedback</div>
                                                                            </div>
                                                                            <span class="text-gray-600">/</span>
                                                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                                                <span class="text-[var(--review-neu)] font-mono tracking-tighter mr-0.5">=</span><span class="text-[var(--review-neu)]">{{ $p->neutral_count ?? 0 }}</span>
                                                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>Neutral Feedback</div>
                                                                            </div>
                                                                            <span class="text-gray-600">/</span>
                                                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                                                <span class="text-[var(--review-neg)] font-mono tracking-tighter mr-0.5">-</span><span class="text-[var(--review-neg)]">{{ $p->negative_count ?? 0 }}</span>
                                                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>Negative Feedback</div>
                                                                            </div>
                                                                        </div>

                                                                        {{-- KYC Badge --}}
                                                                        @php
                                                                            $kycTooltip = match ((string) $kycNum) {
                                                                                '0' => 'Guaranteed no KYC. Report if ever asked for KYC',
                                                                                '1' => 'No clause about KYC in the terms. May or may not ask for KYC',
                                                                                '2' => 'Can ask for KYC when requested by authorities for investigation.',
                                                                                '3', '4' => 'Strict KYC policy requires to access features otherwise funds can be blocked',
                                                                                default => 'KYC Policy'
                                                                            };
                                                                        @endphp
                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center bg-[var(--card-kyc-bg)] text-[var(--card-kyc-text)] border border-[var(--card-kyc-border)] rounded relative cursor-help shadow-sm">
                                                                            <svg class="w-3.5 h-3.5 ml-1.5 text-[var(--review-pos)]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                                                            <div class="px-1 text-[10px] font-bold tracking-tight">KYC {{ $kycBadge }}</div>
                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white normal-case font-normal whitespace-nowrap">
                                <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>
                                                                                {{ $kycTooltip }}
                                                                            </div>
                                                                        </div>

                                                                        {{-- Community Badge --}}
                                                                        @if($communityText)
                                                                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 text-[9px] bg-[var(--card-comm-true-bg)] text-[var(--card-comm-true-text)] border border-[var(--card-comm-true-border)] font-bold uppercase rounded relative cursor-help">
                                                                                                                            COMMUNITY
                                                                                                                            <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl normal-case font-normal cursor-auto whitespace-nowrap" @mouseenter="tooltip = true">
                                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                                                              <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                                                              <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                                                            </svg>
                                                                                                                                <div class="flex flex-row items-center gap-3">
                                                                                                                                @php
                                                                                                                                    $links = array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $communityText))));
                                                                                                                                @endphp
                                                                                                                                @foreach($links as $link)
                                                                                                                                    @if(filter_var($link, FILTER_VALIDATE_URL))
                                                                                                                                        <a href="{{ $link }}" target="_blank" rel="nofollow" class="text-[10px] text-blue-400 hover:text-blue-300 truncate underline block p-0.5">
                                                                                                                                            {{ preg_replace('/^www\./', '', parse_url($link, PHP_URL_HOST)) ?: $link }}
                                                                                                                                        </a>
                                                                                                                                    @else
                                                                                                                                        <span class="text-[10px] text-gray-300 block p-0.5">{{ $link }}</span>
                                                                                                                                    @endif
                                                                                                                                @endforeach
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                        @else
                                                                            <div class="px-2 py-0.5 text-[9px] bg-[var(--card-comm-false-bg)] text-[var(--card-comm-false-text)] border border-[var(--card-comm-false-border)] font-bold uppercase rounded">
                                                                                COMMUNITY
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    <div class="flex items-center gap-2">
                                                                        @if($ageFormatted)
                                                                            <span class="text-[var(--card-age-text)] font-bold text-[10px] uppercase">{{ $ageFormatted }}</span>
                                                                        @endif

                                                                        {{-- Online --}}
                                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative cursor-help">
                                                                            <div class="w-2 h-2 rounded-full {{ $p->online_status === 'online' ? 'bg-[var(--card-online)] shadow-[0_0_5px_var(--card-online)]' : 'bg-[var(--card-offline)] shadow-[0_0_5px_var(--card-offline)]' }}"></div>
                                                                            <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded normal-case font-normal whitespace-nowrap">
                                <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none">
                                  <path d="M12 21l-12-18h24z" class="fill-gray-900"/>
                                  <path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/>
                                </svg>
                                                                                {{ ucfirst($p->online_status) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                            @else
                                {{-- LIST VIEW CARD --}}
                                <div wire:key="project-list-{{ $p->id }}" class="bg-[var(--card-bg)] rounded-xl border border-[var(--card-border)] hover:border-gray-600 transition duration-300 p-4 flex flex-col lg:flex-row gap-5 relative group items-stretch w-full">
                                    
                                    {{-- LEFT COLUMN: Logo & Info --}}
                                    <div class="flex-grow flex gap-4 items-start min-w-0">
                                        {{-- Logo --}}
                                        <div class="flex-shrink-0 flex flex-col items-center gap-2">
                                            <a href="{{ route('projects.show', $p) }}" class="block w-16 h-16 bg-gray-800 rounded-lg border border-gray-700 overflow-hidden flex items-center justify-center">
                                                @if($p->logo)
                                                    <img src="{{ Storage::url($p->logo) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-gray-500 text-xs font-bold">logo</span>
                                                @endif
                                            </a>
                                            
                                            @if($ageFormatted)
                                                <span class="text-[var(--card-age-text)] font-bold text-[9px] uppercase tracking-wider bg-gray-900 border border-gray-800 px-1.5 py-0.5 rounded">{{ $ageFormatted }}</span>
                                            @endif
                                        </div>

                                        {{-- Title & Details --}}
                                        <div class="flex-grow min-w-0 flex flex-col gap-1.5">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-[var(--card-cat-bg)] text-[var(--card-cat-text)] border border-[var(--card-cat-border)] whitespace-nowrap">
                                                    {{ $p->category->name }}
                                                </span>
                                                @if($hasRisk)
                                                    <div x-data="{ showRisk: false }" class="relative text-[9px] font-bold uppercase bg-[var(--card-risk-bg)] text-[var(--card-risk-text)] border border-red-800 cursor-pointer whitespace-nowrap rounded">
                                                        <button @click="showRisk = !showRisk" @click.away="showRisk = false" class="px-2 py-0.5 w-full h-full">
                                                            POTENTIAL RISK DETECTED
                                                        </button>
                                                        <div x-show="showRisk" style="display: none;" class="absolute z-50 top-[calc(100%+4px)] left-0 w-64 bg-gray-900 border border-gray-700 text-gray-200 text-xs p-3 rounded shadow-xl normal-case font-normal whitespace-normal">
                                                            {{ $riskMsg }}
                                                            <div class="absolute top-0 left-4 -mt-2 border-8 border-transparent border-b-gray-700"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($p->source_code_availability)
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-green-900/40 text-green-400 border border-green-800/50 whitespace-nowrap">
                                                        Open Source
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2.5">
                                                <h3 class="font-semibold text-lg text-[var(--card-title)] leading-tight">
                                                    <a href="{{ route('projects.show', $p) }}" class="hover:text-[var(--dir-accent)] transition">
                                                        {{ $p->name }}
                                                    </a>
                                                </h3>

                                                {{-- Status Icons --}}
                                                @if($p->list_status === 'verified')
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex items-center justify-center cursor-help">
                                                        <svg class="w-5 h-5 text-[var(--card-status-verified)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>WLC Verified
                                                        </div>
                                                    </div>
                                                @elseif($p->list_status === 'approved')
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex items-center justify-center cursor-help">
                                                        <svg class="w-5 h-5 text-[var(--card-status-approved)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>WLC Approved
                                                        </div>
                                                    </div>
                                                @elseif($p->list_status === 'pending')
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="w-6 h-6 bg-[var(--card-status-pending)] rounded flex items-center justify-center text-white relative cursor-help">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>Waiting for WLC approval
                                                        </div>
                                                    </div>
                                                @elseif($p->list_status === 'scam')
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="w-6 h-6 bg-[var(--card-status-scam)] rounded flex items-center justify-center text-white relative cursor-help">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>Warning! Scam Platform.
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Escrow Shield --}}
                                                @if($escrowAmount)
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative flex flex-col items-center justify-center cursor-help">
                                                        <div class="w-7 h-8 bg-[var(--card-escrow)] flex items-center justify-center text-white text-[9px] font-bold" style="clip-path: polygon(50% 0%, 100% 0, 100% 75%, 50% 100%, 0% 75%, 0 0);">
                                                            <span class="-mt-1">{{ $escrowAmount }}</span>
                                                        </div>
                                                        <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                                                            <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                            {{ $escrowTooltip }}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($p->ownership_verified)
                                                    <div class="inline-flex items-center gap-1.5 ml-2">
                                                        <svg class="w-3.5 h-3.5 text-[var(--card-status-approved)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        <span class="text-[9px] font-bold uppercase text-[var(--card-ownership)] tracking-wider">OWNERSHIP VERIFIED</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Description --}}
                                            <p class="text-[var(--card-text)] text-xs leading-relaxed line-clamp-2 h-[38px] overflow-hidden max-w-2xl">
                                                {{ $p->description }}
                                            </p>

                                            {{-- Row of Badges (Coins, LOG, Fee) --}}
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                @if(count($coins) > 0)
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold bg-[var(--card-coins-bg)] text-[var(--card-coins-text)] border border-[var(--card-coins-border)] flex items-center relative cursor-help">
                                                        {{ $coins[0] }} {{ count($coins) > 1 ? '+' . (count($coins) - 1) : '' }}
                                                        @if(count($coins) > 1)
                                                            <div x-show="tooltip" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white whitespace-nowrap">
                                                                <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                                {{ implode(', ', array_slice($coins, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($logVerifiable !== null)
                                                    @if($logVerifiable)
                                                        <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold bg-[var(--card-log-true-bg)] text-[var(--card-log-true-text)] border border-[var(--card-log-true-border)] relative cursor-help">
                                                            LOG VERIFIABLE
                                                            <div x-show="tooltip" style="display: none;" class="absolute bottom-full left-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-3 rounded-lg shadow-xl text-xs text-white font-normal w-72 whitespace-normal flex flex-col gap-2" @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                                                <svg class="absolute top-full left-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                                <div class="font-bold border-b border-gray-800 pb-1 text-green-400">LOG VERIFIABLE DETAILS</div>
                                                                @if($p->bitcoin_address)
                                                                    <div class="flex flex-col gap-0.5">
                                                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Bitcoin Address</span>
                                                                        <div class="font-mono bg-gray-950 p-1.5 rounded border border-gray-800 break-all select-all text-[11px] text-gray-200">{{ $p->bitcoin_address }}</div>
                                                                    </div>
                                                                @else
                                                                    <div class="text-[10px] text-gray-500 italic">No Bitcoin Address provided.</div>
                                                                @endif
                                                                @if($p->pgp_public_key)
                                                                    <div class="mt-1">
                                                                        <a href="data:text/plain;charset=utf-8,{{ rawurlencode($p->pgp_public_key) }}" download="{{ \Illuminate\Support\Str::slug($p->name) }}-pgp-key.txt" class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-600 hover:bg-green-500 text-white font-bold rounded text-[10px] uppercase transition shadow-md w-full justify-center">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>Download PGP Key
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="text-[10px] text-gray-500 italic">No PGP Public Key provided.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="px-2 py-0.5 text-[9px] bg-[var(--card-log-false-bg)] text-[var(--card-log-false-text)] border border-[var(--card-log-false-border)] font-bold uppercase rounded">LOG VERIFIABLE</div>
                                                    @endif
                                                @endif

                                                @if($feeString)
                                                    <div class="px-2 py-0.5 rounded text-[9px] font-bold bg-[var(--card-fee-bg)] text-[var(--card-fee-text)] border border-[var(--card-fee-border)] leading-tight flex items-center">
                                                        {{ $feeString }}
                                                    </div>
                                                @endif

                                                {{-- KYC Badge --}}
                                                <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center bg-[var(--card-kyc-bg)] text-[var(--card-kyc-text)] border border-[var(--card-kyc-border)] rounded relative cursor-help shadow-sm">
                                                    <svg class="w-3.5 h-3.5 ml-1.5 text-[var(--review-pos)]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                                    <div class="px-1 text-[9px] font-bold tracking-tight">KYC {{ $kycBadge }}</div>
                                                    <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white normal-case font-normal whitespace-nowrap">
                                                        <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                        {{ $kycTooltip }}
                                                    </div>
                                                </div>

                                                {{-- Tor Badge --}}
                                                @if($hasTor)
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help bg-[var(--card-tor-true-bg)] text-[var(--card-tor-true-text)] border border-[var(--card-tor-true-border)]">
                                                        HAVE TOR
                                                        <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                            <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                            {{ is_string($torUrl) ? $torUrl : 'TOR URL' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase w-fit relative cursor-help bg-[var(--card-attr-bg)] text-[var(--card-tor-false-text)] border border-[var(--card-tor-false-border)]">
                                                        NO TOR
                                                        <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                            <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                            No onion URL has recorded for this platform
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MIDDLE COLUMN: Dynamic Policies Checklist --}}
                                    <div class="w-full lg:w-60 flex flex-col gap-1.5 justify-center border-t lg:border-t-0 lg:border-l lg:border-r border-gray-800/80 pt-4 lg:pt-0 px-0 lg:px-4">
                                        {{-- Reg Policy --}}
                                        @if($regPolicy !== null)
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-1 rounded text-[9px] font-bold uppercase w-full relative cursor-help border" style="{{ $regPolicy ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                {!! $regPolicy ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} NO REGISTRATION POLICY
                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {{ $regPolicy ? 'The platform does not require a registration to use the product and service' : 'Platform require registration, not good for privacy' }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Log Policy --}}
                                        @if($logPolicy !== null)
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-1 rounded text-[9px] font-bold uppercase w-full relative cursor-help border" style="{{ $logPolicy ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                {!! $logPolicy ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} NO LOG POLICY
                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {{ $logPolicy ? 'The platform does not keep any user log/finger-print' : 'Platform keep log / finger-print of their users. not good for security and privacy' }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Own Liquidity --}}
                                        @if($ownLiquidity !== null)
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-1 rounded text-[9px] font-bold uppercase w-full relative cursor-help border" style="{{ $ownLiquidity ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                {!! $ownLiquidity ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} HAVE OWN LIQUIDITY
                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {{ $ownLiquidityTooltipText }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Code Audited --}}
                                        @if($codeAudited !== null)
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-1 rounded text-[9px] font-bold uppercase w-full relative cursor-help border" style="{{ $codeAudited ? 'background: var(--card-policy-true-bg); color: var(--card-policy-true-text); border-color: var(--card-policy-true-border);' : 'background: var(--card-policy-false-bg); color: var(--card-policy-false-text); border-color: var(--card-policy-false-border);' }}">
                                                {!! $codeAudited ? '<span style="color: var(--card-policy-check-true)">&#10004;&#xFE0E;</span>' : '<span style="color: var(--card-policy-check-false)">✕</span>' !!} CODE AUDITED BY 3RD PARTY
                                                <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl text-xs text-white font-normal normal-case whitespace-nowrap">
                                                    <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {!! $codeAudited ? (strtolower($codeAuditedVal) !== 'yes' ? $codeAuditedVal : 'Audited') : 'The source code is not audited by a trusted third party.' !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- RIGHT COLUMN: Scores, Badges & Online Dot --}}
                                    <div class="w-full lg:w-64 flex flex-row lg:flex-col justify-between items-center lg:items-end gap-3 flex-shrink-0">
                                        {{-- Score Box & Online status row --}}
                                        <div class="flex items-center gap-3 w-full justify-between lg:justify-end">
                                            {{-- Online dot --}}
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="relative cursor-help order-last lg:order-first">
                                                <div class="w-2.5 h-2.5 rounded-full {{ $p->online_status === 'online' ? 'bg-[var(--card-online)] shadow-[0_0_6px_var(--card-online)]' : 'bg-[var(--card-offline)] shadow-[0_0_6px_var(--card-offline)]' }}"></div>
                                                <div x-show="tooltip" class="absolute bottom-full right-0 mb-2 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded normal-case font-normal whitespace-nowrap">
                                                    <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {{ ucfirst($p->online_status) }}
                                                </div>
                                            </div>

                                            {{-- WLC Score Box --}}
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center justify-center px-2 py-1 rounded shadow-sm relative cursor-help w-fit {{ $wlcBgColor }}">
                                                <div class="text-[13px] font-black tracking-tight {{ $wlcTextColor }}">{{ number_format($scaledWLC, 2) }}</div>
                                                <div x-show="tooltip" class="absolute bottom-[calc(100%+4px)] right-0 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded shadow-lg whitespace-nowrap font-normal">
                                                    <svg class="absolute top-full right-4 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                    {{ $wlcLabel }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sub Scores Grid --}}
                                        <div class="flex flex-col gap-1 w-full lg:w-auto items-start lg:items-end">
                                            @if($supportRating > 0)
                                                <div class="px-2 py-0.5 rounded text-[8px] font-bold uppercase w-fit bg-[var(--card-attr-bg)] text-[var(--card-attr-text)] border border-[var(--card-attr-border)]">
                                                    Support Rating: <span class="{{ $ratingColor }} border-b {{ $ratingBorder }}">{{ $supportRating }}</span>/5
                                                </div>
                                            @endif
                                            <div class="text-[8px] font-bold uppercase text-[var(--card-attr-text)] bg-[var(--card-attr-bg)] px-2 py-0.5 rounded border border-[var(--card-attr-border)]">
                                                REPUTATION SCORE: <span class="{{ $repColor }} border-b {{ $repBorder }}">{{ number_format($scaledRep, 2) }}</span>/10
                                            </div>
                                            <div class="text-[8px] font-bold uppercase text-[var(--card-attr-text)] bg-[var(--card-attr-bg)] px-2 py-0.5 rounded border border-[var(--card-attr-border)]">
                                                PRIVACY SCORE: <span class="{{ $privColor }} border-b {{ $privBorder }}">{{ number_format($scaledPriv, 2) }}</span>/10
                                            </div>
                                        </div>

                                        {{-- Feedback & Community Row --}}
                                        <div class="flex flex-wrap lg:flex-nowrap items-center gap-2 justify-end mt-auto">
                                            {{-- Feedback Counts --}}
                                            <div class="flex items-center gap-1.5 text-[9px] font-bold bg-gray-900/60 px-2 py-0.5 rounded border border-gray-800">
                                                <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                    <span class="text-[var(--review-pos)] font-mono tracking-tighter mr-0.5">+</span><span class="text-[var(--review-pos)]">{{ $p->positive_count ?? 0 }}</span>
                                                    <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                                        <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>Positive Feedback
                                                    </div>
                                                </div>
                                                <span class="text-gray-600">/</span>
                                                <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                    <span class="text-[var(--review-neu)] font-mono tracking-tighter mr-0.5">=</span><span class="text-[var(--review-neu)]">{{ $p->neutral_count ?? 0 }}</span>
                                                    <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                                        <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>Neutral Feedback
                                                    </div>
                                                </div>
                                                <span class="text-gray-600">/</span>
                                                <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="flex items-center relative cursor-help">
                                                    <span class="text-[var(--review-neg)] font-mono tracking-tighter mr-0.5">-</span><span class="text-[var(--review-neg)]">{{ $p->negative_count ?? 0 }}</span>
                                                    <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 z-50 bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap normal-case font-normal">
                                                        <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>Negative Feedback
                                                    </div>
                                                </div>
                                            </div>



                                            {{-- Community Badge --}}
                                            @if($communityText)
                                                <div x-data="{ tooltip: false }" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="px-2 py-0.5 text-[9px] bg-[var(--card-comm-true-bg)] text-[var(--card-comm-true-text)] border border-[var(--card-comm-true-border)] font-bold uppercase rounded relative cursor-help">
                                                    COMMUNITY
                                                    <div x-show="tooltip" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 bg-gray-900 border border-gray-700 p-2 rounded shadow-xl normal-case font-normal cursor-auto whitespace-nowrap" @mouseenter="tooltip = true">
                                                        <svg class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 21l-12-18h24z" class="fill-gray-900"/><path d="M12 21l-12-18h24z" stroke="rgba(55, 65, 81, 1)" stroke-width="1"/></svg>
                                                        <div class="flex flex-row items-center gap-3">
                                                            @php $links = array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $communityText)))); @endphp
                                                            @foreach($links as $link)
                                                                @if(filter_var($link, FILTER_VALIDATE_URL))
                                                                    <a href="{{ $link }}" target="_blank" rel="nofollow" class="text-[10px] text-blue-400 hover:text-blue-300 truncate underline block p-0.5">
                                                                        {{ preg_replace('/^www\./', '', parse_url($link, PHP_URL_HOST)) ?: $link }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-[10px] text-gray-300 block p-0.5">{{ $link }}</span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="px-2 py-0.5 text-[9px] bg-[var(--card-comm-false-bg)] text-[var(--card-comm-false-text)] border border-[var(--card-comm-false-border)] font-bold uppercase rounded">
                                                    COMMUNITY
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                    </div>

                    {{-- Infinite Scroll Sentinel --}}
                    @if($projects->hasMorePages())
                        <div x-data x-intersect.margin.500px="$wire.loadMore()" class="flex justify-center py-8">
                            <div wire:loading wire:target="loadMore" class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 border-4 border-[var(--dir-accent)] border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-xs text-gray-500 font-medium tracking-widest uppercase">Loading more...</span>
                            </div>
                            <div wire:loading.remove wire:target="loadMore" class="text-xs text-gray-600 uppercase tracking-widest">
                                Scroll for more
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center py-12">
                            <div class="text-xs text-gray-600 uppercase tracking-widest border-t border-gray-800/50 pt-4 px-8">
                                No more projects to show
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>
