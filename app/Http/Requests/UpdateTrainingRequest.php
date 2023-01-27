<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', $this->training)) {
            return redirect()->route('trainings.index')->with('warning', 'Not Authorized');
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
            'training_menu_id' => 'required|exists:training_menus,id',
            'trainers_name' => 'required|string',
            'training_venue' => 'required|string',
            'training_date' => 'required|date',
            'training_length' => 'required|integer',
            'cost_per_participant' => 'required|integer',
        ];
    }
}
