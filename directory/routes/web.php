<?php

use App\Services\SmfAuthService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Livewire\ProjectList;
use App\Livewire\ProjectDetail;
use App\Livewire\SubmitProject;
use App\Livewire\AdminDashboard;
use App\Livewire\AdminUsers;
use App\Livewire\AdminPermissions;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('home');
})->name('home');

// Route::get('/test-list', ProjectList::class);
Route::get('/projects/{project:slug}', ProjectDetail::class)->name('projects.show');
Route::get('/page/{slug}', App\Livewire\PageShow::class)->name('pages.show');


Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/submit', SubmitProject::class)->name('projects.submit');

    Route::post('/logout', function (Request $request, SmfAuthService $smf) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $details = $smf->getSmfCookieDetails();

        $response = redirect()->route('home');

        if (!empty($details['name'])) {
            $path = $details['path'] ?? '/';
            $domain = $details['domain'] ?? null;

            // Clear SMF cookie (so SmfAuthMiddleware won't auto-log the user back in).
            $response->withCookie(Cookie::forget($details['name'], $path, $domain));

            // Also try common path fallback in case SMF used root path.
            if ($path !== '/') {
                $response->withCookie(Cookie::forget($details['name'], '/', $domain));
            }
        }

        return $response;
    })->name('logout');
});

// Auth Routes for redirect purposes
Route::get('/login', function () {
    return redirect(rtrim((string) config('smf.url', 'http://localhost/wlc/forum'), '/') . '/index.php?action=login');
})->name('login');

Route::get('/register', function () {
    return redirect(rtrim((string) config('smf.url', 'http://localhost/wlc/forum'), '/') . '/index.php?action=register');
})->name('register');

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/users', AdminUsers::class)->name('admin.users');
    Route::get('/admin/permissions', AdminPermissions::class)->name('admin.permissions');
    Route::get('/admin/fields', App\Livewire\AdminFields::class)->name('admin.fields');
    Route::get('/admin/reputation-privacy', App\Livewire\AdminReputationPrivacy::class)->name('admin.reputation-privacy');
    Route::get('/admin/categories', App\Livewire\AdminCategories::class)->name('admin.categories');
    Route::get('/admin/pages', App\Livewire\AdminPages::class)->name('admin.pages');
    Route::get('/admin/pages/create', App\Livewire\Admin\EditPage::class)->name('admin.pages.create');
    Route::get('/admin/pages/{page}/edit', App\Livewire\Admin\EditPage::class)->name('admin.pages.edit');
    Route::get('/admin/settings', App\Livewire\Admin\Settings::class)->name('admin.settings');
    Route::get('/admin/projects', App\Livewire\Admin\Projects::class)->name('admin.projects');
    Route::get('/admin/projects/{project}/edit', App\Livewire\Admin\EditProject::class)->name('admin.projects.edit');
    Route::get('/admin/reports', App\Livewire\Admin\Reports::class)->name('admin.reports');
    Route::get('/admin/clear-cache', function() {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return back()->with('message', 'All cache cleared successfully!');
    })->name('admin.clear-cache');
});
