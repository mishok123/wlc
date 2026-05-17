<div class="px-4 sm:px-6 lg:px-8">
    @if (session()->has('message'))
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200 shadow-sm animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 uppercase tracking-wide font-bold">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Projects</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all projects in the directory including their name, category, and status.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex gap-3">
            <button wire:click="recalculateAllScores" wire:loading.attr="disabled" class="inline-flex items-center justify-center rounded-lg border border-transparent bg-indigo-50 px-4 py-2 text-sm font-bold text-indigo-700 shadow-sm hover:bg-indigo-100 border border-indigo-200 transition-all duration-200 disabled:opacity-50">
                <span wire:loading.remove wire:target="recalculateAllScores" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh All Scores
                </span>
                <span wire:loading wire:target="recalculateAllScores" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Recalculating...
                </span>
            </button>

            <button wire:click="runHealthCheck" wire:loading.attr="disabled" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto transition-all duration-200 disabled:opacity-50">
                <span wire:loading.remove wire:target="runHealthCheck" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Run Health Check
                </span>
                <span wire:loading wire:target="runHealthCheck" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Checking URLs...
                </span>
            </button>
        </div>
    </div>

    <!-- Search -->
    <div class="mt-4">
        <label for="search" class="sr-only">Search</label>
        <div class="relative rounded-md shadow-sm max-w-sm">
            <input type="text" wire:model.live.debounce.300ms="search" id="search" class="block w-full rounded-lg border border-gray-300 bg-gray-50 pl-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150 ease-in-out" placeholder="Search projects...">
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Logo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Category</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Requests</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Terms</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">WLC Score</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Reports</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($projects as $project)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        @if($project->logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $project->logo) }}" alt="{{ $project->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $project->name }}</div>
                                        <div class="text-gray-500">Submitted by {{ $project->user->name ?? 'Unknown' }}</div>
                                        @if($project->verification_code)
                                            <div class="mt-1 flex items-center gap-1 text-xs">
                                                <span class="font-medium text-gray-600">Verification Code:</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200 tracking-widest">{{ $project->verification_code }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $project->category->name ?? 'Uncategorized' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                            @if($project->list_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($project->list_status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($project->list_status === 'verified') bg-green-100 text-green-800
                                            @elseif($project->list_status === 'scam') bg-red-100 text-red-800
                                            @elseif($project->list_status === 'rejected') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($project->list_status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($project->approve_requests_count > 0 || $project->verify_requests_count > 0)
                                            <div class="flex flex-col gap-1 items-start">
                                                @if($project->approve_requests_count > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        Approve: {{ $project->approve_requests_count }}
                                                    </span>
                                                @endif
                                                @if($project->verify_requests_count > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-200">
                                                        Verify: {{ $project->verify_requests_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($project->terms_url)
                                            <a href="{{ $project->terms_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded text-xs font-medium border border-indigo-100">View Terms</a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ number_format($project->trust_score, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($project->reports_count > 0)
                                            <button wire:click="viewReports({{ $project->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-100 text-red-800 border border-red-200 hover:bg-red-200 transition-colors cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ $project->reports_count }} Report{{ $project->reports_count > 1 ? 's' : '' }}
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, {{ $project->name }}</span></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-3 py-4 text-sm text-gray-500 text-center">
                                        No projects found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $projects->links() }}
    </div>

    {{-- Reports Modal --}}
    @if($showReportsModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" role="dialog" aria-modal="true" style="isolation: isolate;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-black/60 transition-opacity" wire:click="closeReportsModal"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 border border-gray-100">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Reports</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $selectedProjectName }} &mdash; {{ count($selectedProjectReports) }} report(s)</p>
                    </div>
                    <button wire:click="closeReportsModal" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto divide-y divide-gray-100">
                    @forelse($selectedProjectReports as $report)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ ($report['status'] ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                            {{ ucfirst($report['status'] ?? 'pending') }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($report['created_at'])->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $report['description'] }}</p>
                                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $report['user']['name'] ?? 'Guest' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                            {{ $report['ip_address'] ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-sm">No reports found.</div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button wire:click="closeReportsModal" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
