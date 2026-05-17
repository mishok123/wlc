<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.page-show')
            ->layout('components.layouts.app', ['title' => $this->page->title]);
    }
}
