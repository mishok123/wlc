<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraphs(3, true),
            'website_url' => $this->faker->url,
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id ?? 1,
            'industry' => 'Crypto',
            'online_status' => $this->faker->randomElement(['online', 'offline']),
            'star_rating' => $this->faker->randomFloat(2, 1, 5),
            'review_count' => $this->faker->numberBetween(0, 500),
            'trust_score' => $this->faker->numberBetween(10, 100),
            'list_status' => $this->faker->randomElement(['proposed', 'approved', 'verified']),
            'supported_cryptos' => ['BTC', 'ETH', 'XMR', 'USDT'],
            'supported_networks' => ['Clear Net', 'TOR'],
            'features' => ['No KYC', 'Instant Payouts'],
            'privacy_level' => 'No KYC',
        ];
    }
}
