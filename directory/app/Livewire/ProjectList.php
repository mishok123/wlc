<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Project;
use App\Models\Category;
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $verified = false; 
    public $approved = false;
    public $onion = false;
    public $activeFilters = []; // Array of field_id => boolean
    public $sort = 'score_high_low'; 
    public $perPage = 12;
    public $filtersInitialized = false;
    public $viewMode = 'grid';

    public function mount()
    {
        if (!$this->filtersInitialized) {
            $ageField = \App\Models\Field::where('name', 'Age')->first();
            if ($ageField && !isset($this->activeFilters[$ageField->id])) {
                $this->activeFilters[$ageField->id]['min'] = 0;
            }
            $this->filtersInitialized = true;
        }
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'score_high_low'],
        'verified' => ['except' => false],
        'approved' => ['except' => false],
        'activeFilters' => ['except' => []],
        'viewMode' => ['except' => 'grid'],
    ];

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->activeFilters = []; // Reset dynamic filters when category changes
        $this->verified = false; // Reset verified? Maybe not needed if global
        $this->approved = false;
        $this->onion = false;
        $this->resetPage();
    }

    public function updatedVerified() { $this->resetPage(); $this->perPage = 12; }
    public function updatedApproved() { $this->resetPage(); $this->perPage = 12; }
    public function updatedOnion() { $this->resetPage(); $this->perPage = 12; }

    public function updatedSort()
    {
        $this->resetPage();
        $this->perPage = 12;
    }

    public function updatedActiveFilters()
    {
        $this->resetPage();
        $this->perPage = 12;
    }

    public function updatedViewMode()
    {
        $this->resetPage();
        $this->perPage = 12;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->verified = false;
        $this->approved = false;
        $this->onion = false;
        $this->activeFilters = [];
        
        // Re-initialize Age default for slider
        $ageField = \App\Models\Field::where('name', 'Age')->first();
        if ($ageField) {
            $this->activeFilters[$ageField->id]['min'] = 0;
        }

        $this->perPage = 12;
        $this->resetPage();
    }

    public function render()
    {
        // 1. Base Query (Search, Sorting, Global Filters)
        // These filters affect the project list AND the category counts (except for the category itself)
        $baseQuery = Project::query()
            ->whereNotIn('list_status', ['proposed', 'rejected']);

        if ($this->search) {
            $baseQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
            // Exclude pending and rejected from search results
            $baseQuery->whereNotIn('list_status', ['pending', 'rejected']);
        }

        $statuses = [];
        if ($this->verified) $statuses[] = 'verified';
        if ($this->approved) $statuses[] = 'approved';

        if (!empty($statuses)) {
            $baseQuery->whereIn('list_status', $statuses);
        }

        if ($this->onion) {
             $baseQuery->whereHas('fieldValues', function($q) {
                 $q->whereHas('field', function($f) {
                     $f->where('name', 'Have Tor');
                 })->whereIn('value', ['1', 'true', 'yes', 'on', 'Yes']);
             });
        }

        // 2. Dynamic Category Facets
        // We calculate counts for each category based on the base query (ignoring category filter)
        $categories = Category::orderBy('name')
            ->get()
            ->map(function ($cat) use ($baseQuery) {
                $cat->projects_count = (clone $baseQuery)
                    ->whereHas('category', function($q) use ($cat) {
                        $q->where('id', $cat->id);
                    })
                    ->count();
                return $cat;
            });
            // REMOVED ->filter(...) to keep all categories visible even if no matches found

        $totalProjectsCount = (clone $baseQuery)->count();

        // 3. Extract all field IDs available in the current category/industry to show stable filters
        if ($this->category) {
            $cat = \App\Models\Category::where('slug', $this->category)->first();
            $fieldIds = $cat ? $cat->fields()->pluck('fields.id')->unique() : collect();
        } else {
            // If no category, show fields assigned to ANY category (stable list)
            $fieldIds = \DB::table('category_field')->distinct()->pluck('field_id');
        }

        // 4. Apply Category Filter to the actual results query
        $resultsQuery = (clone $baseQuery)->with(['category.fields', 'fieldValues.field'])
            ->withCount([
                'reviews as positive_reviews' => function ($query) { $query->where('rating', '>=', 4); },
                'reviews as neutral_reviews' => function ($query) { $query->where('rating', 3); },
                'reviews as negative_reviews' => function ($query) { $query->where('rating', '<', 3); }
            ]);

        if ($this->category) {
            $resultsQuery->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // 5. Dynamic Fields Filter (Active Filters)
        if (!empty($this->activeFilters)) {
            foreach ($this->activeFilters as $fieldId => $value) {
                if ($value === '' || $value === false || $value === null) continue;

                if (is_array($value)) {
                    if (isset($value['min']) || isset($value['max'])) {
                        $hasActiveRange = (isset($value['min']) && $value['min'] !== '') || (isset($value['max']) && $value['max'] !== '');
                        if (!$hasActiveRange) continue;
                    } else {
                        $filtered = array_filter($value);
                        if (empty($filtered)) continue;
                    }
                }

                if ($fieldId == 27) { // Supported Coin
                    if (is_array($value)) {
                        $selectedCoins = array_keys(array_filter($value));
                        if (!empty($selectedCoins)) {
                            $resultsQuery->whereHas('fieldValues', function($q) use ($selectedCoins) {
                                $q->where('field_id', 27);
                                $q->where(function($sq) use ($selectedCoins) {
                                    foreach ($selectedCoins as $coin) {
                                        $sq->orWhere('value', 'like', '%' . $coin . '%');
                                    }
                                });
                            });
                        }
                    } elseif ($value) {
                        $resultsQuery->whereHas('fieldValues', function($q) use ($value) {
                            $q->where('field_id', 27)
                              ->where('value', 'like', '%' . $value . '%');
                        });
                    }
                    continue;
                }

                if ($fieldId === 'fees_range') {
                    if (is_array($value)) {
                        $resultsQuery->whereHas('fieldValues', function($sq) use ($value) {
                            $sq->whereIn('field_id', [46, 47]); // 46=Fees Min, 47=Fees Max
                            if (isset($value['max']) && $value['max'] !== '') {
                                $sq->whereRaw('CAST(value AS DECIMAL(10,2)) <= ?', [$value['max']]);
                            }
                        });
                    }
                    continue;
                }

                if ($fieldId == 57) { // Customer Support Rating
                    if (isset($value['min']) && $value['min'] !== '') {
                        $resultsQuery->whereHas('fieldValues', function($q) use ($value) {
                            $q->where('field_id', 57)
                              ->whereRaw("CAST(SUBSTRING_INDEX(value, ' ', 1) AS UNSIGNED) >= ?", [$value['min']]);
                        });
                    }
                    continue;
                }

                if ($fieldId == 28) { // Age / In Business
                    $resultsQuery->where(function($bigQ) use ($value) {
                        $bigQ->whereHas('fieldValues', function($q) use ($value) {
                            $q->where('field_id', 29); // Launch Date
                            if (isset($value['min']) && $value['min'] !== '') {
                                $q->whereRaw("TIMESTAMPDIFF(YEAR, CAST(value AS DATE), NOW()) >= ?", [$value['min']]);
                            }
                            if (isset($value['max']) && $value['max'] !== '') {
                                $q->whereRaw("TIMESTAMPDIFF(YEAR, CAST(value AS DATE), NOW()) <= ?", [$value['max']]);
                            }
                        })->orWhereHas('fieldValues', function($q) use ($value) {
                            $q->where('field_id', 28); // Age Bracket strings
                            $extractSql = "CAST(SUBSTRING_INDEX(REPLACE(value, 'Age ', ''), '-', 1) AS UNSIGNED)";
                            if (isset($value['min']) && $value['min'] !== '') {
                                $q->whereRaw("$extractSql >= ?", [$value['min']]);
                            }
                            if (isset($value['max']) && $value['max'] !== '') {
                                $q->whereRaw("$extractSql <= ?", [$value['max']]);
                            }
                        });
                    });
                    continue;
                }

                $field = \App\Models\Field::find($fieldId);
                if (!$field) continue;

                $resultsQuery->whereHas('fieldValues', function($q) use ($fieldId, $value, $field) {
                    $q->where('field_id', $fieldId);

                    if ($field->id == 48 || in_array($field->type, ['boolean', 'checkbox'])) {
                         if ($value === true || $value === 'true' || $value === 1 || $value === '1' || $value === 'on') {
                             if ($field->type === 'number') {
                                 $q->whereRaw('CAST(value AS DECIMAL(10,2)) > 0');
                             } else {
                                 $q->whereIn('value', ['1', 'true', 'yes', 'on']);
                             }
                         }
                    } elseif ($field->type === 'select') {
                         if (is_array($value)) {
                             $q->whereIn('value', array_keys(array_filter($value)));
                         } elseif ($value === true || $value === 'true' || $value === 1 || $value === '1') {
                             $q->where('value', 'Yes');
                         } elseif ($value) {
                             $q->where('value', $value);
                         }
                    } elseif (in_array($field->type, ['number', 'date', 'age'])) {
                         if (is_array($value)) {
                             if (in_array($field->type, ['date', 'age'])) {
                                 if (isset($value['min']) && $value['min'] !== '') {
                                     $q->whereRaw("TIMESTAMPDIFF(YEAR, CAST(value AS DATE), NOW()) >= ?", [$value['min']]);
                                 }
                                 if (isset($value['max']) && $value['max'] !== '') {
                                     $q->whereRaw("TIMESTAMPDIFF(YEAR, CAST(value AS DATE), NOW()) <= ?", [$value['max']]);
                                 }
                             } else {
                                 $precision = str_contains($field->name, 'Fixed') ? '20,8' : '10,2';
                                 if (isset($value['min']) && $value['min'] !== '') {
                                     $q->whereRaw("CAST(value AS DECIMAL($precision)) >= ?", [$value['min']]);
                                 }
                                 if (isset($value['max']) && $value['max'] !== '') {
                                     $q->whereRaw("CAST(value AS DECIMAL($precision)) <= ?", [$value['max']]);
                                 }
                             }
                         }
                    } else {
                        $q->where('value', 'like', '%' . $value . '%');
                    }
                });
            }
        }

        // Sorting
        if ($this->sort === 'score_high_low') {
            $resultsQuery->orderBy('trust_score', 'desc');
        } elseif ($this->sort === 'score_low_high') {
             $resultsQuery->orderBy('trust_score', 'asc');
        } elseif ($this->sort === 'a_z') {
             $resultsQuery->orderBy('name', 'asc');
        } elseif ($this->sort === 'newest') {
            $resultsQuery->orderBy('created_at', 'desc');
        }
        $resultsQuery->orderBy('id', 'desc');

        // 6. Dynamic Filter Fields (Restricted to User's Reference Image)
        $requiredFieldIds = [27, 31, 30, 16, 28, 57, 48, 32, 34, 19, 35, 45, 37, 7, 33];
        
        $filterFields = \App\Models\Field::whereIn('id', $requiredFieldIds)
            ->get()
            ->keyBy('id');

        if (isset($filterFields[27])) {
            $matchingProjectIds = (clone $baseQuery);
            if ($this->category) {
                $matchingProjectIds->whereHas('category', function($q) {
                    $q->where('slug', $this->category);
                });
            }
            $projectIds = $matchingProjectIds->pluck('id');

            $coinValues = \App\Models\ProjectFieldValue::where('field_id', 27)
                ->whereIn('project_id', $projectIds)
                ->pluck('value');
            $coins = [];
            foreach ($coinValues as $val) {
                foreach (explode(',', $val) as $c) {
                    $c = strtoupper(trim($c));
                    if ($c !== '') {
                        $coins[] = $c;
                    }
                }
            }
            $uniqueCoins = array_values(array_unique($coins));
            sort($uniqueCoins);
            $filterFields[27]->options = $uniqueCoins;
        }
        
        // No more complex sorting here, we will handle the exact order in the Blade file

        $categoryMaxes = [];
        $activeCategories = Category::all();
        foreach ($activeCategories as $cat) {
            $categoryMaxes[$cat->id] = [
                'rep' => Project::getCategoryMaxReputation($cat->id),
                'priv' => Project::getCategoryMaxPrivacy($cat->id),
            ];
        }

        // Calculate Dynamic Fee Range (Global)
        $minFee = \App\Models\ProjectFieldValue::where('field_id', 46)->min('value') ?? 0;
        $maxFee = \App\Models\ProjectFieldValue::where('field_id', 47)->max('value') ?? 5; // Default max 5 if none found
        
        // Ensure decimal format
        $minFee = floatval($minFee);
        $maxFee = floatval($maxFee);

        return view('livewire.project-list', [
            'projects' => $resultsQuery->paginate($this->perPage),
            'categories' => $categories,
            'totalProjects' => $totalProjectsCount,
            'filterFields' => $filterFields,
            'categoryMaxes' => $categoryMaxes,
            'minFee' => $minFee,
            'maxFee' => $maxFee,
        ]);
    }
}
