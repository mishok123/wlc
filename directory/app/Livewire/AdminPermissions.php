<?php

namespace App\Livewire;

use Livewire\Component;

class AdminPermissions extends Component
{
    public function render()
    {
        return view('livewire.admin-permissions')
            ->layout('components.admin.layout', [
                'title' => 'Permissions',
                'subtitle' => 'How access is controlled',
            ]);
    }
}

