<?php

namespace Tests\Feature;

use App\Models\Truck;
use App\Models\TruckSubunit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TruckSubunitServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_subunit(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        $data = [
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ];

        $response = $this->postJson('/api/truck-subunits', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('truck_subunits', $data);
    }

    #[Test]
    public function it_cannot_assign_the_same_truck_as_its_own_subunit(): void
    {
        $mainTruck = Truck::factory()->create();

        $data = [
            'main_truck' => $mainTruck->id,
            'subunit' => $mainTruck->id, // Same truck
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ];

        // Expect an error when trying to assign the same truck as a subunit
        $response = $this->postJson('/api/truck-subunits', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subunit');
    }

    #[Test]
    public function it_throws_an_exception_if_the_dates_overlap_for_the_main_truck(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ]);

        $data = [
            'main_truck' => $mainTruck->id,
            'subunit' => Truck::factory()->create()->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-15',
        ];

        // Expect exception when the dates overlap for the main truck
        $response = $this->postJson('/api/truck-subunits', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subunit');
    }

    #[Test]
    public function it_throws_an_exception_if_the_dates_overlap_for_the_subunit_truck(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ]);

        $data = [
            'main_truck' => Truck::factory()->create()->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-15',
        ];

        // expect exception due to overlapping dates
        $response = $this->postJson('/api/truck-subunits', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subunit');
    }

    #[Test]
    public function it_identifies_subunit_conflict_for_a_truck(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-10',
        ]);

        // Expect conflict due to overlapping date
        $response = $this->postJson('/api/truck-subunits', [
            'main_truck' => Truck::factory()->create()->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2023-04-05',
            'end_date' => '2023-04-08',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subunit');
    }

    #[Test]
    public function it_does_not_identify_subunit_conflict_outside_of_the_date_range(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-10',
        ]);

        // Assign a new subunit not overlapping date range
        $response = $this->postJson('/api/truck-subunits', [
            'main_truck' => Truck::factory()->create()->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2023-04-11',
            'end_date' => '2023-04-15',
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function it_cannot_assign_a_truck_as_a_main_truck_when_it_is_already_a_subunit(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ]);

        $data = [
            'main_truck' => $subunitTruck->id,
            'subunit' => Truck::factory()->create()->id,
            'start_date' => '2024-10-05',
            'end_date' => '2024-10-15',
        ];

        $response = $this->postJson('/api/truck-subunits', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('main_truck');
    }
}
