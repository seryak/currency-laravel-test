<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvertCurrencyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $activeCurrencies = Currency::where('is_active', true)->pluck('code');

        return [
            'amount' => 'required|numeric|min:0.01',
            'from' => ['required', Rule::in($activeCurrencies)],
            'to' => ['required', Rule::in($activeCurrencies)],
        ];
    }
}