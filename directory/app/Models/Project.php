<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class Project extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'supported_cryptos' => 'array',
        'supported_networks' => 'array',
        'features' => 'array',
        'discussion_channels' => 'array',
        'security_features' => 'array',
        'letter_of_guarantee' => 'boolean',
        'ownership_verified' => 'boolean',
        'registration_required' => 'boolean',
        'no_log_policy' => 'boolean',
        'source_code_availability' => 'boolean',
        'own_liquidity' => 'boolean',
        'reputation_score' => 'decimal:2',
        'privacy_score' => 'decimal:2',
        'trust_score' => 'decimal:2',
        'positive_count' => 'integer',
        'neutral_count' => 'integer',
        'negative_count' => 'integer',
        'star_rating' => 'integer',
        'url_statuses' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getWebsiteUrlsAttribute()
    {
        if (!$this->website_url) {
            return [];
        }

        return array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $this->website_url))));
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function fieldValues()
    {
        return $this->hasMany(ProjectFieldValue::class);
    }

    public function requests()
    {
        return $this->hasMany(ProjectRequest::class);
    }

    public function reports()
    {
        return $this->hasMany(ProjectReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDynamicField($key)
    {
        $fieldValue = $this->fieldValues->first(function ($fv) use ($key) {
            return $fv->field && $fv->field->key === $key;
        });

        return $fieldValue ? $fieldValue->value : null;
    }

    /**
     * Calculate and save reputation and privacy scores based on field values and their option_scores.
     */
    public function calculateScores()
    {
        $this->load('fieldValues.field');

        $reputation = 0;
        $privacy = 0;

        // Load the category and its fields with pivot data
        $categoryFields = $this->category ? $this->category->fields()->get()->keyBy('id') : collect();

        foreach ($this->fieldValues as $fv) {
            if (!$fv->field) {
                continue;
            }

            // Get global option scores
            $globalOptionScores = $fv->field->option_scores ?? [];

            // Get category specific option scores if available
            $catField = $categoryFields->get($fv->field_id);
            $pivotOptionScoresRaw = $catField ? $catField->pivot->option_scores : [];
            $pivotOptionScores = is_string($pivotOptionScoresRaw) ? json_decode($pivotOptionScoresRaw, true) : ($pivotOptionScoresRaw ?? []);
            if (!is_array($pivotOptionScores))
                $pivotOptionScores = [];

            // Merge so that pivot scores take precedence
            $optionScores = array_merge($globalOptionScores, $pivotOptionScores);

            if (empty($optionScores)) {
                // Fallback for supported_coin to use cryptocurrency's configuration if empty
                if ($fv->field->key === 'supported_coin') {
                    $cryptoField = $categoryFields->firstWhere('key', 'cryptocurrency');
                    if ($cryptoField) {
                        $cGlobalScores = $cryptoField->option_scores ?? [];
                        $cPivotRaw = $cryptoField->pivot->option_scores ?? [];
                        $cPivot = is_string($cPivotRaw) ? json_decode($cPivotRaw, true) : ($cPivotRaw ?? []);
                        if (!is_array($cPivot))
                            $cPivot = [];
                        $optionScores = array_merge($cGlobalScores, $cPivot);
                    }
                }

                if (empty($optionScores)) {
                    continue;
                }
            }

            if (in_array($fv->field->key, ['age', 'launch_date', 'ownership_verified'])) {
                continue;
            }

            $value = $fv->value;

            if ($fv->field->key === 'community' && isset($optionScores['Per Community'])) {
                // Count number of lines/URLs in community field
                $urls = array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $value))));
                $count = count($urls);

                $reputation += floatval($optionScores['Per Community']['reputation'] ?? 0) * $count;
                $privacy += floatval($optionScores['Per Community']['privacy'] ?? 0) * $count;
            } elseif (in_array($fv->field->key, ['cryptocurrency', 'supported_coin']) && isset($optionScores['Per Coin'])) {
                // Count number of coins (comma separated)
                $coins = array_filter(array_map('trim', explode(',', $value)));
                $count = count($coins);

                $reputation += floatval($optionScores['Per Coin']['reputation'] ?? 0) * $count;
                $privacy += floatval($optionScores['Per Coin']['privacy'] ?? 0) * $count;
            } elseif ($fv->field->key === 'privacy_kyc') {
                $matchedScore = null;
                $valLower = strtolower(trim($value));
                foreach ($optionScores as $key => $scores) {
                    $kLower = strtolower(trim($key));
                    if (
                        $valLower === $kLower ||
                        str_replace('privacy kyc', 'kyc information', $valLower) === $kLower ||
                        str_replace('kyc information', 'privacy kyc', $valLower) === $kLower ||
                        str_contains($valLower, $kLower) ||
                        str_contains($kLower, $valLower)
                    ) {
                        $matchedScore = $scores;
                        break;
                    }
                }
                if ($matchedScore) {
                    $reputation += floatval($matchedScore['reputation'] ?? 0);
                    $privacy += floatval($matchedScore['privacy'] ?? 0);
                }
            } elseif (isset($optionScores[$value])) {
                $reputation += floatval($optionScores[$value]['reputation'] ?? 0);
                $privacy += floatval($optionScores[$value]['privacy'] ?? 0);
            }
        }

        // System-generated: Age related scoring
        $launchDateValue = $this->getDynamicField('launch_date');
        $ageField = $categoryFields->firstWhere('key', 'age');
        if ($launchDateValue && $ageField) {
            $globalScores = $ageField->option_scores ?? [];
            $pivotScoresRaw = $ageField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $bracket = $this->calculateAgeBracket($launchDateValue);
                if (isset($scores[$bracket])) {
                    $reputation += floatval($scores[$bracket]['reputation'] ?? 0);
                    $privacy += floatval($scores[$bracket]['privacy'] ?? 0);
                }
            }
        }

        // System-generated: List status scoring
        $listStatusField = $categoryFields->firstWhere('key', 'list_status_scoring');
        if ($listStatusField) {
            $globalScores = $listStatusField->option_scores ?? [];
            $pivotScoresRaw = $listStatusField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $statusKey = Str::title($this->list_status ?? 'pending');
                if (isset($scores[$statusKey])) {
                    $reputation += floatval($scores[$statusKey]['reputation'] ?? 0);
                    $privacy += floatval($scores[$statusKey]['privacy'] ?? 0);
                }
            }
        }

        // System-generated: Ownership verified
        $ownershipField = $categoryFields->firstWhere('key', 'ownership_verified');
        if ($ownershipField) {
            $globalScores = $ownershipField->option_scores ?? [];
            $pivotScoresRaw = $ownershipField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $ownerKey = ($this->ownership_verified || $this->getDynamicField('ownership_verified') === 'Yes') ? 'Yes' : 'No';
                if (isset($scores[$ownerKey])) {
                    $reputation += floatval($scores[$ownerKey]['reputation'] ?? 0);
                    $privacy += floatval($scores[$ownerKey]['privacy'] ?? 0);
                }
            }
        }

        // System-generated: User positive feedback numbers
        $posReviewField = $categoryFields->firstWhere('key', 'positive_review_scoring');
        if ($posReviewField) {
            $globalScores = $posReviewField->option_scores ?? [];
            $pivotScoresRaw = $posReviewField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $posCount = $this->positive_count ?? 0;
                $bracket = $this->getReviewBracket($posCount);
                if (isset($scores[$bracket])) {
                    $reputation += floatval($scores[$bracket]['reputation'] ?? 0);
                    $privacy += floatval($scores[$bracket]['privacy'] ?? 0);
                }
            }
        }

        // System-generated: User neutral feedback numbers
        $neuReviewField = $categoryFields->firstWhere('key', 'neutral_review_scoring');
        if ($neuReviewField) {
            $globalScores = $neuReviewField->option_scores ?? [];
            $pivotScoresRaw = $neuReviewField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $neuCount = $this->neutral_count ?? 0;
                $bracket = $this->getReviewBracket($neuCount);
                if (isset($scores[$bracket])) {
                    $reputation += floatval($scores[$bracket]['reputation'] ?? 0);
                    $privacy += floatval($scores[$bracket]['privacy'] ?? 0);
                }
            }
        }

        // System-generated: User negative feedback numbers
        $negReviewField = $categoryFields->firstWhere('key', 'negative_review_scoring');
        if ($negReviewField) {
            $globalScores = $negReviewField->option_scores ?? [];
            $pivotScoresRaw = $negReviewField->pivot->option_scores ?? [];
            $pivotScores = is_string($pivotScoresRaw) ? json_decode($pivotScoresRaw, true) : ($pivotScoresRaw ?? []);
            if (!is_array($pivotScores))
                $pivotScores = [];
            $scores = array_merge($globalScores, $pivotScores);

            if (!empty($scores)) {
                $negCount = $this->negative_count ?? 0;
                $bracket = $this->getReviewBracket($negCount);
                if (isset($scores[$bracket])) {
                    $reputation += floatval($scores[$bracket]['reputation'] ?? 0);
                    $privacy += floatval($scores[$bracket]['privacy'] ?? 0);
                }
            }
        }

        $this->reputation_score = round($reputation, 2);
        $this->privacy_score = round($privacy, 2);

        // Scaled WLC Score calculation (using dynamic maximums within the current category)
        $maxRep = self::getCategoryMaxReputation($this->category_id);
        $maxPriv = self::getCategoryMaxPrivacy($this->category_id);
        $maxTotal = $maxRep + $maxPriv;
        
        // Trust Score calculation: only scale positive contributions
        // If a project has net negative scores, its trust is 0.
        $totalRaw = max(0, $reputation + $privacy);
        $scaledWLC = ($maxTotal > 0) ? ($totalRaw / $maxTotal) * 10 : 0;

        // Clamp to 0-10 range to ensure DB constraints (decimal 5,2) and UI consistency
        $this->trust_score = round(max(0, min(10, $scaledWLC)), 2);
        $this->save();

        return ['reputation' => $reputation, 'privacy' => $privacy];
    }

    protected static $categoryMaxReputation = [];
    protected static $categoryMaxPrivacy = [];

    public static function getCategoryMaxReputation($categoryId)
    {
        if (!isset(self::$categoryMaxReputation[$categoryId])) {
            $max = self::where('category_id', $categoryId)->max('reputation_score');
            self::$categoryMaxReputation[$categoryId] = max(floatval($max), 1);
        }
        return self::$categoryMaxReputation[$categoryId];
    }

    public static function getCategoryMaxPrivacy($categoryId)
    {
        if (!isset(self::$categoryMaxPrivacy[$categoryId])) {
            $max = self::where('category_id', $categoryId)->max('privacy_score');
            self::$categoryMaxPrivacy[$categoryId] = max(floatval($max), 1);
        }
        return self::$categoryMaxPrivacy[$categoryId];
    }

    /**
     * Get review count bracket string for scoring lookup.
     */
    private function getReviewBracket(int $count): string
    {
        if ($count <= 2)
            return '0 to 2';
        if ($count <= 5)
            return '0 to 5';
        if ($count <= 10)
            return '0 to 10';
        if ($count <= 20)
            return '0 to 20';
        return '20+';
    }

    /**
     * Convert a launch date into an age bracket label for scoring.
     */
    private function calculateAgeBracket(?string $date): string
    {
        if (!$date) {
            return 'Age 0-1year';
        }

        try {
            $launchDate = \Illuminate\Support\Carbon::parse($date);
            $years = $launchDate->diffInYears(now());

            if ($years >= 5)
                return 'Age 5 and over';
            if ($years >= 4)
                return 'Age 4+ year';
            if ($years >= 3)
                return 'Age 3+ year';
            if ($years >= 2)
                return 'Age 2+ year';
            if ($years >= 1)
                return 'Age 1+ year';
            return 'Age 0-1year';
        } catch (\Exception $e) {
            return 'Age 0-1year';
        }
    }

    /**
     * Get the online status for a specific website URL.
     */
    public function getUrlStatus($url): string
    {
        $statuses = $this->url_statuses ?? [];
        return $statuses[$url] ?? 'offline'; // Default to offline if not found
    }

    /**
     * Generate conditional insights based on external criteria map.
     */
    public function getConditionalInsights(): array
    {
        $insights = [];
        $projectName = $this->name;
        $wlcScore = floatval($this->trust_score);

        // 1. Website URL
        $urls = $this->website_urls;
        if (count($urls) === 1) {
            $host = parse_url($urls[0], PHP_URL_HOST) ?: $urls[0];
            $tld = pathinfo($host, PATHINFO_EXTENSION);
            if (!$tld && str_contains($urls[0], '.onion'))
                $tld = 'onion';
            $insights[] = [
                'type' => 'info',
                'message' => "{$projectName} domain TLD is .{$tld}. Beware of phishing sites."
            ];
        } elseif (count($urls) > 1) {
            $insights[] = [
                'type' => 'warning',
                'message' => "{$projectName} has more than one clearnet domain. Please beware of phishing sites."
            ];
        }

        // 2. Have TOR
        if ($this->getDynamicField('have_tor') === 'Yes') {
            $insights[] = [
                'type' => 'success',
                'message' => "Onion available. You can use TOR browser for better privacy."
            ];
        }

        // 3. Age & WLC Score
        $launchDate = $this->getDynamicField('launch_date');
        $ageBracket = $this->calculateAgeBracket($launchDate);
        $ageYears = 0;
        if ($launchDate) {
            try {
                $ageYears = \Illuminate\Support\Carbon::parse($launchDate)->diffInYears(now());
            } catch (\Exception $e) {
            }
        }

        if ($ageYears < 1) {
            if ($wlcScore >= 7) {
                $insights[] = ['type' => 'success', 'message' => "Although the business is in operation for not long ago but {$projectName} is maintaining a good WLC score"];
            } else {
                $insights[] = ['type' => 'info', 'message' => "The business is in operation for not long ago, please do your own due diligence."];
            }
        } elseif ($ageYears >= 5) {
            if ($wlcScore >= 7) {
                $insights[] = ['type' => 'success', 'message' => "Business is in operation for over FIVE YEARS with a good WLC score. The management is proven"];
            } else {
                $insights[] = ['type' => 'warning', 'message' => "Business is in operation for over FIVE YEARS but seems like the WLC score is still not up to the mark. There are still a lot of scopes to improve the service."];
            }
        } else {
            $yearWord = match (true) {
                $ageYears >= 4 => 'FOUR YEARS',
                $ageYears >= 3 => 'THREE YEARS',
                $ageYears >= 2 => 'TWO YEARS',
                default => 'A YEAR'
            };
            if ($wlcScore >= 7) {
                $insights[] = ['type' => 'success', 'message' => "Business is in operation for over {$yearWord} with a good WLC score."];
            } else {
                $insights[] = ['type' => 'warning', 'message' => "Business is in operation for over {$yearWord} but seems like the WLC score is " . ($ageYears >= 1 ? "not as good as expected" : "still not up to the mark.")];
            }
        }

        // 4. Reputation Score
        $rep = floatval($this->reputation_score);
        $maxRep = self::getCategoryMaxReputation($this->category_id);
        $scaledRep = ($maxRep > 0) ? ($rep / $maxRep) * 10 : 0;

        if ($scaledRep < 4) {
            $insights[] = ['type' => 'danger', 'message' => "Overall Business reputation score is BAD. Due deligence advised."];
        } elseif ($scaledRep >= 4 && $scaledRep < 6) {
            $insights[] = ['type' => 'warning', 'message' => "Overall Business reputation score is MODERATED nothing to get excited."];
        } elseif ($scaledRep >= 6 && $scaledRep < 8) {
            $insights[] = ['type' => 'success', 'message' => "Overall Business reputation score is GOOD."];
        } elseif ($scaledRep >= 8) {
            $insights[] = ['type' => 'success', 'message' => "Maintaining AN EXCELLENT Business reputation."];
        }

        // 5. Privacy
        $priv = floatval($this->privacy_score);
        $maxPriv = self::getCategoryMaxPrivacy($this->category_id);
        $scaledPriv = ($maxPriv > 0) ? ($priv / $maxPriv) * 10 : 0;

        if ($scaledPriv < 4) {
            $insights[] = ['type' => 'danger', 'message' => "NOT A GOOD privacy featured platform"];
        } elseif ($scaledPriv >= 4 && $scaledPriv < 6) {
            $insights[] = ['type' => 'warning', 'message' => "Has MODERATED privacy elements"];
        } elseif ($scaledPriv >= 6 && $scaledPriv < 8) {
            $insights[] = ['type' => 'success', 'message' => "Privacy elements are GOOD"];
        } elseif ($scaledPriv >= 8) {
            $insights[] = ['type' => 'success', 'message' => "AN EXCELLENT privacy featured platform."];
        }

        // 6. Feedback
        if ($this->positive_count == 1) {
            $insights[] = ['type' => 'success', 'message' => "We have recorded a POSITIVE experience for {$projectName}. This is a good start."];
        } elseif ($this->positive_count > 1) {
            $insights[] = ['type' => 'success', 'message' => "WLC has recorded more than one POSITIVE experience for {$projectName}."];
        }
        if ($this->negative_count >= 1) {
            $insights[] = ['type' => 'danger', 'message' => "There are one or more Negative Experience has recorded for {$projectName}"];
        }
        if ($this->positive_count == 0 && $this->negative_count == 0 && $this->neutral_count == 0) {
            $insights[] = ['type' => 'info', 'message' => "Still NO FEEDBACK recorded for {$projectName}"];
        }

        // 7. Customer Support Rating
        $supportRating = floatval($this->getDynamicField('customer_support_rating'));
        if ($supportRating > 0) {
            if ($supportRating <= 1) {
                $insights[] = ['type' => 'danger', 'message' => "BAD or NO customer support available."];
            } elseif ($supportRating <= 3) {
                $insights[] = ['type' => 'warning', 'message' => "Customer support is not so good. {$projectName} must need to work in it."];
            } elseif ($supportRating == 4) {
                $insights[] = ['type' => 'success', 'message' => "GOOD customer support for platform users"];
            } elseif ($supportRating == 5) {
                $insights[] = ['type' => 'success', 'message' => "EXCELLENT customer support is available for {$projectName} users."];
            }
        }

        // 8. Community
        $communityField = $this->getDynamicField('community');
        $communityLinks = $communityField ? array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $communityField)))) : [];
        $commCount = count($communityLinks);
        if ($commCount < 1) {
            $insights[] = ['type' => 'info', 'message' => "No public community has found so far."];
        } elseif ($commCount == 1) {
            $host = parse_url($communityLinks[0], PHP_URL_HOST) ?: $communityLinks[0];
            $domain = preg_replace('/^www\./', '', $host);
            $insights[] = ['type' => 'success', 'message' => "Only {$domain} exists so far."];
        } else {
            $insights[] = ['type' => 'success', 'message' => "More than one community exists."];
        }

        // 9. Guarantee Fund (Escrow)
        $escrow = $this->getDynamicField('guarantee_fund');
        if (!$escrow || stripos($escrow, 'no escrow') !== false) {
            if ($ageYears < 1) {
                $insights[] = ['type' => 'info', 'message' => "NO ESCROW information available."];
            } else {
                $insights[] = ['type' => 'info', 'message' => "NO ESCROW information available however the business is in operation for long time is a good sign."];
            }
        } else {
            if (stripos($escrow, 'under $10k') !== false) {
                $insights[] = ['type' => 'info', 'message' => "A SMALL SUM of ESCROW secured to guarantee the service"];
            } elseif (stripos($escrow, 'between $10k to $20k') !== false) {
                $insights[] = ['type' => 'success', 'message' => "There is A GOOD SUM of ESCROW secured to guarantee the service"];
            } elseif (stripos($escrow, 'above $20 to $50k') !== false || stripos($escrow, '$20k to $50k') !== false) {
                $insights[] = ['type' => 'success', 'message' => "There is A LARGE SUM of ESCROW secured to guarantee the service"];
            } elseif (stripos($escrow, 'above $50k') !== false) {
                $insights[] = ['type' => 'success', 'message' => "There is A VERY LARGE SUM of ESCROW secured to guarantee the service"];
            }
        }

        // 10. Privacy KYC
        $kyc = $this->getDynamicField('privacy_kyc');
        if ($kyc) {
            if (stripos($kyc, 'KYC 0') !== false) {
                $insights[] = ['type' => 'success', 'message' => "{$projectName} is a Guaranteed NO KYC. They will never ask for KYC. Report if ever asked for a KYC."];
            } elseif (stripos($kyc, 'KYC 1') !== false) {
                $insights[] = ['type' => 'info', 'message' => "There are no clause about KYC found on {$projectName} terms page. The service may or may not ask for KYC"];
            } elseif (stripos($kyc, 'KYC 2') !== false) {
                $insights[] = ['type' => 'warning', 'message' => "{$projectName} may ask for KYC when requested by authorities. Primarily it's a NO KYC service however they may request a KYC if authority demands."];
            } elseif (stripos($kyc, 'KYC 3') !== false) {
                $insights[] = ['type' => 'danger', 'message' => "{$projectName} has a very strict KYC policy. Failed to comply with KYC may result blocking funds."];
            }
        }

        // 11. Own Liquidity
        if ($this->getDynamicField('own_liquidity_field') === 'Yes') {
            $insights[] = ['type' => 'success', 'message' => "{$projectName} has their own liquidity."];
        }

        return $insights;
    }
}
