<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'description' => ['required', 'string', 'min:10', 'max:20000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'date_available' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
