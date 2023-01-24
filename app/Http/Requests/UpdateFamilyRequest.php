<?php

namespace App\Http\Requests;

use App\Enums\FamilyRelation;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->family)) {
            if ($this->routeIs('employee.*')){
                return redirect()->route('employees.show', ['employee' => $this->employee]);
            } else if ($this->routeIs('my-data.*')){
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
        if ($this->routeIs('employee.*')){
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('families')->where(fn ($query) => $query->where('relationship', $this->relationship)->where('employee_id', $this->employee->id))->ignore($this->family),
                ],
                'relationship' => [
                    'required',
                    Rule::when(function() {
                        if($this->family->relationship == FamilyRelation::Anak){
                            return $this->employee->families->isNotEmpty() and ($this->employee->families->pluck('relationship')->contains(FamilyRelation::Istri) or $this->employee->families->pluck('relationship')->contains(FamilyRelation::Suami));
                        }
                        return false;
                    }, ['not_in:wife,husband']),
                ],
            ];
        } else if ($this->routeIs('my-data.*')){
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('families')->where(fn ($query) => $query->where('relationship', $this->relationship)->where('employee_id', $this->user()->employee->id))->ignore($this->family),
                ],
                'relationship' => [
                    'required',
                    Rule::when(function() {
                        if($this->family->relationship == FamilyRelation::Anak){
                            return $this->user()->employee->families->isNotEmpty() and ($this->user()->employee->families->pluck('relationship')->contains(FamilyRelation::Istri) or $this->user()->employee->families->pluck('relationship')->contains(FamilyRelation::Suami));
                        }
                        return false;
                    }, ['not_in:wife,husband']),
                ],
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'relationship.not_in' => 'This employee already has a spouse'
        ];
    }
}
