<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'author'      => 'sometimes|string|max:255',
            'isbn'        => 'sometimes|string|max:13|unique:books,isbn,' . $this->book->id,
            'description' => 'nullable|string',
        ];
    }
}
