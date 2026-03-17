<?php

namespace Database\Seeders;

use App\Models\Cryptocurrency;
use App\Models\PriceAlert;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cryptos = Cryptocurrency::factory(10)->create();

        User::factory(100)->create()->each(function ($user) use ($cryptos) {
            PriceAlert::factory(5)->create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptos->random()->id
            ]);
        });

        $testUser = User::factory()->create([
            'name' => 'Kamil Admin',
            'email' => 'admin@coinwatch.com',
        ]);

        PriceAlert::factory(5)->create([
            'user_id' => $testUser->id,
            'cryptocurrency_id' => $cryptos->random()->id
        ]);

        $token = $testUser->createToken('test-token')->plainTextToken;

        $this->command->info('Database seeded successfully with 100 Users and 500 Alerts!');
        $this->command->info('--- YOUR TEST USER ---');
        $this->command->info('Email: admin@coinwatch.com');
        $this->command->info('Password: password');
        $this->command->info('API Token: ' . $token);
    }
}
