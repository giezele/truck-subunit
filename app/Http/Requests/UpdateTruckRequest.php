<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTruckRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        $maxYear = (int) date('Y') + 5;

        return [
            'unit_number' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . $maxYear,
            'notes' => 'nullable|string',
        ];
    }
}
