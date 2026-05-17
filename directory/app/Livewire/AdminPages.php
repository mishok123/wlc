<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminPages extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $page = Page::find($id);
        if ($page) {
            $page->delete();
            session()->flash('message', 'Page Deleted Successfully.');
        } else {
            session()->flash('error', 'Page not found.');
        }
    }

    public function render()
    {
        $query = Page::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin-pages', [
            'pages' => $query->orderBy('created_at', 'desc')->paginate(10),
        ])->layout('components.admin.layout', ['title' => 'Manage Pages']);
    }
}
