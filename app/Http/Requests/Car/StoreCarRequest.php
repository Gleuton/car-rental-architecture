<?php

declare(strict_types=1);

namespace App\Http\Requests\Car;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'car_model_id' => 'required_without:car_model_uuid|integer',
            'car_model_uuid' => 'required_without:car_model_id|uuid',
            'license_plate' => 'required|string|max:10',
            'color' => 'required|string|max:255',
            'is_available' => 'boolean',
            'km' => 'integer|min:0',
        ];
    }
}
