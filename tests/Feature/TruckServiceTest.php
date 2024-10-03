<?php

namespace Tests\Feature;

use App\Models\Truck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TruckServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_truck(): void
    {
        $data = [
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Test truck notes',
        ];

        $response = $this->postJson('/api/trucks', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('trucks', $data);
    }

    #[Test]
    public function it_can_get_all_trucks(): void
    {
        Truck::factory()->count(3)->create();

        $response = $this->getJson('/api/trucks');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    #[Test]
    public function it_can_update_a_truck(): void
    {
        $truck = Truck::factory()->create([
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Original notes',
        ]);

        $data = [
            'unit_number' => 'B5678',
            'year' => 2021,
            'notes' => 'Updated truck notes',
        ];

        $response = $this->putJson("/api/trucks/{$truck->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('trucks', $data);
    }

    #[Test]
    public function it_can_delete_a_truck(): void
    {
        $truck = Truck::factory()->create([
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Test truck notes',
        ]);

        $response = $this->deleteJson("/api/trucks/{$truck->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('trucks', [
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Test truck notes',
        ]);
    }
}
