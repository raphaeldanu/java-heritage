<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalaryRangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create-salary-ranges')) {
            return redirectNotAuthorized('salary-ranges');
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
            'level_id' => 'required',
            'name' => [
                'required',
                'string',
                Rule::unique('salary_ranges')->where(fn ($query) => $query->where('level_id', $this->level_id)),
            ],
            'base_salary' => 'required|numeric|min:100000',
        ];
    }
}
