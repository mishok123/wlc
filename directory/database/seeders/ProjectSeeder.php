<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Category;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure mixer category exists
        $mixerCat = Category::where('slug', 'mixers')->first();
        
        if (!$mixerCat) {
            return;
        }

        $mixers = [
            [
                'name' => 'Whale Mixer',
                'slug' => 'whale-mixer',
                'description' => 'A high-volume mixer for large transactions with zero logging and advanced privacy features.',
                'star_rating' => 4.9,
                'trust_score' => 98,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['BTC', 'ETH', 'XMR'],
                'supported_networks' => ['TOR', 'Clear Net'],
                'fields' => [
                    'age' => 'Age 5 and over',
                    'guarantee_fund' => 'Escrow above $50k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Low fees',
                    'withdrawal_delay' => 'Instant max 5 min',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Shadow Tumble',
                'slug' => 'shadow-tumble',
                'description' => 'Fast and reliable mixer focusing on speed and low withdrawal delays.',
                'star_rating' => 4.5,
                'trust_score' => 92,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['BTC', 'LTC'],
                'supported_networks' => ['TOR'],
                'fields' => [
                    'age' => 'Age 3+ year',
                    'guarantee_fund' => 'Escrow between $10k to $20k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Ultra low fees',
                    'withdrawal_delay' => 'Instant max 5 min',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Stealth Mix',
                'slug' => 'stealth-mix',
                'description' => 'Privacy-focused mixer with a multi-hop architecture to ensure maximum anonymity.',
                'star_rating' => 4.7,
                'trust_score' => 95,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['BTC', 'ETH', 'USDT'],
                'supported_networks' => ['Clear Net', 'TOR', 'I2P'],
                'fields' => [
                    'age' => 'Age 2+ year',
                    'guarantee_fund' => 'Escrow above $20 to $50k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Medium fees',
                    'withdrawal_delay' => 'Low delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Quick Blend',
                'slug' => 'quick-blend',
                'description' => 'The fastest mixer in the market, designed for small to medium transactions.',
                'star_rating' => 4.2,
                'trust_score' => 88,
                'privacy_level' => 'Optional KYC',
                'supported_cryptos' => ['BTC', 'SOL', 'DOT'],
                'supported_networks' => ['Clear Net'],
                'fields' => [
                    'age' => 'Age 1+ year',
                    'guarantee_fund' => 'No escrow',
                    'privacy_kyc' => 'Privacy KYC 1',
                    'fee' => 'Low fees',
                    'withdrawal_delay' => 'Instant max 5 min',
                    'log_verifiable' => 'No',
                    'have_tor' => 'No',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Deep Dive Mixer',
                'slug' => 'deep-dive-mixer',
                'description' => 'Specialized in Monero and other privacy coins, providing deep liquidity and untraceable transactions.',
                'star_rating' => 4.8,
                'trust_score' => 97,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['XMR', 'ZEC', 'DASH'],
                'supported_networks' => ['TOR', 'I2P'],
                'fields' => [
                    'age' => 'Age 4+ year',
                    'guarantee_fund' => 'Escrow above $50k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Medium fees',
                    'withdrawal_delay' => 'Low delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Nexus Tumbler',
                'slug' => 'nexus-tumbler',
                'description' => 'A decentralized mixing protocol that leverages smart contracts for transparency and security.',
                'star_rating' => 4.6,
                'trust_score' => 94,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['ETH', 'BNB', 'AVAX'],
                'supported_networks' => ['Clear Net', 'Web3'],
                'fields' => [
                    'age' => 'Age 2+ year',
                    'guarantee_fund' => 'Escrow between $10k to $20k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Ultra low fees',
                    'withdrawal_delay' => 'Medium delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'No',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Ghost Blend',
                'slug' => 'ghost-blend',
                'description' => 'Operates entirely on the dark web, offering the highest level of privacy for sensitive transactions.',
                'star_rating' => 4.9,
                'trust_score' => 99,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['BTC', 'XMR', 'LTC'],
                'supported_networks' => ['TOR'],
                'fields' => [
                    'age' => 'Age 5 and over',
                    'guarantee_fund' => 'Escrow above $50k',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'High fees',
                    'withdrawal_delay' => 'High delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Crypto Wash',
                'slug' => 'crypto-wash',
                'description' => 'Simple and intuitive interface for quick crypto mixing with no account required.',
                'star_rating' => 4.1,
                'trust_score' => 85,
                'privacy_level' => 'No KYC',
                'supported_cryptos' => ['BTC', 'ETH'],
                'supported_networks' => ['Clear Net'],
                'fields' => [
                    'age' => 'Age 0-1year',
                    'guarantee_fund' => 'No escrow',
                    'privacy_kyc' => 'Privacy KYC 0 (Guaranteed No KYC)',
                    'fee' => 'Low fees',
                    'withdrawal_delay' => 'Low delay',
                    'log_verifiable' => 'No',
                    'have_tor' => 'No',
                    'no_reg_policy' => 'Yes',
                    'no_log_policy_field' => 'Yes',
                ]
            ],
            [
                'name' => 'Infinity Mixer',
                'slug' => 'infinity-mixer',
                'description' => 'A versatile mixer supporting hundreds of altcoins with competitive fees.',
                'star_rating' => 4.4,
                'trust_score' => 90,
                'privacy_level' => 'Optional KYC',
                'supported_cryptos' => ['BTC', 'ETH', 'ADA', 'XRP', 'LINK'],
                'supported_networks' => ['Clear Net', 'TOR'],
                'fields' => [
                    'age' => 'Age 3+ year',
                    'guarantee_fund' => 'Escrow under $10k',
                    'privacy_kyc' => 'Privacy KYC 2',
                    'fee' => 'Medium fees',
                    'withdrawal_delay' => 'Medium delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'Yes',
                    'no_reg_policy' => 'No',
                    'no_log_policy_field' => 'No',
                ]
            ],
            [
                'name' => 'Aegis Tumbler',
                'slug' => 'aegis-tumbler',
                'description' => 'Focused on enterprise-level security and compliance, offering audited mixing services.',
                'star_rating' => 4.3,
                'trust_score' => 89,
                'privacy_level' => 'KYC Required',
                'supported_cryptos' => ['BTC', 'ETH', 'USDC'],
                'supported_networks' => ['Clear Net'],
                'fields' => [
                    'age' => 'Age 4+ year',
                    'guarantee_fund' => 'Escrow above $50k',
                    'privacy_kyc' => 'Privacy KYC 3',
                    'fee' => 'High fees',
                    'withdrawal_delay' => 'Low delay',
                    'log_verifiable' => 'Yes',
                    'have_tor' => 'No',
                    'no_reg_policy' => 'No',
                    'no_log_policy_field' => 'No',
                ]
            ],
        ];

        foreach ($mixers as $mixerData) {
            $fieldsValues = $mixerData['fields'];
            unset($mixerData['fields']);
            
            $mixerData['category_id'] = $mixerCat->id;
            $mixerData['industry'] = 'Privacy';
            $mixerData['online_status'] = 'online';
            $mixerData['list_status'] = 'verified';
            $mixerData['review_count'] = rand(10, 500);

            $project = Project::updateOrCreate(
                ['slug' => $mixerData['slug']],
                $mixerData
            );

            $this->seedMixerFields($project, $fieldsValues);
        }
    }

    private function seedMixerFields(Project $project, array $fieldsData): void
    {
        $fieldsMap = \App\Models\Field::whereIn('key', array_keys($fieldsData))->get()->keyBy('key');

        foreach ($fieldsData as $key => $val) {
            if (isset($fieldsMap[$key])) {
                \App\Models\ProjectFieldValue::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'field_id'   => $fieldsMap[$key]->id,
                    ],
                    ['value' => $val]
                );
            }
        }
        
        $project->calculateScores();
    }
}
