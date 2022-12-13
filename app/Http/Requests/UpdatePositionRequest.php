<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->position)) {
            return redirectNotAuthorized('positions');
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
            'department_id' => 'required',
            'name' => [
                'required',
                'string',
                Rule::unique('positions')->where(fn ($query) => $query->where('department_id', $this->department_id))->whereNull('deleted_at')->ignore($this->position)
            ]
        ];
    }
}
