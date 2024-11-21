<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jenisPerusahaan' => ['required', 'string'],
            'alamat' => ['required', 'string', 'max:255'],
            'noWa' => ['required', 'string', 'max:15'],
            'email' => ['required', 'string'],
        ];
    }
}
