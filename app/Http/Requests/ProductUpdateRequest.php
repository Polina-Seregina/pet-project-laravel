<?php

namespace App\Http\Requests;

use App\Enums\ProductsStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Rules\Authorship;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $user = $this->user();

        return [
            'name' => ['string', 'max:255'],
            'description' => ['max:255'],
            'price' => ['numeric', 'min:0.01'],
            'status' => ['required', new Enum(ProductsStatus::class)],
            'image' => ['file', 'nullable', new Authorship($product, $user)],
        ];
    }
}
