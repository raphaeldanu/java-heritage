<?php

namespace App\Http\Requests;

use App\Models\ResidenceAddress;
use Illuminate\Foundation\Http\FormRequest;

class StoreResidenceAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', [ResidenceAddress::class, $this->employee])) {
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
            'address' => 'string|required'
        ];
    }
}
