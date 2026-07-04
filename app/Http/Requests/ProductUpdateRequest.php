<?php

namespace App\Http\Requests;

use App\Enums\ProductsStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'status' => ['required', new Enum(ProductsStatus::class)],
            'image' => ['file', 'nullable'],
        ];
    }
}
