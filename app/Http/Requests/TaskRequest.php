<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => 'required|string|max:255|min:3',
            'epic_id' => 'required|exists:epics,id',
            'sprint_id' => 'nullable|exists:sprints,id',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required',
            'priority' => 'required',
            'type_id' => 'required|exists:task_types,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'due_date' => 'required|date',

            'checklists' => 'nullable|array',
            'checklists.*.title' => 'required|string|max:255',
            'checklists.*.description' => 'nullable|string',
            'checklists.*.assigned_to' => 'nullable|exists:users,id',
            'checklists.*.due_date' => 'nullable|date',
            'checklists.*.is_completed' => 'nullable|boolean',
        ];
    }

        public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'title.min' => 'Task title must be at least 3 characters.',
            'title.max' => 'Task title may not be greater than 255 characters.',

            'epic_id.required' => 'Please select an epic.',
            'assigned_to.required' => 'Please assign this task to a member.',
            'status.required' => 'Please select a status.',
            'priority.required' => 'Please select a priority.',
            'type_id.required' => 'Please select a task type.',
            'due_date.required' => 'Please select a due date.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Only JPG, JPEG, PNG and WEBP images are allowed.',
            'image.max' => 'The image must not be larger than 2 MB.',

            'checklists.*.title.required' => 'Checklist title is required.',
        ];
    }
}
