
<div>
    @if(session()->has('message'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">All users</div>
            <div class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['all'] }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admins</div>
            <div class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['admin'] }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Moderators</div>
            <div class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['moderator'] }}</div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Users</div>
            <div class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['user'] }}</div>
        </div>
    </div>

    <div class="mb-4 grid gap-3 md:grid-cols-3">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Search</label>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search name or email..."
                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select
                wire:model.live="role"
                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">All roles</option>
                <option value="admin">admin</option>
                <option value="moderator">moderator</option>
                <option value="user">user</option>
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $user->id }} @if($user->smf_id) • SMF: {{ $user->smf_id }} @endif</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(Auth::user()->isAdmin())
                                <select
                                    class="w-40 rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    wire:key="role-{{ $user->id }}"
                                    wire:change="updateUserRole({{ $user->id }}, $event.target.value)"
                                >
                                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                                    <option value="moderator" @selected($user->role === 'moderator')>moderator</option>
                                    <option value="user" @selected($user->role === 'user')>user</option>
                                </select>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ $user->role }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(Auth::user()->isAdmin())
                                <div class="flex flex-col items-end gap-2">
                                    <span class="text-xs text-gray-500">Changing role saves automatically</span>
                                    <button
                                        type="button"
                                        wire:click="openPasswordModal({{ $user->id }})"
                                        class="inline-flex items-center rounded-md border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-800 shadow-sm hover:bg-gray-50"
                                    >
                                        Change password
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-gray-500">Admins can edit roles</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>

    @if($showPasswordModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/40" wire:click="closePasswordModal" aria-hidden="true"></div>

            <div class="relative z-10 w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Change password</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            User: <span class="font-semibold text-gray-900">{{ $passwordUserName }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-700">{{ $passwordUserEmail }}</span>
                        </p>
                        @if($passwordUserHasSmf)
                            <p class="mt-1 text-xs text-amber-700">
                                Note: This updates the <span class="font-semibold">directory</span> password only (it does not change the forum/SMF password).
                            </p>
                        @endif
                    </div>
                    <button
                        type="button"
                        wire:click="closePasswordModal"
                        class="inline-flex items-center justify-center rounded-md border border-gray-200 bg-white p-2 text-gray-700 shadow-sm hover:bg-gray-50"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="mt-5 grid gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New password</label>
                        <input
                            type="password"
                            wire:model.defer="newPassword"
                            class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Minimum 8 characters"
                            autocomplete="new-password"
                        />
                        @error('newPassword')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm password</label>
                        <input
                            type="password"
                            wire:model.defer="newPassword_confirmation"
                            class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            autocomplete="new-password"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closePasswordModal"
                        class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="updateUserPassword"
                        wire:loading.attr="disabled"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                    >
                        Update password
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

