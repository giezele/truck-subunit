<?php

namespace Tests\Feature;

use App\Models\Truck;
use App\Services\TruckService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TruckServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TruckService $truckService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->truckService = new TruckService();
    }

    #[Test]
    public function it_can_create_a_truck(): void
    {
        $data = [
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Test truck notes',
        ];

        $truck = $this->truckService->createTruck($data);

        $this->assertInstanceOf(Truck::class, $truck);
        $this->assertDatabaseHas('trucks', $data);
    }

    #[Test]
    public function it_can_get_all_trucks(): void
    {
        Truck::factory()->count(3)->create();

        $trucks = $this->truckService->getAllTrucks();

        $this->assertCount(3, $trucks);
        $this->assertInstanceOf(Collection::class, $trucks);
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

        $updatedTruck = $this->truckService->updateTruck($truck, $data);

        $this->assertEquals('B5678', $updatedTruck->unit_number);
        $this->assertEquals(2021, $updatedTruck->year);
        $this->assertEquals('Updated truck notes', $updatedTruck->notes);
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

        $this->truckService->deleteTruck($truck);

        $this->assertDatabaseMissing('trucks', [
            'unit_number' => 'A1234',
            'year' => 2020,
            'notes' => 'Test truck notes',
        ]);
    }
}
