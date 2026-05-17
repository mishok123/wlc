<div class="px-4 sm:px-6 lg:px-8">
    <style>
        .reports-stats-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media (min-width: 640px) {
            .reports-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .reports-stats-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
    </style>

    {{-- Alerts --}}
    @if (session()->has('message'))
        <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 shadow-sm animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-green-800 uppercase tracking-wide">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="sm:flex sm:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Project Reports</h1>
            <p class="mt-2 text-sm text-gray-600">Investigate and moderate complaints submitted by users regarding catalog projects.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="reports-stats-grid">
        {{-- Total --}}
        <div class="bg-white rounded-xl border border-gray-150 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-gray-400 text-[9px] font-black uppercase tracking-wider">Total Reports</span>
                    <span class="block text-xl font-black text-gray-900 mt-0.5">{{ $stats['total'] }}</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl border border-gray-150 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-gray-400 text-[9px] font-black uppercase tracking-wider">Pending Action</span>
                    <span class="block text-xl font-black text-yellow-600 mt-0.5">{{ $stats['pending'] }}</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 border border-yellow-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Resolved --}}
        <div class="bg-white rounded-xl border border-gray-150 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-gray-400 text-[9px] font-black uppercase tracking-wider">Resolved</span>
                    <span class="block text-xl font-black text-green-600 mt-0.5">{{ $stats['resolved'] }}</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 border border-green-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Dismissed --}}
        <div class="bg-white rounded-xl border border-gray-150 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-gray-400 text-[9px] font-black uppercase tracking-wider">Dismissed</span>
                    <span class="block text-xl font-black text-gray-500 mt-0.5">{{ $stats['dismissed'] }}</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search Header --}}
    <div class="bg-white border border-gray-150 rounded-2xl p-3 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Status Filter Tabs --}}
        <div class="flex flex-wrap gap-1 bg-gray-50 p-1 rounded-xl border border-gray-100 w-fit">
            <button wire:click="$set('statusFilter', '')" class="px-2.5 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all {{ $statusFilter === '' ? 'bg-white text-gray-900 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-900' }}">
                All ({{ $stats['total'] }})
            </button>
            <button wire:click="$set('statusFilter', 'pending')" class="px-2.5 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all {{ $statusFilter === 'pending' ? 'bg-yellow-500 text-white shadow-md' : 'text-gray-500 hover:text-gray-900' }}">
                Pending ({{ $stats['pending'] }})
            </button>
            <button wire:click="$set('statusFilter', 'resolved')" class="px-2.5 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all {{ $statusFilter === 'resolved' ? 'bg-green-600 text-white shadow-md' : 'text-gray-500 hover:text-gray-900' }}">
                Resolved ({{ $stats['resolved'] }})
            </button>
            <button wire:click="$set('statusFilter', 'dismissed')" class="px-2.5 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all {{ $statusFilter === 'dismissed' ? 'bg-gray-500 text-white shadow-md' : 'text-gray-500 hover:text-gray-900' }}">
                Dismissed ({{ $stats['dismissed'] }})
            </button>
        </div>

        {{-- Search Input --}}
        <div class="relative rounded-xl shadow-sm max-w-sm w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" id="search" class="block w-full rounded-xl border border-gray-200 bg-gray-50/50 pl-10 pr-4 py-2.5 text-xs text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm placeholder-gray-400" placeholder="Search by project name or content...">
        </div>
    </div>

    {{-- Main Content Table --}}
    <div class="flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 md:rounded-2xl border border-gray-150 bg-white">
                    <table class="min-w-full divide-y divide-gray-150">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-4 pl-4 pr-3 text-left text-[10px] font-black uppercase tracking-wider text-gray-500 sm:pl-6">Project</th>
                                <th scope="col" class="px-3 py-4 text-left text-[10px] font-black uppercase tracking-wider text-gray-500">Reported By / IP</th>
                                <th scope="col" class="px-3 py-4 text-left text-[10px] font-black uppercase tracking-wider text-gray-500">Reason / Description</th>
                                <th scope="col" class="px-3 py-4 text-left text-[10px] font-black uppercase tracking-wider text-gray-500">Date Reported</th>
                                <th scope="col" class="px-3 py-4 text-left text-[10px] font-black uppercase tracking-wider text-gray-500">Status</th>
                                <th scope="col" class="relative py-4 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 bg-white">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        @if($report->project)
                                            <div class="flex items-center gap-3">
                                                @if($report->project->logo)
                                                    <img class="h-9 w-9 rounded-xl object-cover border border-gray-150 shadow-sm" src="{{ asset('storage/' . $report->project->logo) }}" alt="{{ $report->project->name }}">
                                                @else
                                                    <div class="h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                                                        {{ substr($report->project->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('projects.show', $report->project->slug) }}" target="_blank" class="font-bold text-gray-900 hover:text-indigo-600 flex items-center gap-1">
                                                        {{ $report->project->name }}
                                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">{{ $report->project->category->name ?? 'Uncategorized' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-red-500 font-bold italic">Deleted Project</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">
                                        <div class="font-semibold text-gray-800">{{ $report->user->name ?? 'Guest User' }}</div>
                                        @if($report->user)
                                            <div class="text-[10px] text-gray-400">{{ $report->user->email }}</div>
                                        @endif
                                        <div class="font-mono text-[10px] text-gray-400 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                            {{ $report->ip_address ?? 'Unknown IP' }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-xs text-gray-600 max-w-sm">
                                        <p class="line-clamp-2 leading-relaxed">{{ $report->description }}</p>
                                        <button wire:click="viewReport({{ $report->id }})" class="text-indigo-600 hover:text-indigo-800 font-bold uppercase tracking-wider text-[10px] mt-1.5 hover:underline cursor-pointer">Read Full Description</button>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">
                                        <div class="font-semibold text-gray-800">{{ $report->created_at->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $report->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border
                                            @if($report->status === 'pending') bg-yellow-50 text-yellow-800 border-yellow-200
                                            @elseif($report->status === 'resolved') bg-green-50 text-green-800 border-green-200
                                            @elseif($report->status === 'dismissed') bg-gray-50 text-gray-600 border-gray-200
                                            @else bg-gray-50 text-gray-600 border-gray-200 @endif">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-xs font-bold sm:pr-6">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($report->status === 'pending')
                                                <button wire:click="changeStatus({{ $report->id }}, 'resolved')" class="px-2.5 py-1 bg-green-600 hover:bg-green-500 text-white rounded-lg transition shadow-sm text-[10px] uppercase cursor-pointer">
                                                    Resolve
                                                </button>
                                                <button wire:click="changeStatus({{ $report->id }}, 'dismissed')" class="px-2.5 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition shadow-sm text-[10px] uppercase cursor-pointer">
                                                    Dismiss
                                                </button>
                                            @else
                                                <button wire:click="changeStatus({{ $report->id }}, 'pending')" class="px-2.5 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-800 border border-yellow-200 rounded-lg transition text-[10px] uppercase cursor-pointer">
                                                    Re-open
                                                </button>
                                            @endif
                                            <button wire:click="deleteReport({{ $report->id }})" wire:confirm="Are you absolutely sure you want to delete this report permanently?" class="p-1 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-lg transition cursor-pointer" title="Delete Report">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 border-none">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="font-bold text-gray-700">No Reports Found</p>
                                            <p class="text-xs text-gray-400 mt-1 max-w-xs leading-relaxed">No project complaints match the active filter or search query.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-5">
        {{ $reports->links() }}
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedReport)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" role="dialog" aria-modal="true" style="isolation: isolate;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-black/60 transition-opacity animate-fade-in" wire:click="closeDetailModal"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg z-10 border border-gray-100 overflow-hidden transform transition-all animate-scale-up">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border mb-1.5
                            @if($selectedReport['status'] === 'pending') bg-yellow-50 text-yellow-800 border-yellow-200
                            @elseif($selectedReport['status'] === 'resolved') bg-green-50 text-green-800 border-green-200
                            @elseif($selectedReport['status'] === 'dismissed') bg-gray-50 text-gray-600 border-gray-200
                            @else bg-gray-50 text-gray-600 border-gray-200 @endif">
                            {{ $selectedReport['status'] }}
                        </span>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Report #{{ $selectedReport['id'] }} Detail</h3>
                    </div>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-xl hover:bg-gray-150 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-6 space-y-5">
                    {{-- Project info --}}
                    @if($selectedReport['project'])
                        <div class="flex items-center gap-3.5 bg-indigo-50/30 p-3.5 rounded-2xl border border-indigo-100/50">
                            @if($selectedReport['project']['logo'])
                                <img class="h-11 w-11 rounded-2xl object-cover border border-gray-200 shadow-sm" src="{{ asset('storage/' . $selectedReport['project']['logo']) }}" alt="{{ $selectedReport['project']['name'] }}">
                            @else
                                <div class="h-11 w-11 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-base border border-indigo-200 shadow-sm">
                                    {{ substr($selectedReport['project']['name'], 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-black text-gray-900 text-sm leading-tight">{{ $selectedReport['project']['name'] }}</h4>
                                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wide mt-0.5 block">Catalog Project</span>
                            </div>
                            <a href="{{ route('projects.show', $selectedReport['project']['slug']) }}" target="_blank" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase transition flex items-center gap-1 cursor-pointer">
                                View
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    @endif

                    {{-- User/Reporter info --}}
                    <div class="grid grid-cols-2 gap-4 text-xs border-b border-gray-100 pb-4">
                        <div>
                            <span class="block text-gray-400 font-bold uppercase tracking-wider text-[9px] mb-0.5">Reporter</span>
                            <span class="font-bold text-gray-800 text-sm leading-tight">{{ $selectedReport['user']['name'] ?? 'Guest User' }}</span>
                            @if($selectedReport['user'])
                                <span class="block text-[10px] text-gray-400 mt-0.5">{{ $selectedReport['user']['email'] }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-gray-400 font-bold uppercase tracking-wider text-[9px] mb-0.5">IP / Timestamp</span>
                            <span class="font-mono text-gray-800 text-[11px] block">{{ $selectedReport['ip_address'] ?? 'N/A' }}</span>
                            <span class="block text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($selectedReport['created_at'])->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <span class="block text-gray-400 font-bold uppercase tracking-wider text-[9px] mb-2">Complaint Description</span>
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-150 text-gray-700 text-xs leading-relaxed max-h-48 overflow-y-auto whitespace-pre-line font-medium">
                            {{ $selectedReport['description'] }}
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-wrap gap-2">
                        @if($selectedReport['status'] === 'pending')
                            <button wire:click="changeStatus({{ $selectedReport['id'] }}, 'resolved')" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-xl text-xs font-bold uppercase transition shadow-sm cursor-pointer">
                                Mark Resolved
                            </button>
                            <button wire:click="changeStatus({{ $selectedReport['id'] }}, 'dismissed')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold uppercase transition cursor-pointer">
                                Dismiss
                            </button>
                        @else
                            <button wire:click="changeStatus({{ $selectedReport['id'] }}, 'pending')" class="px-4 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-800 border border-yellow-200 rounded-xl text-xs font-bold uppercase transition cursor-pointer">
                                Re-open Report
                            </button>
                        @endif
                        <button wire:click="deleteReport({{ $selectedReport['id'] }})" wire:confirm="Are you absolutely sure you want to delete this report permanently?" class="p-2 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-xl transition cursor-pointer" title="Delete Report">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <button wire:click="closeDetailModal" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-bold uppercase transition cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
