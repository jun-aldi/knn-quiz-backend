<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStudent extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => 'required|string|max:255',
            // 'nim' => 'required|string|max:255',
            // 'prodi' => 'required|string|max:255',
            'type' => 'string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'amount_visual' => 'integer',
            'amount_kinesthetic' => 'integer',
            'amount_auditorial' => 'integer',
        ];
    }
}
