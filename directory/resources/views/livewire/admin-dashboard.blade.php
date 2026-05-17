<div>
    @if(session()->has('message'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <div id="pending-approvals" class="mb-12 scroll-mt-24">
        <h2 class="text-2xl font-bold mb-4 text-orange-600">Pending Approvals</h2>
        @if($pendingProjects->isEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-gray-500">
                No pending projects found.
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingProjects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $project->name }}</div>
                                    <a href="{{ $project->website_url }}" target="_blank" class="text-sm text-blue-500 hover:underline">{{ $project->website_url }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $project->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $project->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="approveProject({{ $project->id }})" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                    <button wire:click="verifyProject({{ $project->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Verify</button>
                                    <button wire:click="deleteProject({{ $project->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="all-projects" class="scroll-mt-24">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">All Projects</h2>
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WLC Score</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allProjects as $project)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $project->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $project->list_status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project->list_status === 'scam' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $project->list_status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($project->list_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $project->trust_score >= 5 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($project->trust_score, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="markAsScam({{ $project->id }})" class="text-red-600 hover:text-red-900 mr-3">Mark Scam</button>
                                <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:text-gray-900">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $allProjects->links() }}
            </div>
        </div>
    </div>
</div>