<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
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
            'order_id' => 'nullable|exists:orders,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'package_id' => 'nullable|exists:subscription_packages,id',
            'payment_method' => 'required|in:mobile_money,cash,card',
            'phone_number' => 'required_if:payment_method,mobile_money|string|max:20',
            'transaction_id' => 'nullable|string|max:100|unique:payments,transaction_id',
        ];
    }
}
