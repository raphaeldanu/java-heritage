<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->routeIs('employees.*')) {
            if ($this->user()->cannot('create', [Employee::class, 'employees'])) {
                return redirectNotAuthorized('employees');
            }
        } elseif ($this->routeIs('my-data.*')) {
            if ($this->user()->cannot('create', [Employee::class, 'my-data'])) {
                return redirectNotAuthorized('my-data');
            }
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
        if ($this->routeIs('employees.*')) {
            return [
                'user_id' => 'required|unique:employees|exists:users,id',
                'position_id' => 'required|numeric|exists:positions,id',
                'salary_range_id' => 'required|numeric|exists:salary_ranges,id',
                'nik' => 'required|integer|unique:employees|digits:16',
                'bpjs_tk_number' => 'required|numeric|unique:employees|digits:11',
                'bpjs_kes_number' => 'required|numeric|unique:employees|digits:13',
                'npwp_number' => 'required|numeric|unique:employees|max_digits:16|min_digits:15',
                'name' => 'required|string',
                'employment_status' => 'required|string',
                'first_join' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                    'after:birth_date +17 years',
                ],
                'last_contract_start' => 'required|date',
                'last_contract_end' => 'required|date',
                'birth_place' => 'required|string|alpha',
                'birth_date' => 'required|date|before:-17 years',
                'gender' => 'required|string',
                'tax_status' => 'required|string',
                'address_on_id' => 'required|string',
                'phone_number' => 'required|numeric|max_digits:16',
                'blood_type' => 'nullable|string',
            ];
        } else {
            return [
                'user_id' => 'required|unique:employees|exists:users,id',
                'position_id' => 'nullable|numeric|exists:positions,id',
                'salary_range_id' => 'nullable|numeric|exists:salary_ranges,id',
                'nik' => 'required|integer|unique:employees|digits:16',
                'bpjs_tk_number' => 'required|numeric|unique:employees|digits:11',
                'bpjs_kes_number' => 'required|numeric|unique:employees|digits:13',
                'npwp_number' => 'required|numeric|unique:employees|max_digits:16|min_digits:15',
                'name' => 'required|string',
                'employment_status' => 'required|string',
                'first_join' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                    'after:birth_date +17 years',
                ],
                'last_contract_start' => 'required|date',
                'last_contract_end' => 'required|date',
                'birth_place' => 'required|string|alpha',
                'birth_date' => 'required|date|before:-17 years',
                'gender' => 'required|string',
                'tax_status' => 'required|string',
                'address_on_id' => 'required|string',
                'phone_number' => 'required|numeric|max_digits:16',
                'blood_type' => 'nullable|string',
            ];
        }
    }
}
