<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'original' => 'url|min:12|max:1024',
            'slug' => 'min:1|max:8|unique:slug'
        ];
    }

    public function messages(): array
    {
        return [
            'original.url' => 'Original must be url',
            'original.min' => 'Original must have at least 12 characters',
            'original.max' => 'Original must have less than 1024 characters',
            'slug.min' => 'Slug must have at least 1 character',
            'slug.max' => 'Slug must have less than 8 characters',
            'slug.unique' => 'This slug has already exist'
        ];
    }
}
