<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'unit_number',
        'year',
        'notes'
    ];
}
