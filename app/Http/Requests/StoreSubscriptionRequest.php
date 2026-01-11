<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            'start_date' => 'required|date|after_or_equal:today',
            'delivery_address' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
