<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rolesArray = DB::table('roles')->get('name')->toArray();
        $roles = array_map(fn ($role) => $role->name, $rolesArray);

        return [
            'role' => ['required', Rule::in($roles)],
        ];
    }
}
