<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', Salary::class)){
            return redirectNotAuthorized('salaries');
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
            'employee_id' => 'required',
            'month_and_year' => [
                'required', 
                'date',
            ],
            'workdays' => 'required|numeric',
            'service' => 'nullable|numeric',
            'pot_cug' => 'nullable|numeric',
            'thr' => 'nullable|numeric',
        ];
    }
}
