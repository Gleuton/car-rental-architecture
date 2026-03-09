<?php

declare(strict_types=1);

namespace App\Http\Requests\Rental;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRentalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|integer',
            'car_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'day_price_cents' => 'required|numeric|min:0',
            'initial_km' => 'required|integer|min:0',
            'final_km' => 'required|integer|min:0',
        ];
    }
}
