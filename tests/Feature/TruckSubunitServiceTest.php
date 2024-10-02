<?php

namespace Tests\Feature;

use App\Models\Truck;
use App\Models\TruckSubunit;
use App\Services\TruckSubunitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Exception;

class TruckSubunitServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TruckSubunitService $truckSubunitService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->truckSubunitService = new TruckSubunitService();
    }

    /**
     * @throws Exception
     */
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

        $subunit = $this->truckSubunitService->createSubunit($data);

        $this->assertInstanceOf(TruckSubunit::class, $subunit);
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('A truck cannot be its own subunit.');

        $this->truckSubunitService->createSubunit($data);
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
            'subunit' => Truck::factory()->create()->id, // Another subunit truck
            'start_date' => '2024-10-01',  // Overlapping with existing subunit
            'end_date' => '2024-10-15',
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The dates for the subunit overlap with an existing subunit for the main truck.');

        $this->truckSubunitService->createSubunit($data);
    }

    #[Test]
    public function it_throws_an_exception_if_the_dates_overlap_for_the_subunit_truck(): void
    {
        $mainTruck = Truck::factory()->create();
        $subunitTruck = Truck::factory()->create();

        // Create an existing subunit where the subunit truck is already busy
        TruckSubunit::factory()->create([
            'main_truck' => $mainTruck->id,
            'subunit' => $subunitTruck->id,
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-10',
        ]);

        $data = [
            'main_truck' => Truck::factory()->create()->id, // Another main truck
            'subunit' => $subunitTruck->id, // Same subunit truck
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-15',
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The subunit truck is already a subunit for another truck during these dates.');

        $this->truckSubunitService->createSubunit($data);
    }
}
