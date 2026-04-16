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
            'client_id' => 'required_without:client_uuid|integer',
            'client_uuid' => 'required_without:client_id|uuid',
            'car_id' => 'required_without:car_uuid|integer',
            'car_uuid' => 'required_without:car_id|uuid',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'day_price_cents' => 'required|integer',
            'initial_km' => 'required|integer',
            'final_km' => 'required|integer',
        ];
    }
}
