<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagAttachRequest extends FormRequest
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
            'tags'   => ['required', 'array', 'min:1'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.required' => 'Tags is required.',
            'tags.array'    => 'Tags must be an array.',
            'tags.min'      => 'Array tags must have at least 1.',
            'tags.*.exists' => 'Tag not found.',
        ];
    }
}
