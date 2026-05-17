<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Exchanges (CEX / DEX)', 'slug' => 'exchanges'],
            ['name' => 'Mixers', 'slug' => 'mixers'],
            ['name' => 'Wallets', 'slug' => 'wallets'],
            ['name' => 'Casinos & Sportsbooks', 'slug' => 'casinos-sportsbooks'],
            ['name' => 'Cards & Payment Services', 'slug' => 'cards-payments'],
            ['name' => 'DeFi Protocols', 'slug' => 'defi'],
            ['name' => 'NFT Marketplaces', 'slug' => 'nft-marketplaces'],
            ['name' => 'Analytics & Tools', 'slug' => 'analytics-tools'],
            ['name' => 'Trading Bots', 'slug' => 'trading-bots'],
            ['name' => 'Launchpads', 'slug' => 'launchpads'],
            ['name' => 'Other', 'slug' => 'other'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
