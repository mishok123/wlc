<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Field;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminCategories extends Component
{
    use WithPagination;

    public $name, $slug, $description;
    public $categoryId;
    public $isModalOpen = false;
    public $search = '';
    
    // For Field Assignment
    public $selectedFields = []; // [field_id => ['selected' => true, 'is_visible_in_card' => false, 'order' => 0]]

    protected $rules = [
        'name' => 'required',
        'slug' => 'required|unique:categories,slug',
        'description' => 'nullable',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Category::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin-categories', [
            'categories' => $query->with('fields')->paginate(10),
            'availableFields' => Field::all(),
        ])->layout('components.admin.layout');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->categoryId = null;
        $this->selectedFields = [];
        
        // Initialize selectedFields logic if needed, but easier to load on edit
    }
    
    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function store()
    {
        $validationRules = $this->rules;
        if ($this->categoryId) {
            $validationRules['slug'] = 'required|unique:categories,slug,' . $this->categoryId;
        }

        $this->validate($validationRules);

        $category = Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        // Sync Fields
        $syncData = [];
        foreach ($this->selectedFields as $fieldId => $data) {
            if (isset($data['selected']) && $data['selected']) {
                $syncData[$fieldId] = [
                    'is_visible_in_card' => $data['is_visible_in_card'] ?? false,
                    'order' => $data['order'] ?? 0,
                ];
            }
        }
        $category->fields()->sync($syncData);

        session()->flash('message', $this->categoryId ? 'Category Updated Successfully.' : 'Category Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::with('fields')->findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;

        // Load fields
        $this->selectedFields = [];
        foreach ($category->fields as $field) {
            $this->selectedFields[$field->id] = [
                'selected' => true,
                'is_visible_in_card' => (bool) $field->pivot->is_visible_in_card,
                'order' => $field->pivot->order,
            ];
        }

        $this->openModal();
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Category Deleted Successfully.');
    }
}
