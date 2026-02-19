<?php

declare(strict_types=1);

namespace App\Http\Requests\CarModel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarModelRequest extends FormRequest
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
            'brand_id' => 'required|integer',
            'name' => 'required|min:3',
            'image' => 'required|file|mimes:png,jpeg,jpg',
            'doors_number' => 'required|integer|digits_between:2,7',
            'seats_number' => 'required|integer|digits_between:2,5',
            'airbags' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }
}
