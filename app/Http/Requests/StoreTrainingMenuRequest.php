<?php

namespace App\Http\Requests;

use App\Models\TrainingMenu;
use App\Models\TrainingSubject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainingMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', TrainingMenu::class)) {
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
        $dep = TrainingSubject::where('name', "Departemental Training")->first();
        return [
            'training_subject_id' => 'required|exists:training_subjects,id',
            'department_id' => [
                Rule::requiredIf(fn() => $this->training_subject_id == $dep->id),
                Rule::excludeIf(fn() => $this->training_subject_id != $dep->id),
                'exists:departments,id'
            ],
            'title' => [
                'required',
                'string',
                Rule::unique('training_menus')->where(fn ($query) => 
                    $query->where('training_subject_id', $this->training_subject_id)
                          ->where('department_id', $this->department_id)),
            ]
        ];
    }
}
