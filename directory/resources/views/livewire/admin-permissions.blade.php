<div>
    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
        This project uses a <span class="font-semibold">role-based</span> permission model (stored in <code class="rounded bg-amber-100 px-1 py-0.5">users.role</code>).
        The admin area is protected by <code class="rounded bg-amber-100 px-1 py-0.5">AdminMiddleware</code>, which currently allows <span class="font-semibold">moderator</span> and <span class="font-semibold">admin</span>.
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-5">
            <div class="text-sm font-semibold text-gray-900">user</div>
            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                <li>- Browse directory</li>
                <li>- Submit project</li>
            </ul>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-5">
            <div class="text-sm font-semibold text-gray-900">moderator</div>
            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                <li>- Everything in user</li>
                <li>- Access admin dashboard</li>
                <li>- Approve / verify / delete projects</li>
            </ul>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-5">
            <div class="text-sm font-semibold text-gray-900">admin</div>
            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                <li>- Everything in moderator</li>
                <li>- Manage user roles (User settings)</li>
            </ul>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-gray-200 bg-white p-5">
        <div class="text-sm font-semibold text-gray-900">Notes</div>
        <ul class="mt-2 space-y-1 text-sm text-gray-600">
            <li>- If you want stricter control (e.g. only admins can access /admin), update <code class="rounded bg-gray-100 px-1 py-0.5">User::isModerator()</code> or <code class="rounded bg-gray-100 px-1 py-0.5">AdminMiddleware</code>.</li>
            <li>- If you later want per-feature permissions (not just roles), we can add a dedicated permissions system.</li>
        </ul>
    </div>
</div>

