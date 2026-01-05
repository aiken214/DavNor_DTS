<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
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
            'doc_id' => 'required|exists:dts_documents,id',
            'description' => 'required|string|max:255',
            'actions_needed' => 'required|string|max:255',
            'dts_doc_type_id' => 'required|exists:dts_doc_types,id',
        ];
    }
}
