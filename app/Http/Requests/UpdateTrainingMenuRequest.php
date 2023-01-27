<?php

namespace App\Http\Requests;

use App\Models\TrainingSubject;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->training_menu)) {
            return redirect()->route('training-menus.index')->with('warning', 'Not Authorized');
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
        $ts = TrainingSubject::find($this->training_subject_id);
        return [
            'training_subject_id' => 'required|exists:training_subjects,id',
            'department_id' => [
                Rule::requiredIf(fn() => isset($ts) and $ts->subject == "Departmental Training"),
                Rule::excludeIf(fn() => isset($ts) and $ts->subject != "Departmental Training"),
                'exists:departments,id'
            ],
            'title' => [
                'required',
                'string',
                Rule::unique('training_menus')->where(fn ($query) => 
                    $query->where('training_subject_id', $this->training_subject_id)
                          ->where('department_id', $this->department_id))
                          ->ignore($this->training_menu),
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'training_subject_id' => 'training subject',
            'department_id' => 'department',
        ];
    }
}
