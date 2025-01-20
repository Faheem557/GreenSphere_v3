<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Plant;

class PlantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_keys(Plant::CATEGORIES)),
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string|min:50',
            'image' => $this->isMethod('PUT') ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'care_level' => 'required|in:' . implode(',', array_keys(Plant::CARE_LEVELS)),
            'water_needs' => 'required|in:' . implode(',', array_keys(Plant::WATER_NEEDS)),
            'light_needs' => 'required|in:' . implode(',', array_keys(Plant::LIGHT_NEEDS)),
            'height' => 'nullable|string|max:50',
            'pot_size' => 'nullable|string|max:50',
            'maturity_time' => 'nullable|string|max:100',
            'season' => 'nullable|string|max:50',
            'toxicity' => 'nullable|boolean',
            'delivery_options' => 'required|array|min:1|max:3',
            'delivery_options.*' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(Plant::DELIVERY_OPTIONS))
            ],
            'growth_habit' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'delivery_options.required' => 'At least one delivery option must be selected.',
            'delivery_options.array' => 'Delivery options must be selected from the available choices.',
            'delivery_options.min' => 'Please select at least one delivery option.',
            'delivery_options.max' => 'You can select up to 3 delivery options.',
            'delivery_options.*.required' => 'Each selected delivery option must be valid.',
            'delivery_options.*.in' => 'Invalid delivery option selected.',
        ];
    }
} 