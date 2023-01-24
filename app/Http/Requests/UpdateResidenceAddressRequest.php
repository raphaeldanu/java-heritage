<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidenceAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->routeIs('employee.*')){
            if ($this->user()->cannot('update', [$this->employee->residence, $this->employee])) {
                return redirect()->route('employees.show', ['employee' => $this->employee]);
            }
        } else if ($this->routeIs('my-data.*')){
            if ($this->user()->cannot('update', [$this->user()->employee->residence, $this->user()->employee])) {
                return redirect()->route('my-data.index');
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
        return [
            'address' => 'string|required'
        ];
    }
}
