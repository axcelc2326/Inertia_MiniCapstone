<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Get any existing user (for foreign key)
        $user = User::first();

        // If there's no user yet, create one
        if (!$user) {
            $user = User::factory()->create();
        }

        // Create dummy items
        for ($i = 0; $i < 20; $i++) {
            Item::create([
                'name' => 'Lost Item ' . ($i + 1),
                'description' => 'Description for item ' . ($i + 1),
                'location' => 'Location ' . rand(1, 5),
                'status' => 'lost', // or 'found'
                'user_id' => $user->id,
                'slug' => Str::slug('Lost Item ' . ($i + 1) . '-' . Str::random(5)),
            ]);
        }
    }
}
