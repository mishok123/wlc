<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Field;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Map PDF Column Headers to Actual Database Category Slugs
        $columnToSlug = [
            'Mixer' => 'mixers',
            'CEX' => 'exchanges',
            'DEX' => 'exchanges',
            'Instant Exchange and aggregator' => 'exchanges',
            'Sportsbook' => 'casinos-sportsbooks',
            'Casino' => 'casinos-sportsbooks',
            'Wallets' => 'wallets',
            'Merchant' => 'cards-payments',
            'Crypto Cards' => 'cards-payments',
        ];

        // Fetch all relevant categories
        $categories = Category::whereIn('slug', array_unique(array_values($columnToSlug)))->get()->keyBy('slug');

        // 2. Define Fields with option_scores for Reputation/Privacy scoring
        $fields = [
            // ===== USER-SUBMITTED FIELDS (appear on submit form) =====

            // #1 Age related data (datetime) - user enters launch date, system calculates age bracket
            [
                'name' => 'Age',
                'key' => 'age',
                'type' => 'date',
                'option_scores' => [
                    'Age 0-1year' => ['reputation' => 35, 'privacy' => 0],
                    'Age 1+ year' => ['reputation' => 50, 'privacy' => 0],
                    'Age 2+ year' => ['reputation' => 60, 'privacy' => 20],
                    'Age 3+ year' => ['reputation' => 80, 'privacy' => 0],
                    'Age 4+ year' => ['reputation' => 90, 'privacy' => 0],
                    'Age 5 and over' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            [
                'name' => 'Launch Date',
                'key' => 'launch_date',
                'type' => 'date',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #2 Community related data (Textarea - one URL per line)
            [
                'name' => 'Community',
                'key' => 'community',
                'type' => 'textarea',
                'option_scores' => [
                    'Community Bitcointalk' => ['reputation' => 35, 'privacy' => 0],
                    'Altcoinstalks' => ['reputation' => 35, 'privacy' => 0],
                    'Community WLC' => ['reputation' => 35, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #3 Guarantee fund related data (Radio button)
            [
                'name' => 'Guarantee Fund',
                'key' => 'guarantee_fund',
                'type' => 'select',
                'options' => ['No escrow', 'Escrow under $10k', 'Escrow between $10k to $20k', 'Escrow above $20 to $50k', 'Escrow above $50k'],
                'option_scores' => [
                    'No escrow' => ['reputation' => 0, 'privacy' => 0],
                    'Escrow under $10k' => ['reputation' => 35, 'privacy' => 0],
                    'Escrow between $10k to $20k' => ['reputation' => 60, 'privacy' => 0],
                    'Escrow above $20 to $50k' => ['reputation' => 80, 'privacy' => 0],
                    'Escrow above $50k' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #3b Privacy related data (Radio button)
            [
                'name' => 'Privacy KYC',
                'key' => 'privacy_kyc',
                'type' => 'select',
                'options' => ['Privacy KYC 0 (Guaranteed No KYC)', 'Privacy KYC 1', 'Privacy KYC 2', 'Privacy KYC 3'],
                'option_scores' => [
                    'Privacy KYC 0 (Guaranteed No KYC)' => ['reputation' => 0, 'privacy' => 100],
                    'Privacy KYC 1' => ['reputation' => 0, 'privacy' => 35],
                    'Privacy KYC 2' => ['reputation' => 0, 'privacy' => 10],
                    'Privacy KYC 3' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #4 FEE related data (Radio button)
            [
                'name' => 'FEE',
                'key' => 'fee',
                'type' => 'select',
                'options' => ['High fees', 'Medium fees', 'Low fees', 'Ultra low fees'],
                'option_scores' => [
                    'High fees' => ['reputation' => 0, 'privacy' => 0],
                    'Medium fees' => ['reputation' => 35, 'privacy' => 0],
                    'Low fees' => ['reputation' => 60, 'privacy' => 0],
                    'Ultra low fees' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #5 Withdrawal or receive related Delay (Radio button)
            [
                'name' => 'Withdrawal/Receive Delay',
                'key' => 'withdrawal_delay',
                'type' => 'select',
                'options' => ['High delay', 'Medium delay', 'Low delay', 'Instant max 5 min'],
                'option_scores' => [
                    'High delay' => ['reputation' => 0, 'privacy' => 0],
                    'Medium delay' => ['reputation' => 35, 'privacy' => 0],
                    'Low delay' => ['reputation' => 60, 'privacy' => 0],
                    'Instant max 5 min' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'DEX', 'Instant Exchange and aggregator'],
            ],

            // #6 LOG related verifiable feature (Select)
            [
                'name' => 'LOG Verifiable Feature',
                'key' => 'log_verifiable',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 100, 'privacy' => 0],
                    'No' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'Instant Exchange and aggregator'],
            ],

            // #11 Have Tor (select)
            [
                'name' => 'Have Tor',
                'key' => 'have_tor',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 0, 'privacy' => 100],
                    'No' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #12 Have no reg policy (select)
            [
                'name' => 'No Registration Policy',
                'key' => 'no_reg_policy',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 0, 'privacy' => 100],
                    'No' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Crypto Cards'],
            ],

            // #13 Have no Log policy (select)
            [
                'name' => 'No Log Policy',
                'key' => 'no_log_policy_field',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 0, 'privacy' => 100],
                    'No' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Crypto Cards'],
            ],

            // #14 Own Liquidity/Bankroll (select)
            [
                'name' => 'Own Liquidity/Bankroll',
                'key' => 'own_liquidity_field',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 100, 'privacy' => 0],
                    'No' => ['reputation' => 50, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino'],
            ],

            // #15 Good Customer Support average rating (System Generated via User Reviews)
            [
                'name' => 'Good Customer Support Average Rating',
                'key' => 'customer_support_rating',
                'type' => 'system',
                'options' => ['0 star', '1 star', '2 star', '3 star', '4 star', '5 star'],
                'option_scores' => [
                    '0 star' => ['reputation' => 0, 'privacy' => 0],
                    '1 star' => ['reputation' => 20, 'privacy' => 0],
                    '2 star' => ['reputation' => 35, 'privacy' => 0],
                    '3 star' => ['reputation' => 50, 'privacy' => 0],
                    '4 star' => ['reputation' => 65, 'privacy' => 0],
                    '5 star' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #16 Code audited by third party (radio)
            [
                'name' => 'Code Audited by Third Party',
                'key' => 'code_audited',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 100, 'privacy' => 0],
                    'No' => ['reputation' => 50, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #17 Phone number is required (radio)
            [
                'name' => 'Phone Number Required',
                'key' => 'phone_required',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 0, 'privacy' => -20],
                    'No' => ['reputation' => 0, 'privacy' => 100],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #19 Legally registered (radio)
            [
                'name' => 'Legally Registered',
                'key' => 'legally_registered',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => 100, 'privacy' => 0],
                    'No' => ['reputation' => 0, 'privacy' => 100],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // ===== SYSTEM-GENERATED FIELDS (NOT on submit form, scores only) =====

            // #7 User Neutral review numbers (System Generated)
            [
                'name' => 'Neutral Review Scoring',
                'key' => 'neutral_review_scoring',
                'type' => 'system',
                'option_scores' => [
                    '0 to 2' => ['reputation' => 35, 'privacy' => 0],
                    '0 to 5' => ['reputation' => 50, 'privacy' => 0],
                    '0 to 10' => ['reputation' => 65, 'privacy' => 0],
                    '0 to 20' => ['reputation' => 80, 'privacy' => 0],
                    '20+' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #8 User Positive review numbers (System Generated)
            [
                'name' => 'Positive Review Scoring',
                'key' => 'positive_review_scoring',
                'type' => 'system',
                'option_scores' => [
                    '0 to 2' => ['reputation' => 35, 'privacy' => 0],
                    '0 to 5' => ['reputation' => 50, 'privacy' => 0],
                    '0 to 10' => ['reputation' => 65, 'privacy' => 0],
                    '0 to 20' => ['reputation' => 80, 'privacy' => 0],
                    '20+' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #9 (was #8 in image) - maps to Negative review numbers (System Generated)
            [
                'name' => 'Negative Review Scoring',
                'key' => 'negative_review_scoring',
                'type' => 'system',
                'option_scores' => [
                    '0 to 2' => ['reputation' => 100, 'privacy' => 0],
                    '0 to 5' => ['reputation' => 65, 'privacy' => 0],
                    '0 to 10' => ['reputation' => 35, 'privacy' => 0],
                    '0 to 20' => ['reputation' => 20, 'privacy' => 0],
                    '20+' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // #9 List status related scoring (System Generated at first will input by admin)
            [
                'name' => 'List Status Scoring',
                'key' => 'list_status_scoring',
                'type' => 'system',
                'option_scores' => [
                    'Scam' => ['reputation' => -2050, 'privacy' => 0],
                    'Approved' => ['reputation' => 70, 'privacy' => 0],
                    'Pending' => ['reputation' => 0, 'privacy' => 0],
                    'Rejected' => ['reputation' => 0, 'privacy' => 0],
                    'Verified' => ['reputation' => 100, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],


            // ===== ADMIN-ONLY FIELD =====

            // #18 Potential risk (select, only for admin)
            [
                'name' => 'Potential Risk',
                'key' => 'potential_risk',
                'type' => 'select',
                'options' => ['Yes', 'No'],
                'option_scores' => [
                    'Yes' => ['reputation' => -666.6666667, 'privacy' => -666.6666667],
                    'No' => ['reputation' => 0, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],

            // ===== LEGACY FIELDS (kept for backward compatibility) =====
            [
                'name' => 'Online Status',
                'key' => 'online_status',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Cryptocurrency',
                'key' => 'cryptocurrency',
                'type' => 'textarea',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Privacy',
                'key' => 'privacy',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'AML',
                'key' => 'aml',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Fixed FEE',
                'key' => 'fixed_fee',
                'type' => 'text',
                'industries' => ['Mixer', 'DEX', 'Instant Exchange and aggregator', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Withdrawal FEE',
                'key' => 'withdrawal_fee',
                'type' => 'text',
                'industries' => ['CEX', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Time Delay',
                'key' => 'time_delay',
                'type' => 'text',
                'industries' => ['Mixer', 'DEX', 'Instant Exchange and aggregator'],
            ],
            [
                'name' => 'Log',
                'key' => 'log',
                'type' => 'text',
                'industries' => ['Mixer', 'Instant Exchange and aggregator'],
            ],
            [
                'name' => 'User review',
                'key' => 'user_review',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Star Rating',
                'key' => 'star_rating',
                'type' => 'number',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'List status',
                'key' => 'list_status',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Ownership verified',
                'key' => 'ownership_verified',
                'type' => 'checkbox',
                'option_scores' => [
                    'Yes' => ['reputation' => 100, 'privacy' => 0],
                    'No' => ['reputation' => 50, 'privacy' => 0],
                ],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'TOR',
                'key' => 'tor',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'I2P',
                'key' => 'i2p',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Code Source',
                'key' => 'code_source',
                'type' => 'select',
                'options' => ['Open Source', 'Closed Source'],
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Asset Custody',
                'key' => 'asset_custody',
                'type' => 'text',
                'industries' => ['Wallets'],
            ],
            [
                'name' => 'BOT',
                'key' => 'bot',
                'type' => 'checkbox',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'API',
                'key' => 'api',
                'type' => 'checkbox',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
            [
                'name' => 'Supported Coin',
                'key' => 'supported_coin',
                'type' => 'text',
                'industries' => ['Mixer', 'CEX', 'DEX', 'Instant Exchange and aggregator', 'Sportsbook', 'Casino', 'Wallets', 'Merchant', 'Crypto Cards'],
            ],
        ];

        foreach ($fields as $fieldData) {
            $industries = $fieldData['industries'];
            unset($fieldData['industries']);

            // Create Field
            $field = Field::updateOrCreate(
                ['key' => $fieldData['key']],
                $fieldData
            );

            // Assign to Categories
            foreach ($industries as $columnName) {
                $targetSlug = $columnToSlug[$columnName] ?? null;

                if ($targetSlug && isset($categories[$targetSlug])) {
                    $category = $categories[$targetSlug];
                    // Check if already attached
                    if (!$category->fields()->where('field_id', $field->id)->exists()) {
                        $category->fields()->attach($field->id, ['is_visible_in_card' => true, 'order' => 0]);
                    }
                }
            }
        }
    }
}
