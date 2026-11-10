<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'form_data.name' => 'required|string|min:2|max:255',
            'form_data.email' => 'required|email|max:255',
            'form_data.phone' => 'required|string|min:5|max:50',
            'form_data.country' => 'required|string|min:2|max:255',
            'form_data.city' => 'required|string|min:2|max:255',
            'form_data.check_in' => 'required|date|after_or_equal:today',
            'form_data.persons' => 'required|integer|min:1|max:100',
            'form_data.days' => 'required|integer|min:1|max:60',
            'form_data.tour_language' => 'required|in:ka,ru,en',
            'form_data.text' => 'required|string|min:5|max:1000',
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
            'form_data.name.required' => 'The name field is required.',
            'form_data.name.max' => 'The name may not be greater than 255 characters.',
            'form_data.email.required' => 'The email field is required.',
            'form_data.email.email' => 'Please enter a valid email address.',
            'form_data.phone.required' => 'The phone field is required.',
            'form_data.country.required' => 'The country field is required.',
            'form_data.city.required' => 'The city field is required.',
            'form_data.check_in.required' => 'The check-in date is required.',
            'form_data.check_in.date' => 'Please enter a valid date.',
            'form_data.check_in.after_or_equal' => 'The check-in date must be today or in the future.',
            'form_data.persons.required' => 'The number of persons is required.',
            'form_data.persons.integer' => 'The number of persons must be a number.',
            'form_data.persons.min' => 'The number of persons must be at least 1.',
            'form_data.persons.max' => 'The number of persons may not be greater than 100.',
            'form_data.days.required' => 'Please choose the tour duration.',
            'form_data.tour_language.required' => 'Please choose the tour language.',
            'form_data.tour_language.in' => 'Please choose Georgian, Russian or English.',
            'form_data.days.integer' => 'Please choose the tour duration.',
            'form_data.days.min' => 'Please choose the tour duration.',
            'form_data.text.required' => 'Please describe your travel requirements.',
            'form_data.text.min' => 'Please write a little more about your travel requirements.',
            'form_data.text.max' => 'The message may not be greater than 1000 characters.',
            'form_data.phone.min' => 'Please enter a valid phone number.',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        // If a specific key is requested, return that value
        if ($key !== null) {
            return $validated[$key] ?? $default;
        }

        // Ensure form_data exists and is an array
        if (!isset($validated['form_data']) || !is_array($validated['form_data'])) {
            $validated['form_data'] = [];
        }

        return $validated;
    }
}

