<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->leave)) {
            return redirect()->route('employees.show', ['employee' => $this->employee]);
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
            'annual' => 'required|numeric',
            'dp' => 'required|numeric',
            'extra_off' => 'required|numeric',
        ];
    }
}
