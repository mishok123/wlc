<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class AdminReputationPrivacy extends Component
{
    public $categories = [];
    public $selectedCategoryId = null;
    public $selectedCategory = null;
    public $categoryFields = [];

    public $scores = [];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function updatedSelectedCategoryId($value)
    {
        $this->selectCategory($value);
    }

    public function selectCategory($categoryId)
    {
        if (empty($categoryId)) {
            $this->selectedCategory = null;
            $this->categoryFields = [];
            $this->scores = [];
            return;
        }

        $this->selectedCategory = Category::with('fields')->find($categoryId);
        $this->scores = [];

        if ($this->selectedCategory) {

            // Only fields that have options (select, radio, system, checkbox, or 'community' textarea)
            $this->categoryFields = $this->selectedCategory->fields()
                ->whereIn('type', ['select', 'radio', 'system', 'checkbox', 'textarea', 'date'])
                ->whereNotIn('key', ['launch_date', 'tor_urls', 'api', 'bot', 'code_source'])
                ->get();

            foreach ($this->categoryFields as $field) {
                $globalScores = $field->option_scores ?? [];

                // pivot attribute is an array/json if properly cast, but if not we might need to decode?
                // Wait, if it wasn't cast in Category.php, it's string. Let's decode if it's a string.
                $pivotScoresRaw = $field->pivot->option_scores;
                $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);

                $optionsList = is_array($field->options) ? $field->options : (is_string($field->options) ? explode(',', $field->options) : (empty($field->options) ? [] : explode(',', (string)$field->options)));
                if (empty($optionsList)) {
                    if (!empty($globalScores)) {
                        $optionsList = array_keys((array) $globalScores);
                    } elseif ($field->type === 'checkbox') {
                        $optionsList = ['Yes', 'No'];
                    }
                }

                // Special handling for multiplier fields - these should override defaults
                if ($field->key === 'community') {
                    $optionsList = ['Per Community'];
                } elseif (in_array($field->key, ['cryptocurrency', 'supported_coin'])) {
                    $optionsList = ['Per Coin'];
                }

                $this->scores[$field->id] = [];

                foreach ($optionsList as $optName) {
                    $optName = trim($optName);

                    $rep = $pivotScores[$optName]['reputation'] ?? ($globalScores[$optName]['reputation'] ?? 0);
                    $priv = $pivotScores[$optName]['privacy'] ?? ($globalScores[$optName]['privacy'] ?? 0);

                    $this->scores[$field->id][$optName] = [
                        'reputation' => (float) $rep,
                        'privacy' => (float) $priv,
                    ];
                }
            }
        }
    }

    public function saveScores()
    {
        if (!$this->selectedCategory) {
            return;
        }

        foreach ($this->scores as $fieldId => $optionsData) {
            // Format optionsData back before saving (it should be an array)
            $this->selectedCategory->fields()->updateExistingPivot($fieldId, [
                'option_scores' => json_encode($optionsData)
            ]);
        }

        session()->flash('message', 'Scores saved successfully for ' . $this->selectedCategory->name);
    }

    public function render()
    {
        return view('livewire.admin-reputation-privacy', [
            'categoryDBMaxRep' => $this->selectedCategoryId ? \App\Models\Project::getCategoryMaxReputation($this->selectedCategoryId) : 0,
            'categoryDBMaxPriv' => $this->selectedCategoryId ? \App\Models\Project::getCategoryMaxPrivacy($this->selectedCategoryId) : 0,
        ])->layout('components.admin.layout', [
            'title' => 'Reputation and Privacy Scoring',
            'subtitle' => 'Set unique scores for static fields per category'
        ]);
    }
}
