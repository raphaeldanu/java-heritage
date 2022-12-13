<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->salaryRange)) {
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
                Rule::unique('salary_ranges')->where(fn ($query) => $query->where('level_id', $this->level_id))->ignore($this->salaryRange),
            ],
            'base_salary' => 'required|numeric|min:100000',
        ];
    }
}
