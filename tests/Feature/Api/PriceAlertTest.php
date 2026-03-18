<?php

use App\Models\Cryptocurrency;
use App\Models\PriceAlert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows user to fetch their paginated price alerts', function () {
   $user = User::factory()->create();
   $crypto = Cryptocurrency::factory()->create();

   PriceAlert::factory(3)->create([
       'user_id' => $user->id,
       'cryptocurrency_id' => $crypto->id,
   ]);

   Sanctum::actingAs($user);

   $response = $this->getJson('/api/alerts');

   $response->assertStatus(200)
       ->assertJsonCount(3, 'data')
       ->assertJsonStructure([
           'data' => [
               '*' => [
                   'id',
                   'crypto_name',
                   'crypto_symbol',
                   'target_price',
                   'direction',
                   'is_triggered',
                   'created_at'
               ]
           ],
           'meta',
           'links'
       ]);
});

it('prevents unauthenticated user from accessing alerts', function () {
    $response = $this->getJson('/api/alerts');
    $response->assertStatus(401);
});

it('allows user to create a price alert', function () {
    $user = User::factory()->create();
    $crypto = Cryptocurrency::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/alerts', [
        'cryptocurrency_id' => $crypto->id,
        'target_price' => 50000.00,
        'direction' => 'above',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('price_alerts', [
        'user_id' => $user->id,
        'cryptocurrency_id' => $crypto->id,
        'target_price' => 50000.00,
        'direction' => 'above',
        'is_triggered' => false,
    ]);
});

it('allows user to delete their own alert', function () {
   $user = User::factory()->create();
   $crypto = Cryptocurrency::factory()->create();

   $alert = PriceAlert::factory()->create([
       'user_id' => $user->id,
       'cryptocurrency_id' => $crypto->id,
   ]);

   Sanctum::actingAs($user);

   $response = $this->deleteJson('/api/alerts/' . $alert->id);

   $response->assertStatus(200)
       ->assertJson(['message' => 'Alert deleted successfully.']);
});

it('prevents user from deleting someone elses alerts', function () {
    $victimUser = User::factory()->create();
    $crypto = Cryptocurrency::factory()->create();
    $victimAlert = PriceAlert::factory()->create([
        'user_id' => $victimUser->id,
        'cryptocurrency_id' => $crypto->id,
    ]);

    $attackerUser = User::factory()->create();
    Sanctum::actingAs($attackerUser);

    $response = $this->deleteJson('/api/alerts/' . $victimAlert->id);

    $response->assertStatus(403);

    $this->assertDatabaseHas('price_alerts', [
        'id' => $victimAlert->id,
    ]);
});
