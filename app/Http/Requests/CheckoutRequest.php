<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Plant;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'location' => 'required|array',
            'location.address' => 'required|string',
            'location.city' => 'required|string',
            'location.state' => 'required|string',
            'location.zip' => 'required|string',
            'delivery' => 'required|array',
            'delivery.date' => 'required|date|after:today',
            'delivery.slot' => 'required|in:morning,afternoon,evening',
            'delivery.instructions' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,online',
            'delivery_option_id' => [
                'required',
                'string',
                Rule::in(['standard', 'express', 'pickup'])
            ],
            'shipping_address' => 'required|string|min:10',
            'phone' => 'required|string|min:10'
        ];
    }

    public function messages()
    {
        return [
            'delivery_option_id.required' => 'Please select a delivery option',
            'delivery_option_id.in' => 'Please select a valid delivery option (Standard, Express, or Pickup)',
            'shipping_address.required' => 'Shipping address is required',
            'shipping_address.min' => 'Please enter a complete shipping address',
            'phone.required' => 'Phone number is required',
            'phone.min' => 'Please enter a valid phone number',
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => 'Invalid payment method selected',
            'delivery.date.required' => 'Please select a delivery date',
            'delivery.date.after' => 'Delivery date must be after today',
            'delivery.slot.required' => 'Please select a delivery time slot',
            'delivery.slot.in' => 'Please select a valid delivery time slot'
        ];
    }
} 