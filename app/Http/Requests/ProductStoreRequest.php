<?php

namespace App\Http\Requests;

use App\Enums\ProductsStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'image' => ['required', 'image'],
            'status' => ['string', Rule::in([ProductsStatus::FORSALE->value, ProductsStatus::DRAFT->value])],
        ];
    }
}
