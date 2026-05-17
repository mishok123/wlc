<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Category;
use App\Models\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Livewire\WithFileUploads;

class SubmitProject extends Component
{
    use WithFileUploads;

    // Basic fields
    public $name;
    public $logo;
    public $description;
    public $website_url;
    public $terms_url;
    public $category_id;
    public $contact_email;
    public $contact_telegram;
    public $contact_discord;
    public $contact_simplex;

    // New Fee fields
    public $fee_min;
    public $fee_max;
    public $fee_fixed;
    public $tor_urls;
    public $liquidity_amount;
    public $liquidity_proof_url;
    public $audit_url;
    public $guarantee_url;
    public $bitcoin_address;
    public $pgp_public_key;

    // Static mixer fields (from scoring table)
    public $age;                      // #1 - date input (launch date)
    public $community;                // #2 - textarea (URLs)
    public $guarantee_fund;           // #3 - select
    public $privacy_kyc;              // #4 - select
    public $fee = 'Low fees';         // #5 - select (aligned with seeder key 'fee')

    public $log_verifiable = false;           // #7 - select (Yes/No)
    public $have_tor = false;                 // #11 - select (Yes/No)
    public $no_reg_policy = false;            // #12 - select (Yes/No)
    public $no_log_policy_field = false;      // #13 - select (aligned with seeder key 'no_log_policy_field')
    public $own_liquidity_field = false;      // #14 - select (aligned with seeder key 'own_liquidity_field')
    public $code_audited = false;             // #16 - select (Yes/No)
    public $ownership_verified_field = false; // #20 - checkbox for scoring

    public $potential_risk = 'No';    // #18 - admin only
    public $customer_support_rating = 0; // #15 - admin only
    public $supported_coin = '';           // Added for comma-separated coin support
    public $launch_date;

    public $verify_ownership = false;
    public $verification_code = null;

    // Admin only
    public $list_status = 'pending';
    public $scam_reason;
    public $potential_risk_message;

    public $isMixerCategory = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:projects,name',
        'logo' => 'nullable|image|max:300|dimensions:ratio=1/1,max_width=512,max_height=512',
        'description' => 'required|string|min:50',
        'website_url' => 'required|string',
        'terms_url' => 'nullable|url',
        'category_id' => 'required|exists:categories,id',
        'contact_email' => 'nullable|email|max:255',
        'contact_telegram' => 'nullable|string|max:255',
        'contact_discord' => 'nullable|string|max:255',
        'contact_simplex' => 'nullable|string',
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
        'verify_ownership' => 'boolean',
    ];

    public function updatedLaunchDate($value)
    {
        if ($value) {
            $this->age = $this->calculateAgeBracket($value);
        }
    }

    public function updatedVerifyOwnership($value)
    {
        if ($value && empty($this->verification_code)) {
            $this->verification_code = strtoupper(\Illuminate\Support\Str::random(10));
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

    public function submit()
    {
        if ($this->launch_date) {
            $this->age = $this->calculateAgeBracket($this->launch_date);
        }

        $minBankroll = \App\Models\Setting::getValue('min_bankroll_amount', 0);
        
        $this->validate(array_merge($this->rules, [
            'liquidity_amount' => ($this->own_liquidity_field) 
                ? "nullable|numeric|min:{$minBankroll}" 
                : "nullable|numeric|min:0",
        ], [
            'liquidity_amount.min' => "The liquidity amount must be at least " . number_format($minBankroll) . " USD when \"Own Liquidity / Bankroll\" is checked."
        ]));

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('project-logos', 'public');
        }

        $slug = Str::slug($this->name);

        $project = Project::create([
            'user_id' => Auth::id(),
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
            'list_status' => (Auth::user() && Auth::user()->isAdmin()) ? $this->list_status : 'pending',
            'online_status' => 'offline',
            'star_rating' => 0,
            'review_count' => 0,
            'trust_score' => 50,
            'ownership_verified' => $this->ownership_verified_field,
            'verification_code' => $this->verify_ownership ? $this->verification_code : null,
            'scam_reason' => (Auth::user() && Auth::user()->isAdmin()) ? $this->scam_reason : null,
            'potential_risk_message' => (Auth::user() && Auth::user()->isAdmin()) ? $this->potential_risk_message : null,
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
        // Convert customer_support_rating number to star format for scoring
        $originalRating = $this->customer_support_rating;
        $this->customer_support_rating = intval($this->customer_support_rating) . ' star';
        
        $this->saveAdminFields($project);
        
        // Convert back for UI (though we redirect, it's good practice)
        $this->customer_support_rating = $originalRating;

        session()->flash('message', 'Project submitted successfully! It is now pending approval.');
        
        return redirect()->route('home');
    }

    private function saveAdminFields($project)
    {
        $fieldMap = [
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
            if ($value === null || $value === '') continue;

            $field = $fields->get($fieldKey);
            if (!$field) continue;

            $project->fieldValues()->create([
                'field_id' => $field->id,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]);
        }

        // Calculate reputation and privacy scores
        $project->calculateScores();
        $project->refresh();

        // Auto-generate SEO Meta tags
        $siteTitleSetting = \App\Models\Setting::where('key', 'site_title')->first();
        $siteTitle = $siteTitleSetting ? $siteTitleSetting->value : 'WeLiveCrypto';
        
        $score = $project->trust_score ?? 0;
        if ($score <= 3.9) {
            $sentiment = "- proceed with caution";
        } elseif ($score < 7) {
            $sentiment = "of an emerging project";
        } else {
            $sentiment = "of a promising project";
        }
        
        $project->meta_title = "{$project->name} review {$sentiment} | {$siteTitle}";
        $project->meta_description = "How does {$project->name} compare to other crypto projects? Analyze privacy and reputation score. Discover if {$project->name} is worth your attention in " . date('Y') . ".";
        $project->save();
    }

    /**
     * Convert a launch date into an age bracket label for scoring.
     */
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
        return view('livewire.submit-project', [
            'categories' => Category::orderBy('name')->get(),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key')->toArray()
        ]);
    }
}
