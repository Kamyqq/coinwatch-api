<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePriceAlertRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cryptocurrency_id' => ['required', 'exists:cryptocurrencies,id'],
            'target_price' => ['required', 'numeric', 'min:0.01'],
            'direction' => ['required', 'in:above,below'],
        ];
    }
}
