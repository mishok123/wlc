<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Field;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProject extends Component
{
    use WithFileUploads;

    public Project $project;

    // Basic fields
    public $name;
    public $logo;
    public $existingLogo;
    public $description;
    public $website_url;
    public $category_id;
    public $list_status;
    public $trust_score;
    public $star_rating;
    public $contact_email;
    public $contact_telegram;
    public $contact_discord;
    public $contact_simplex;
    public $terms_url;
    public $scam_reason;
    public $potential_risk_message;
    public $meta_title;
    public $meta_description;

    // Static mixer fields (same as SubmitProject)
    public $age;
    public $community;
    public $guarantee_fund;
    public $privacy_kyc;
    public $fee;
    public $log_verifiable = false;
    public $have_tor = false;
    public $no_reg_policy = false;
    public $no_log_policy_field = false;
    public $own_liquidity_field = false;
    public $code_audited = false;
    public $ownership_verified_field = false;
    public $supported_coin;
    public $launch_date;

    // New detailed fee fields
    public $fee_min;
    public $fee_max;
    public $fee_fixed;

    // New detailed conditional fields
    public $tor_urls;
    public $liquidity_amount;
    public $liquidity_proof_url;
    public $audit_url;
    public $guarantee_url;
    public $bitcoin_address;
    public $pgp_public_key;

    // Admin-only field: #18 Potential Risk
    public $potential_risk = 'No';

    // Admin-only field: #15 Good Customer Support Average Rating (0-5)
    public $customer_support_rating = 0;

    // Scores display
    public $reputation_score;
    public $privacy_score;

    // Whether the selected category is "Mixers"
    public $isMixerCategory = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:projects,name,' . $this->project->id,
            'logo' => 'nullable|image|max:300|dimensions:ratio=1/1,max_width=512,max_height=512',
            'description' => 'required|string|min:50',
            'website_url' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'list_status' => 'required|in:pending,approved,verified,scam,rejected',
            'trust_score' => 'required|numeric|min:-100|max:10',
            'fee_min' => 'nullable|numeric|min:0|max:100',
            'fee_max' => 'nullable|numeric|min:0|max:100',
            'fee_fixed' => 'nullable|numeric|min:0',
            'liquidity_amount' => 'nullable|numeric|min:0',
            'liquidity_proof_url' => 'nullable|url',
            'audit_url' => 'nullable|url',
            'guarantee_url' => 'nullable|url',
            'bitcoin_address' => 'required_if:log_verifiable,true|nullable|string|max:255',
            'pgp_public_key' => 'required_if:log_verifiable,true|nullable|string',
            'customer_support_rating' => 'nullable|integer|min:0|max:5',
            'contact_email' => 'nullable|email|max:255',
            'contact_telegram' => 'nullable|string|max:255',
            'contact_discord' => 'nullable|string|max:255',
            'contact_simplex' => 'nullable|string',
            'terms_url' => 'nullable|url|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];
    }

    // Map of property name => field key in database
    private function getMixerFieldMap(): array
    {
        return [
            'age'                     => 'age',
            'community'               => 'community',
            'guarantee_fund'          => 'guarantee_fund',
            'privacy_kyc'             => 'privacy_kyc',
            'fee'                     => 'fee',
            'log_verifiable'          => 'log_verifiable',
            'have_tor'                => 'have_tor',
            'no_reg_policy'           => 'no_reg_policy',
            'no_log_policy_field'     => 'no_log_policy_field',
            'own_liquidity_field'     => 'own_liquidity_field',
            'code_audited'            => 'code_audited',
            'potential_risk'          => 'potential_risk',
            'supported_coin'          => 'supported_coin',
            'launch_date'             => 'launch_date',
            'fee_min'                 => 'fee_min',
            'fee_max'                 => 'fee_max',
            'fee_fixed'               => 'fee_fixed',
            'tor_urls'                => 'tor_urls',
            'liquidity_amount'        => 'liquidity_amount',
            'liquidity_proof_url'     => 'liquidity_proof_url',
            'audit_url'               => 'audit_url',
            'ownership_verified_field' => 'ownership_verified',
            'customer_support_rating'  => 'customer_support_rating',
        ];
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->existingLogo = $project->logo;
        $this->description = $project->description;
        $this->website_url = $project->website_url;
        $this->category_id = $project->category_id;
        $this->list_status = $project->list_status;
        $this->trust_score = $project->trust_score;
        $this->star_rating = $project->star_rating;
        $this->reputation_score = $this->project->reputation_score;
        $this->privacy_score = $this->project->privacy_score;
        $this->guarantee_url = $this->project->guarantee_url;
        $this->contact_email = $this->project->contact_email;
        $this->contact_telegram = $this->project->contact_telegram;
        $this->contact_discord = $this->project->contact_discord;
        $this->contact_simplex = $this->project->contact_simplex;
        $this->terms_url = $this->project->terms_url;
        $this->scam_reason = $this->project->scam_reason;
        $this->potential_risk_message = $this->project->potential_risk_message;
        $this->meta_title = $this->project->meta_title;
        $this->meta_description = $this->project->meta_description;
        $this->bitcoin_address = $this->project->bitcoin_address;
        $this->pgp_public_key = $this->project->pgp_public_key;
        
        // Detect mixer category
        $category = Category::find($this->category_id);
        $this->isMixerCategory = $category && $category->slug === 'mixers';

        $maxRep = Project::getCategoryMaxReputation($this->category_id);
        $maxPriv = Project::getCategoryMaxPrivacy($this->category_id);
        $maxTotal = $maxRep + $maxPriv;
        
        // Use the same logic as Project model for consistency
        $totalRaw = max(0, floatval($this->reputation_score) + floatval($this->privacy_score));
        $scaledWLC = ($maxTotal > 0) ? ($totalRaw / $maxTotal) * 10 : 0;
        
        $this->trust_score = round(max(0, min(10, $scaledWLC)), 2);
        $this->ownership_verified_field = (bool) $this->project->ownership_verified;

        // Load existing administrative and mixer field values
        $this->loadAdminFieldValues();
    }

    private function loadAdminFieldValues()
    {
        $fieldMap = $this->getMixerFieldMap();
        $fieldKeys = array_values($fieldMap);
        
        // Get all fields by key
        $fields = Field::whereIn('key', $fieldKeys)->get()->keyBy('key');
        
        // Get existing values for this project
        $fieldIds = $fields->pluck('id')->toArray();
        $existingValues = $this->project->fieldValues()
            ->whereIn('field_id', $fieldIds)
            ->get()
            ->keyBy('field_id');

        $checkboxFields = [
            'log_verifiable', 'have_tor', 'no_reg_policy', 'no_log_policy_field', 
            'own_liquidity_field', 'code_audited', 'ownership_verified_field'
        ];

        foreach ($fieldMap as $property => $fieldKey) {
            $field = $fields->get($fieldKey);
            if (!$field) continue;

            $fieldValue = $existingValues->get($field->id);
            if ($fieldValue) {
                $val = $fieldValue->value;
                if (in_array($property, $checkboxFields)) {
                    $boolVal = ($val === 'Yes' || $val === '1' || $val === true || $val === 1);
                    $this->{$property} = $boolVal;
                    if ($property === 'ownership_verified_field') {
                        // Ensure it's true if either the model OR the dynamic field says so
                        $this->ownership_verified_field = (bool) $this->project->ownership_verified || $boolVal;
                    }
                } elseif ($property === 'customer_support_rating') {
                    $this->customer_support_rating = (int) str_replace(' star', '', $val);
                } else {
                    $this->{$property} = $val;
                }
            }
        }
    }

    public function updatedLaunchDate($value)
    {
        if ($value) {
            $this->age = $this->calculateAgeBracket($value);
        }
    }

    public function updatedListStatus($value)
    {
        if ($value) {
            $this->project->list_status = $value;
            $this->recalculateScores();
        }
    }

    public function updatedCategoryId($value)
    {
        if ($value) {
            $category = Category::find($value);
            $this->isMixerCategory = $category && $category->slug === 'mixers';
        } else {
            $this->isMixerCategory = false;
        }
    }

    public function recalculateScores()
    {
        $scores = $this->project->calculateScores();
        $this->reputation_score = $scores['reputation'];
        $this->privacy_score = $scores['privacy'];
        
        $maxRep = Project::getCategoryMaxReputation($this->category_id);
        $maxPriv = Project::getCategoryMaxPrivacy($this->category_id);
        $maxTotal = $maxRep + $maxPriv;
        
        // Consistency with Project model clamping
        $totalRaw = max(0, floatval($this->reputation_score) + floatval($this->privacy_score));
        $scaledWLC = ($maxTotal > 0) ? ($totalRaw / $maxTotal) * 10 : 0;
        
        $this->trust_score = round(max(0, min(10, $scaledWLC)), 2);
        
        $session = session();
        $session->flash('scores_recalculated', 'Scores recalculated successfully!');
    }

    public function regenerateSeo()
    {
        $siteTitleSetting = \App\Models\Setting::where('key', 'site_title')->first();
        $siteTitle = $siteTitleSetting ? $siteTitleSetting->value : 'WeLiveCrypto';
        
        $score = floatval($this->trust_score);
        if ($score <= 3.9) {
            $sentiment = "- proceed with caution";
        } elseif ($score < 7) {
            $sentiment = "of an emerging project";
        } else {
            $sentiment = "of a promising project";
        }
        
        $this->meta_title = "{$this->name} review {$sentiment} | {$siteTitle}";
        $this->meta_description = "How does {$this->name} compare to other crypto projects? Analyze privacy and reputation score. Discover if {$this->name} is worth your attention in " . date('Y') . ".";
        
        session()->flash('seo_message', 'SEO tags regenerated! Save changes to apply.');
    }

    public function save()
    {
        $minBankroll = \App\Models\Setting::getValue('min_bankroll_amount', 0);
        
        $this->validate(array_merge($this->rules(), [
            'liquidity_amount' => ($this->own_liquidity_field) 
                ? "nullable|numeric|min:{$minBankroll}" 
                : "nullable|numeric|min:0",
        ], [
            'liquidity_amount.min' => "The liquidity amount must be at least " . number_format($minBankroll) . " USD when \"Own Liquidity / Bankroll\" is checked."
        ]));

        $logoPath = $this->existingLogo;
        if ($this->logo) {
            $logoPath = $this->logo->store('project-logos', 'public');
        }

        $slug = Str::slug($this->name);

        $this->project->update([
            'name' => $this->name,
            'logo' => $logoPath,
            'slug' => $slug,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'terms_url' => $this->terms_url,
            'category_id' => $this->category_id,
            'contact_email' => $this->contact_email,
            'contact_telegram' => $this->contact_telegram,
            'contact_discord' => $this->contact_discord,
            'contact_simplex' => $this->contact_simplex,
            'guarantee_url' => ($this->guarantee_fund !== 'No escrow') ? $this->guarantee_url : null,
            'list_status' => (auth()->user() && auth()->user()->isAdmin()) ? $this->list_status : $this->project->list_status,
            'trust_score' => $this->trust_score,
            'ownership_verified' => ($this->ownership_verified_field === true || $this->ownership_verified_field === 'Yes' || $this->ownership_verified_field === '1' || $this->ownership_verified_field === 1),
            'scam_reason' => $this->scam_reason,
            'potential_risk_message' => $this->potential_risk_message,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'bitcoin_address' => ($this->log_verifiable === true || $this->log_verifiable === 'Yes' || $this->log_verifiable === '1' || $this->log_verifiable === 1) ? $this->bitcoin_address : null,
            'pgp_public_key' => ($this->log_verifiable === true || $this->log_verifiable === 'Yes' || $this->log_verifiable === '1' || $this->log_verifiable === 1) ? $this->pgp_public_key : null,
        ]);

        // Handle checkbox booleans back to Yes/No strings for the DB
        $checkboxFields = [
            'log_verifiable', 'have_tor', 'no_reg_policy', 'no_log_policy_field', 
            'own_liquidity_field', 'code_audited', 'ownership_verified_field'
        ];

        foreach ($checkboxFields as $field) {
            $this->$field = ($this->$field === true || $this->$field === 'Yes' || $this->$field === '1' || $this->$field === 1) ? 'Yes' : 'No';
        }

        // Save admin-specific and mixer-specific field values
        if ($this->launch_date) {
            $this->age = $this->calculateAgeBracket($this->launch_date);
        }
        
        // Convert customer_support_rating number to star format for scoring
        $originalRating = $this->customer_support_rating;
        $this->customer_support_rating = intval($this->customer_support_rating) . ' star';
        
        $this->saveAdminFields();
        
        // Convert back to number for UI
        $this->customer_support_rating = $originalRating;

        // Recalculate scores
        $this->project->calculateScores();

        session()->flash('message', 'Project updated successfully.');

        return redirect()->route('admin.projects');
    }

    private function saveAdminFields()
    {
        $fieldMap = $this->getMixerFieldMap();
        
        // Define which fields are mixer-only
        $mixerOnlyFields = [
            'age', 'community', 'guarantee_fund', 'privacy_kyc', 'fee', 
            'log_verifiable', 'have_tor', 'no_reg_policy', 'no_log_policy_field', 
            'own_liquidity_field', 'code_audited', 'supported_coin', 'launch_date',
            'fee_min', 'fee_max', 'fee_fixed', 'tor_urls', 'liquidity_amount', 
            'liquidity_proof_url', 'audit_url'
        ];

        $fields = Field::whereIn('key', array_values($fieldMap))->get()->keyBy('key');

        foreach ($fieldMap as $property => $fieldKey) {
            // Skip mixer-only fields if project is not a mixer
            if (!$this->isMixerCategory && in_array($property, $mixerOnlyFields)) {
                continue;
            }

            $value = $this->{$property};
            $field = $fields->get($fieldKey);
            if (!$field) continue;

            if ($value !== null && $value !== '') {
                $this->project->fieldValues()->updateOrCreate(
                    ['field_id' => $field->id],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            } else {
                $this->project->fieldValues()->where('field_id', $field->id)->delete();
            }
        }
    }

    private function calculateAgeBracket(string $date): string
    {
        try {
            $launchDate = Carbon::parse($date);
            $years = $launchDate->diffInYears(now());

            if ($years >= 5) return 'Age 5 and over';
            if ($years >= 4) return 'Age 4+ year';
            if ($years >= 3) return 'Age 3+ year';
            if ($years >= 2) return 'Age 2+ year';
            if ($years >= 1) return 'Age 1+ year';
            return 'Age 0-1year';
        } catch (\Exception $e) {
            return 'Age 0-1year';
        }
    }

    public function render()
    {
        return view('livewire.admin.edit-project', [
            'categories' => Category::orderBy('name')->get(),
            'categoryMaxRep' => Project::getCategoryMaxReputation($this->project->category_id),
            'categoryMaxPriv' => Project::getCategoryMaxPrivacy($this->project->category_id),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key')->toArray()
        ])->layout('components.admin.layout', ['title' => 'Edit Project: ' . $this->project->name]);
    }
}
