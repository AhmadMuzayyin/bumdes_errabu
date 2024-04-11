<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'nama_pembeli' => 'required|string',
            'telepon' => 'required|string',
            'nama_barang' => 'required|string',
            'total_bayaran' => 'required|numeric',
            'total_kembalian' => 'required|numeric',
            'tanggal_transaksi' => 'required|date',
        ];
    }
}
