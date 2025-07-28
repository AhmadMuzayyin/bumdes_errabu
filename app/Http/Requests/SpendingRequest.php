<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpendingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'badan_usaha_id' => 'required|exists:badan_usahas,id',
            'nominal' => 'required|integer',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ];
    }
}
