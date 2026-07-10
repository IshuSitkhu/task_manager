<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EpicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',

            'owner_id' => 'required|exists:users,id',

            'priority' => 'required|in:low,medium,high,critical',

            'status' => 'required|in:not_started,in_progress,testing,completed',

            'planned_start_date' => 'nullable|date',

            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',

            'progress' => 'nullable|integer|min:0|max:100',
        ];
    }

       public function messages(): array
    {
        return [
            'title.required' => 'Epic title is required.',
            'title.min' => 'Epic title must be at least 3 characters.',
            'title.max' => 'Epic title may not be greater than 255 characters.',

            'owner_id.required' => 'Please select an owner.',
            'status.required' => 'Please select a status.',
            'priority.required' => 'Please select a priority.',
            'planned_start_date.required' => 'Please select a start date.',
            'planned_end_date.required' => 'Please select a end date.',
        ];
    }
}
