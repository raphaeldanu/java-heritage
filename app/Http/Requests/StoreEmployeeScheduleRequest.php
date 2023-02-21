<?php

namespace App\Http\Requests;

use App\Models\EmployeeSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', EmployeeSchedule::class)){
            return redirect()->route('schedules.index')->with('warning', 'Not Authorized');
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
                Rule::unique('employee_schedules')->where(fn ($query) => $query->where('employee_id', $this->employee_id)),
            ],
            'workdays' => 'required|numeric|max:28'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'month_and_year' => Carbon::parse($this->month_and_year),
        ]);
    }
}
