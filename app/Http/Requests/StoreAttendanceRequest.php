<?php

namespace App\Http\Requests;

use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create', Attendance::class)) {
            return redirectNotAuthorized('attendances');
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
            'attendance_files' => 'required|mimes:xls,xlm,xla,xlc,xlt,xlw,xlam,xlsb,xlsm,xltm,xlsx,csv,tsv',
        ];
    }
}
