<?php

declare(strict_types=1);

namespace App\Http\Requests\CarModel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCarModelRequest extends FormRequest
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
            'brand_uuid' => 'nullable|uuid',
            'name' => 'nullable|min:3',
            'image' => 'nullable|file|mimes:png,jpeg,jpg',
            'doors_number' => 'nullable|integer|between:2,5',
            'seats_number' => 'nullable|integer|between:2,7',
            'airbags' => 'nullable|boolean',
            'abs' => 'nullable|boolean',
        ];
    }
}
