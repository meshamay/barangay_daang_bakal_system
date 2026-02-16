<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change this authorization logic based on your system.
        // If only logged-in users can request documents, you might check Auth::check().
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
            'document_type' => ['required', 'string', 'max:50'],
            'purpose' => ['nullable', 'string', 'max:255'],
            
            'length_of_residency' => ['required', 'string', 'max:100'], 

        ];
    }
}