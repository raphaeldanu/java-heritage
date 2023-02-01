<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->employee_schedule)){
            return redirect()->route('schedules.show-by-employee', ['employee' => $this->employee_schedule->employee])->with('warning', 'Not Authorized');
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
                Rule::unique('employee_schedules')->where(fn ($query) => $query->where('employee_id', $this->employee_schedule->employee_id))->ignore($this->employee_schedule),
            ],
            'workdays' => 'required|numeric|max:28'
        ];
    }
}
