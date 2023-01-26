<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainingSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('update', $this->training_subject)) {
            return redirect()->route('training-subjects.index')->with('warning', 'Not Authorized');
        } else {
            return true;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'subject' => [
                'required',
                'string',
                Rule::unique('training_subjects')->ignore($this->training_subject),
            ],
        ];
    }
}
