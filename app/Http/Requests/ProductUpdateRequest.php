<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['max:255'],
            'price' => ['numeric', 'min:0.01'],
            'status' => ['string'],
            'image' => ['image', 'nullable'],
        ];
    }
}
