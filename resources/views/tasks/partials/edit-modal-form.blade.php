<div class="bg-white p-3 rounded shadow">
    <form id="taskForm"
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('projects.tasks.update', [$project->id, $task->id]) }}">

        @csrf
        @method('PUT')


            <input type="hidden" name="redirect_to" value="{{ route('projects.tasks', $project->id) }}">

            <div class="mb-4">
                <label class="block font-medium mb-1">Task Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $task->title) }}"
                       class="w-full border rounded p-2"
                       required
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-2">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="3">{{ old('description', $task->description )}}</textarea>
            </div>

            <div class="mb-2">
                <label class="block font-medium mb-1">Task Image</label>

                <div class="flex items-start gap-2">
                    <input type="file"
                            name="image"
                            id="imageInput"
                            accept="image/*"
                            class=" p-2"
                    >
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <img id="imagePreview"
                    src="{{ $task->image ? asset('storage/'.$task->image) : '' }}"
                    class="w-52 h-42 mb-2 rounded-lg border object-cover {{ $task->image ? '' : 'hidden' }}">
                </div>

            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div>
                    <label class="block font-medium mb-1">Epic</label>
                    <select name="epic_id" class="w-full border rounded p-2" required>

                        @foreach($epics as $epic)
                            <option value="{{ $epic->id }}"
                                {{ old('epic_id', $task->epic_id) == $epic->id ? 'selected' : '' }}>
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
                                {{ old('sprint_id', $task->sprint_id) == $sprint->id ? 'selected' : '' }}>

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
                                {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
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
                                {{ old('type_id', $task->type_id) == $type->id ? 'selected' : '' }}>
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
                                {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>
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
                                {{ old('status', $task->status) == $status->slug ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $status->name)) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">GitHub Link</label>
                    <input type="text"
                           name="github_link"
                           value="{{ old('github_link', $task->github_link) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="text"
                           id="task_due_date"
                            name="due_date"
                           value="{{ old('due_date', $task->due_date) }}"
                           class="w-full border rounded p-2"
                           required >
                </div>

            </div>

            <div class="col-span-2 mt-6">
                <label class="block font-semibold text-gray-700 text-lg">
                    Sub Task
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
                    <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50"
                        data-id="{{ $item->id }}"
                        data-image="{{ $item->image ? asset('storage/'.$item->image) : '' }}">

                        <div class="flex items-center gap-3">

                            <input type="checkbox"
                            class="check-toggle w-4 h-4"
                            data-id="{{ $item->id }}"
                            {{ $item->is_completed ? 'checked' : '' }}>

                            <span>{{ $item->title }}</span>

                            <input type="hidden"
                                class="sub-title"
                                name="checklists[{{ $item->id }}][title]"
                                value="{{ $item->title }}">

                            <input type="hidden"
                                class="check-status"
                                name="checklists[{{ $item->id }}][is_completed]"
                                value="{{ $item->is_completed }}">

                            <input type="hidden"
                                class="sub-description"
                                name="checklists[{{ $item->id }}][description]"
                                value="{{ $item->description }}">

                            <input type="hidden"
                                class="sub-assigned"
                                name="checklists[{{ $item->id }}][assigned_to]"
                                value="{{ $item->assigned_to }}">

                            <input type="hidden"
                                class="sub-due-date"
                                name="checklists[{{ $item->id }}][due_date]"
                                value="{{ $item->due_date }}">

                                <input type="file"
                                    class="sub-image hidden"
                                    name="subtask_images[{{ $item->id }}]">
                        </div>

                        <div class="flex gap-2">
                            <button type="button"
                                    class="editSubtask text-blue-600">
                                Edit
                            </button>

                            @if(auth()->user()->role == 'project_manager')
                            <button type="button"
                                    class="removeChecklist text-red-500">
                                Delete
                            </button>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

            <div>

                <button
                        class=" w-full px-3 py-1 rounded-md bg-blue-500 mt-4 mb-3 justify-end text-white hover:bg-blue-800 transition">
                    Update Task
                </button>
            </div>

    </form>


    @if(auth()->user()->role == 'project_manager')
    <form method="POST" action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">
        @csrf
        @method('DELETE')

        <button type="button"
                onclick="confirmDelete(this.form)"
                class=" w-full px-3 py-1 rounded-md bg-red-500 justify-end text-white hover:bg-red-800 transition">
            Delete Task
        </button>
    </form>
    @endif
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

{{-- @include('tasks.partials.subtask-modal')
@include('tasks.partials.subtask-script') --}}
