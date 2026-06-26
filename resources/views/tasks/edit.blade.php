@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Edit Task
        </h2>

        <a href="{{ route('projects.tasks', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Tasks
        </a>

    </div>

    <div class="bg-white p-6 rounded shadow">


        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
            enctype="multipart/form-data"
              action="{{ route('projects.tasks.update', [$project->id, $task->id]) }}">

            @csrf
            @method('PUT')

            <input type="hidden" name="redirect_to" value="{{ route('projects.tasks', $project->id) }}">

            <div class="mb-4">
                <label class="block font-medium mb-1">Task Title</label>
                <input type="text"
                       name="title"
                       value="{{ $task->title }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            <div class="mb-2">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="3">{{ $task->description }}</textarea>
            </div>

            <div class="mb-2">
                <label class="block font-medium mb-1">Task Image</label>

                <input type="file"
                    name="image"
                    id="imageInput"
                    accept="image/*"
                    class="w-full border rounded p-2">

                <img id="imagePreview"
                    src="{{ $task->image ? asset('storage/'.$task->image) : '' }}"
                    class="mt-3 max-h-28 rounded border {{ $task->image ? '' : 'hidden' }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-medium mb-1">Epic</label>
                    <select name="epic_id" class="w-full border rounded p-2" required>

                        @foreach($epics as $epic)
                            <option value="{{ $epic->id }}"
                                {{ $task->epic_id == $epic->id ? 'selected' : '' }}>
                                {{ $epic->title }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Sprint</label>
                    <select name="sprint_id" class="w-full border rounded p-2">

                        <option value="">No Sprint</option>

                        @foreach($sprints as $sprint)
                            <option value="{{ $sprint->id }}"
                                {{ $task->sprint_id == $sprint->id ? 'selected' : '' }}>
                                {{ $sprint->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Assignee</label>
                    <select name="assigned_to" class="w-full border rounded p-2" required>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Type</label>
                    <select name="type_id" class="w-full border rounded p-2">

                        @foreach($project->taskTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ $task->type == $type->slug ? 'selected' : '' }}>
                                 {{ ucfirst(str_replace('_',' ', $type->name)) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2">

                        @foreach(['low','medium','high','critical'] as $priority)
                            <option value="{{ $priority }}"
                                {{ $task->priority == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2">

                        @foreach($project->statuses as $status)
                            <option value="{{ $status->slug }}"
                                {{ $task->status == $status->slug ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $status->name)) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">GitHub Link</label>
                    <input type="text"
                           name="github_link"
                           value="{{ $task->github_link }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="text"
                           id="task_due_date"
                            name="due_date"
                           value="{{ $task->due_date }}"
                           class="w-full border rounded p-2"
                           required >
                </div>

            </div>

            <div class="col-span-2 mt-6">
                <label class="block font-semibold text-gray-700 text-lg">
                    Checklist
                </label>

                <p class="text-sm text-gray-500 mb-3">
                    Manage task checklist items.
                </p>

                <div class="flex gap-2 mb-4">
                    <input type="text"
                        id="checklistInput"
                        class="flex-1 border rounded-lg p-3"
                        placeholder="Enter checklist item...">

                    <button type="button"
                            id="addChecklist"
                            class="bg-blue-600 text-white px-4 rounded-lg">
                        +
                    </button>
                </div>

                <div id="checklistContainer" class="space-y-2">

                    @foreach($task->checklists as $item)
                    <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50">

                        <div class="flex items-center gap-3">

                    <input type="checkbox"
                        class="check-toggle w-4 h-4"
                        data-id="{{ $item->id }}"
                        {{ $item->is_completed ? 'checked' : '' }}>

                            <span>{{ $item->title }}</span>

                            <input type="hidden"
                                name="checklists[{{ $item->id }}][title]"
                                value="{{ $item->title }}">

                            <input type="hidden"
                                class="check-status"
                                name="checklists[{{ $item->id }}][is_completed]"
                                value="{{ $item->is_completed }}">
                        </div>

                        {{-- <button type="button"
                                class="removeChecklist text-red-500">
                            Delete
                        </button> --}}

                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end">

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Update Task
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

            if (!file) return;

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

        <div class="flex items-center gap-3">

            <input type="checkbox"
                class="check-toggle w-4 h-4">

            <span>${input.value}</span>

            <input type="hidden"
                name="checklists[${id}][title]"
                value="${input.value}">

            <input type="hidden"
                class="check-status"
                name="checklists[${id}][is_completed]"
                value="0">
        </div>

        <button type="button"
                class="removeChecklist text-red-500">
            Delete
        </button>

    </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    input.value = '';
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeChecklist')) {
        e.target.closest('.border').remove();
    }
});
</script>

<script>


document.addEventListener('change', function (e) {

    if (e.target.classList.contains('check-toggle')) {

        const hidden =
            e.target.parentElement.querySelector('.check-status');

        hidden.value = e.target.checked ? 1 : 0;

        fetch('/checklists/' + e.target.dataset.id + '/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                is_completed: e.target.checked ? 1 : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        });
    }

});
</script>

@endsection
