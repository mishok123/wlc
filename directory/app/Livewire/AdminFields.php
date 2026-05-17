<?php

namespace App\Livewire;

use App\Models\Field;
use Livewire\Component;
use Livewire\WithPagination;

class AdminFields extends Component
{
    use WithPagination;

    public $name, $key, $type = 'text';
    public $optionItems = [];
    public $fieldId;
    public $isModalOpen = false;
    public $search = '';

    // List of keys that the system depends on for scoring or logic
    protected $protectedKeys = [
        'age',
        'community',
        'guarantee_fund',
        'privacy_kyc',
        'fee',
        'log_verifiable',
        'have_tor',
        'no_reg_policy',
        'no_log_policy_field',
        'own_liquidity_field',
        'code_audited',
        'ownership_verified',
        'potential_risk',
        'supported_coin',
        'launch_date',
        'fee_min',
        'fee_max',
        'fee_fixed',
        'tor_urls',
        'liquidity_amount',
        'liquidity_proof_url',
        'audit_url',
        'cryptocurrency',
        'fixed_fee',
        'time_delay',
        'online_status',
        'start_date',
        'letter_of_guarantee'
    ];

    protected $rules = [
        'name' => 'required',
        'key' => 'required|unique:fields,key',
        'type' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Field::whereHas('categories', function ($q) {
            $q->where('slug', 'mixers');
        });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('key', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin-fields', [
            'fields' => $query->paginate(10),
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
        $this->key = '';
        $this->type = 'text';
        $this->optionItems = [];
        $this->fieldId = null;
    }

    public function addOption()
    {
        $this->optionItems[] = ['name' => '', 'reputation' => 0, 'privacy' => 0];
    }

    public function removeOption($index)
    {
        unset($this->optionItems[$index]);
        $this->optionItems = array_values($this->optionItems);
    }

    public function store()
    {
        $validationRules = $this->rules;
        if ($this->fieldId) {
            $validationRules['key'] = 'required|unique:fields,key,' . $this->fieldId;
        }

        $this->validate($validationRules);

        $data = [
            'name' => $this->name,
            'key' => $this->key,
            'type' => $this->type,
        ];

        if (in_array($this->type, ['select', 'radio', 'system'])) {
            $optionsArray = [];
            $optionScoresArray = [];

            foreach ($this->optionItems as $item) {
                $optName = trim($item['name'] ?? '');
                if (!empty($optName)) {
                    $optionsArray[] = $optName;
                    $optionScoresArray[$optName] = [
                        'reputation' => (float) ($item['reputation'] ?? 0),
                        'privacy' => (float) ($item['privacy'] ?? 0),
                    ];
                }
            }
            $data['options'] = $optionsArray;
            $data['option_scores'] = empty($optionScoresArray) ? null : $optionScoresArray;
        } else {
            $data['options'] = null;
            $data['option_scores'] = null;
        }

        Field::updateOrCreate(['id' => $this->fieldId], $data);

        session()->flash('message', $this->fieldId ? 'Field Updated Successfully.' : 'Field Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $field = Field::findOrFail($id);
        $this->fieldId = $id;
        $this->name = $field->name;
        $this->key = $field->key;
        $this->type = $field->type;

        $this->optionItems = [];
        $savedOptions = is_array($field->options) ? $field->options : [];

        // If it's a system field without explicit options, derive them from option_scores keys
        if (empty($savedOptions) && is_array($field->option_scores)) {
            $savedOptions = array_keys((array) $field->option_scores);
        }

        if (is_string($savedOptions)) {
            $savedOptions = array_map('trim', explode(',', $savedOptions));
        }

        foreach ($savedOptions as $optName) {
            $rep = 0;
            $priv = 0;
            if (is_array($field->option_scores) && isset($field->option_scores[$optName])) {
                $rep = $field->option_scores[$optName]['reputation'] ?? 0;
                $priv = $field->option_scores[$optName]['privacy'] ?? 0;
            }
            $this->optionItems[] = [
                'name' => $optName,
                'reputation' => $rep,
                'privacy' => $priv
            ];
        }

        $this->openModal();
    }

    public function isProtected($field)
    {
        return true;
    }

    public function delete($id)
    {
        $field = Field::findOrFail($id);

        if ($this->isProtected($field)) {
            session()->flash('error', 'This is a system field and cannot be deleted.');
            return;
        }

        $field->delete();
        session()->flash('message', 'Field Deleted Successfully.');
    }
}
