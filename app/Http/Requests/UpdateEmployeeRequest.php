<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->employee)) {
            return redirectNotAuthorized('employees');
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
            'user_id' => [
                'required',
                Rule::unique('employees')->ignore($this->employee),
                'exists:users,id'
            ],
            'position_id' => 'required|numeric|exists:positions,id',
            'salary_range_id' => 'required|numeric|exists:salary_ranges,id',
            'nik' => [
                'required',
                'integer',
                'digit:16',
                Rule::unique('employees')->ignore($this->employee),
            ],
            'bpjs_tk_number' => [
                'required',
                'integer',
                'digit:11',
                Rule::unique('employees')->ignore($this->employee),
            ],
            'bpjs_kes_number' => [
                'required',
                'integer',
                'digit:13',
                Rule::unique('employees')->ignore($this->employee),
            ],
            'npwp_number' => [
                'required',
                'integer',
                'max_digits:16',
                'min_digits:15',
                Rule::unique('employees')->ignore($this->employee),
            ],
            'name' => 'required|string|alpha',
            'employment_status' => 'required|string',
            'first_join' => [
                'required',
                'date',
                'before_or_equal:today',
                'after:birth_date +17 years',
            ],
            'birth_place' => 'required|string|alpha',
            'birth_date' => 'required|date|before:-17 years',
            'gender' => 'required|string',
            'tax_status' => 'required|string',
            'address_on_id' => 'required|string',
            'phone_number' => 'required|integer|max_digits:16',
            'blood_type' => 'nullable|string',
        ];
    }
}
