@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Create Task
        </h2>

        <a href="{{ route('projects.tasks', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Tasks
        </a>

    </div>

    <div class="bg-white p-6 ">

        <form method="POST"
            enctype="multipart/form-data"
            action="{{ route('projects.tasks.store', $project->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Task Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="w-full border rounded p-2"
                       placeholder="e.g. Login API Fix"
                       required
                >

                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea
                    name="description"
                    class="w-full border rounded p-3"
                    rows="3"
                    placeholder="Describe the task...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-2">
                <label class="block font-medium mb-1">Task Image</label>

                <div class="flex items-start gap-2">
                    <input type="file"
                        name="image"
                        id="imageInput"
                        accept="image/*"
                        class=" p-2"
                        placeholder="Task image"
                    >
                    @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <img id="imagePreview"
                        class="hidden mt-3 w-52 h-42 mb-2 rounded-lg border object-cover">
                </div>


            </div>

            <div class=" grid grid-cols-2 md:grid-cols-4 gap-4">

                <div>
                    <label class="block font-medium mb-1">Epic</label>
                    <select name="epic_id" class="w-full border rounded p-2" required>
                        <option value="">Select Epic</option>

                        @foreach($epics as $epic)
                            <option value="{{ $epic->id }}"
                                {{ old('epic_id') == $epic->id ? 'selected' : '' }}>
                                {{ $epic->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Sprint (optional)</label>
                    <select name="sprint_id" class="w-full border rounded p-2">
                        <option value="">No Sprint</option>

                        @foreach($sprints as $sprint)
                            <option value="{{ $sprint->id }}"
                                {{ old('sprint_id') == $sprint->id ? 'selected' : '' }}>
                                {{ $sprint->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Assignee</label>
                    <select name="assigned_to" class="w-full border rounded p-2" required>
                        <option value="">Select Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Type</label>
                    {{-- <select name="type" class="w-full border rounded p-2" required>
                        <option value="feature">Feature</option>
                        <option value="bug">Bug</option>
                        <option value="ui">UI/UX</option>
                        <option value="frontend">Frontend</option>
                        <option value="backend">Backend</option>
                        <option value="test">Test</option>
                    </select> --}}
                    <select name="type_id" class="w-full border rounded p-2">
                        @foreach($project->taskTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>

                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Status</label>

                    <select name="status" class="w-full border rounded p-2" required>
                        @foreach($project->statuses as $status)
                            <option value="{{ $status->slug }}"
                                {{ old('status') == $status->slug ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div>
                    <label class="block font-medium mb-1">GitHub Link</label>
                    <input type="text"
                           name="github_link"
                           class="w-full border rounded p-2"
                           value="{{ old('github_link') }}"
                           placeholder="https://github.com/...">
                </div>

                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="text"
                        id="task_due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full border rounded p-2"
                        placeholder="Select due date"
                        required
                    >
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>



            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create Task
                </button>
            </div>





        </form>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');

        if (!input) return;

        input.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) {
                preview.src = '';
                preview.classList.add('hidden');
                return;
            }

            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Please upload a valid image file (jpg, png, webp).'
                });
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        });

    });
</script>


<script>


    const addBtn = document.getElementById('addChecklist');
    const input = document.getElementById('checklistInput');
    const container = document.getElementById('checklistContainer');

    addBtn.addEventListener('click', function () {

        if (input.value.trim() === '') return;

        const id = Date.now();

        const html = `
            <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50">

                <div class="flex items-center gap-3 flex-1">

                    <input type="checkbox"
                        class="check-toggle w-4 h-4">

                    <span>${input.value}</span>

                    <input type="hidden"
                     class="sub-title"
                        name="checklists[${id}][title]"
                        value="${input.value}">

                    <input type="hidden"
                        class="check-status"
                        name="checklists[${id}][is_completed]"
                        value="0">

                    <input type="hidden"
                      class="sub-description"
                        name="checklists[${id}][description]"
                        value="">

                    <input type="hidden"
                      class="sub-assigned"
                        name="checklists[${id}][assigned_to]"
                        value="">

                    <input type="hidden"
                     class="sub-due-date"
                        name="checklists[${id}][due_date]"
                        value="">

                    <input type="hidden"
                     class="sub-image"
                        name="checklists[${id}][image]"
                        value="">
                </div>

                <div class="flex gap-2">

                    @if(auth()->user()->role == 'project_manager')
                    <button type="button"
                            class="removeChecklist text-red-500">
                        Delete
                    </button>
                    @endif
                </div>

            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);

        input.value = '';
        input.focus();
    });

    document.addEventListener('change', function (e) {

        if (e.target.classList.contains('check-toggle')) {

            const hidden =
                e.target.parentElement.querySelector('.check-status');

            hidden.value = e.target.checked ? 1 : 0;
        }

    });

    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeChecklist')) {
            e.target.closest('.border').remove();
        }

    });


    const modal = document.getElementById('subtaskModal');
    const closeBtn = document.getElementById('closeModal');

    document.addEventListener('click', function(e){

        if(e.target.classList.contains('editSubtask')){
            modal.classList.remove('hidden');
        }

    });

    closeBtn.addEventListener('click', function(){
        modal.classList.add('hidden');
    });

</script>

@endsection
