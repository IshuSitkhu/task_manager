<div class="bg-white p-3 rounded shadow">
    <form method="POST"
            enctype="multipart/form-data"
            action="{{ route('projects.tasks.store', $project->id) }}">
            @csrf

            <input type="hidden" name="form_type" value="create">

            <div class="mb-3">
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

            <div class="mb-3">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
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

            <div class="grid grid-cols-4 md:grid-cols-4 gap-3">

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
                    <select name="type_id" class="w-full border rounded p-2">
                        @foreach($project->taskTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ old('type_id') == $type->id ? 'selected' : '' }}
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

                    <select name="status" id="statusSelect" class="w-full border rounded p-2" required>
                        @foreach($project->statuses as $status)
                            <option
                                value="{{ $status->slug }}"
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

            <div class="mt-4 flex justify-end">
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
