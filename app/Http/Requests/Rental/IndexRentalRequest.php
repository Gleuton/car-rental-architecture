<?php

declare(strict_types=1);

namespace App\Http\Requests\Rental;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRentalRequest extends FormRequest
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
            'start_date_from' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'start_date_to' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date_from' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date_to' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'order_by' => ['nullable', 'in:start_date,end_date,created_at,id'],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'order_by' => $this->order_by ?? 'start_date',
            'direction' => $this->direction ?? 'asc',
            'per_page' => $this->per_page ?? 15,
        ]);
    }
}
