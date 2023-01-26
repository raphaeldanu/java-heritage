<?php

namespace App\Http\Requests;

use App\Models\TrainingSubject;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', TrainingSubject::class)) {
            return redirect()->route('training-subjects.index')->with('warning', 'Not Authorized');
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
            'subject' => 'required|string|unique:training_subjects,subject',
        ];
    }
}
