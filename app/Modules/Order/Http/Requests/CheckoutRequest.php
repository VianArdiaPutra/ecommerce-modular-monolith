<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => 'Pilih alamat pengiriman terlebih dahulu.',
            'address_id.integer' => 'Alamat pengiriman tidak valid.',
        ];
    }
}
