<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    public string $search = '';
    public string $role = '';

    public bool $showPasswordModal = false;
    public ?int $passwordUserId = null;
    public string $passwordUserName = '';
    public string $passwordUserEmail = '';
    public bool $passwordUserHasSmf = false;
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    protected $queryString = ['search', 'role'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updateUserRole(int $userId, string $role): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Only admins can change user roles.');
        }

        $role = strtolower(trim($role));

        if (!in_array($role, ['user', 'moderator', 'admin'], true)) {
            session()->flash('message', 'Invalid role selected.');
            return;
        }

        $user = User::findOrFail($userId);

        // Prevent removing your own access by mistake.
        if (Auth::check() && Auth::id() === $user->id && $role === 'user') {
            session()->flash('message', 'You cannot demote yourself to user.');
            return;
        }

        $user->update(['role' => $role]);
        session()->flash('message', 'User role updated.');
    }

    public function openPasswordModal(int $userId): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Only admins can change passwords.');
        }

        $user = User::findOrFail($userId);

        $this->resetValidation();
        $this->passwordUserId = $user->id;
        $this->passwordUserName = (string) $user->name;
        $this->passwordUserEmail = (string) $user->email;
        $this->passwordUserHasSmf = !empty($user->smf_id);
        $this->newPassword = '';
        $this->newPassword_confirmation = '';
        $this->showPasswordModal = true;
    }

    public function closePasswordModal(): void
    {
        $this->showPasswordModal = false;
        $this->passwordUserId = null;
        $this->passwordUserName = '';
        $this->passwordUserEmail = '';
        $this->passwordUserHasSmf = false;
        $this->newPassword = '';
        $this->newPassword_confirmation = '';
        $this->resetValidation();
    }

    public function updateUserPassword(): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Only admins can change passwords.');
        }

        if (!$this->passwordUserId) {
            session()->flash('message', 'Please select a user first.');
            return;
        }

        $this->validate([
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'newPassword.required' => 'Password is required.',
            'newPassword.min' => 'Password must be at least 8 characters.',
            'newPassword.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::findOrFail($this->passwordUserId);

        $user->forceFill([
            'password' => Hash::make($this->newPassword),
            'remember_token' => Str::random(60),
        ])->save();

        session()->flash('message', "Password updated for {$user->name}.");
        $this->closePasswordModal();
    }

    public function render()
    {
        $query = User::query()->orderBy('name');

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search);
            });
        }

        if ($this->role !== '') {
            $query->where('role', $this->role);
        }

        return view('livewire.admin-users', [
            'users' => $query->paginate(15),
            'counts' => [
                'all' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'moderator' => User::where('role', 'moderator')->count(),
                'user' => User::where('role', 'user')->count(),
            ],
        ])->layout('components.admin.layout', [
            'title' => 'User settings',
            'subtitle' => 'Manage users and roles',
        ]);
    }
}

