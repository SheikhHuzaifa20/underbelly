<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'description' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }
}
