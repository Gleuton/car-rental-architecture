<?php

declare(strict_types=1);

namespace App\Http\Requests\Rental;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_uuid' => ['nullable', 'uuid'],
            'car_uuid' => ['nullable', 'uuid'],
            'start_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'day_price_cents' => ['nullable', 'integer', 'min:0'],
            'initial_km' => ['nullable', 'integer', 'min:0'],
            'final_km' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
