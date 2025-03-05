<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Edition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_reservation()
    {
        $user = User::factory()->create();
        $edition = Edition::factory()->create(['available' => true]);

        $response = $this->actingAs($user)->postJson('/api/reservations', [
            'user_id'    => $user->id,
            'edition_id' => $edition->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reservations', ['user_id' => $user->id]);
    }
}
