<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;

class EditPage extends Component
{
    public ?Page $page = null;
    public $title, $slug, $content, $status = 'published';

    protected $rules = [
        'title' => 'required|min:3',
        'slug' => 'required',
        'content' => 'nullable',
        'status' => 'required|in:draft,published',
    ];

    public function mount(?Page $page = null)
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->status = $page->status;
        } else {
            $this->page = new Page();
            $this->status = 'published';
        }
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function save()
    {
        $rules = $this->rules;
        $rules['slug'] = 'required|unique:pages,slug,' . ($this->page->id ?? 'NULL');

        $this->validate($rules);

        $this->page->fill([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
        ])->save();

        session()->flash('message', 'Page saved successfully.');

        return redirect()->route('admin.pages');
    }

    public function render()
    {
        $title = $this->page->exists ? 'Edit Page: ' . $this->page->title : 'Create New Page';
        return view('livewire.admin.edit-page')
            ->layout('components.admin.layout', ['title' => $title]);
    }
}
