<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'project_manager';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3|regex:/^[A-Za-z][A-Za-z0-9 _-]*$/',
            'description' => 'nullable|string',
            'status' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a project name.',
            'name.min' => 'The project name must be at least 3 characters.',
            'name.max' => 'The project name may not be greater than 255 characters.',
            'name.regex' => 'Project names must start with a letter and may only contain letters, numbers, spaces, hyphens (-), and underscores (_).',

            'status.required' => 'Please select a project status.',
        ];
    }
}
