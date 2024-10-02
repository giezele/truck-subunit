<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckSubunit extends Model
{
    use HasFactory;

    protected $table = 'truck_subunits';
    /**
     * @var string[]
     */
    protected $fillable = [
        'main_truck',
        'subunit',
        'start_date',
        'end_date'
    ];

    /**
     * Get the main truck (the truck being replaced).
     */
    public function mainTruck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, 'main_truck');
    }

    /**
     * Get the subunit truck (the truck replacing the main truck).
     */
    public function subunitTruck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, 'subunit');
    }
}
