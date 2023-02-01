<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePtkpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->ptkp)){
            return redirectNotAuthorized('ptkps');
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tax_status' => [
                'required',
                Rule::unique('ptkps')->ignore($this->ptkp),
            ],
            'fee' => 'required|numeric',
        ];
    }
}
