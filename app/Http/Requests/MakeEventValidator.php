<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeEventValidator extends FormRequest
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
            'id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'from' => 'required|date|max:255',
            'to' => 'required|date|max:255|after:from',
            'description' => 'required|string|max:255',
            'extra_description' => 'nullable|string|max:255',
            'image' => 'nullable|image',
            // 'isPublic' => 'bool'
        ];
    }
}
