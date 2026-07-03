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

                <div class="flex items-start gap-2">
                    <input type="file"
                    name="image"
                    id="imageInput"
                    accept="image/*"
                    class=" border rounded p-2">

                <img id="imagePreview"
                    src="{{ $task->image ? asset('storage/'.$task->image) : '' }}"
                    class="w-52 h-42 rounded-lg border object-cover {{ $task->image ? '' : 'hidden' }}">
                </div>

            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

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

            <div class="mt-6 flex justify-end">

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Update Task
                </button>

            </div>


            <div id="subtaskModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[100]">
                <div class="bg-white p-6 rounded-lg w-[1200px] overflow-y-auto mt-8">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold">
                            Edit Subtask
                        </h2>
                        <button type="button"
                                    id="closeModal"
                                    class=" text-black font-bold text-lg">
                                ✖
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-8">
                        <!-- LEFT -->
                        <div class="flex flex-col gap-2">
                            <div class="mb-2 ">
                                    <label class="block mb-1 font-medium">
                                        Title
                                    </label>

                                    <input type="text"
                                        id="modalTitle"
                                        class="w-full border p-2 rounded">
                                </div>

                                <div class="mb-2">
                                    <label class="block mb-1 font-medium">
                                        Description
                                    </label>

                                    <textarea id="modalDescription"
                                            class="w-full border p-2 rounded"
                                            rows="2"></textarea>
                                </div>

                                <div class="mb-2">
                                    <label class="block mb-1 font-medium">
                                        Assignee
                                    </label>

                                    <select id="modalAssigned"
                                            class="w-full border p-2 rounded">

                                        <option value="">
                                            Select Member
                                        </option>

                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="block mb-1 font-medium">
                                        Due Date
                                    </label>

                                    <input type="text"
                                        id="modalDueDate"
                                        class="w-full border p-2 rounded">
                                </div>

                                <div class="mb-2 ">
                                    <label class="block mb-1 font-medium">
                                        Image
                                    </label>

                                    <div class="flex items-start gap-3">
                                        <input type="file"
                                            id="modalImage"
                                            name="modalImage"
                                            class="flex-1 border border-gray-300 p-2 rounded-lg">

                                        <img id="modalPreview"
                                            class="hidden w-32 h-32 rounded-lg border object-cover">
                                    </div>


                                </div>

                            </div>


                        <!-- RIGHT -->
                        <div class="flex flex-col justify-between">
                            <h3 class="font-semibold text-lg mb-2">
                                Comments
                            </h3>

                            <div id="subtaskCommentList"
                                class="border rounded-lg  max-h-80 overflow-y-auto p-3 ">

                                <!-- Comments -->

                            </div>

                            <div class="flex gap-2 mt-3">

                                <input
                                    type="text"
                                    id="subtaskCommentInput"
                                    class="flex-1 border rounded p-2"
                                    placeholder="Write a comment...">

                                <button
                                    type="button"
                                    id="addComment"
                                    class="bg-green-600 text-white px-4 rounded">
                                    Send
                                </button>

                            </div>


                        </div>

                    </div>

                    <!-- Save Button -->
                    <div class="mt-3 flex">

                        <button
                            type="button"
                            id="saveSubtask"
                            class="bg-blue-600 text-white px-5 py-2 rounded">
                            Save
                        </button>

                    </div>
                </div>

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

        fetch('/tasks/{{ $task->id }}/checklists', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            },
            body: JSON.stringify({
                title: input.value
            })
        })
        .then(response => response.json())
        .then(checklist => {

            const id = checklist.id;

            const html = `
            <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50"
                 data-id="${id}">

                <div class="flex items-center gap-3 flex-1">

                    <input type="checkbox"
                           class="check-toggle w-4 h-4"
                           data-id="${id}">

                    <span>${checklist.title}</span>

                    <input type="hidden"
                           class="sub-title"
                           name="checklists[${id}][title]"
                           value="${checklist.title}">

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

                    <input type="file"
                           class="sub-image hidden"
                           name="subtask_images[${id}]">
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
            `;

            container.insertAdjacentHTML('beforeend', html);

            input.value = '';
        })
        .catch(error => {
            console.error(error);
        });
    });

    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeChecklist')) {

            const row = e.target.closest('[data-id]');
            const id = row.dataset.id;

            fetch('/checklists/' + id + '/destroy', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                }
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    row.remove();
                }
            });
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

    const modal = document.getElementById('subtaskModal');

    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalAssigned = document.getElementById('modalAssigned');
    const modalDueDate = document.getElementById('modalDueDate');
    const modalImage = document.getElementById('modalImage');
    const modalPreview = document.getElementById('modalPreview');

    modalImage.addEventListener('change', function () {

        if (this.files.length > 0) {

            modalPreview.src =
                URL.createObjectURL(this.files[0]);

            modalPreview.classList.remove('hidden');
        }
    });

    let currentSubtask = null;
    let currentFileInput = null;

    document.addEventListener('click', function(e){

        if(e.target.classList.contains('editSubtask')){

            currentSubtask = e.target.closest('.border');

            currentFileInput =
                currentSubtask.querySelector('.sub-image');
                console.log(currentFileInput);

            modalTitle.value =
                currentSubtask.querySelector('.sub-title').value;

            modalDescription.value =
                currentSubtask.querySelector('.sub-description').value;

            modalAssigned.value =
                currentSubtask.querySelector('.sub-assigned').value;

            modalDueDate.value =
                currentSubtask.querySelector('.sub-due-date').value;

            const imageUrl = currentSubtask.dataset.image;

            if (imageUrl) {

                modalPreview.src = imageUrl;
                modalPreview.classList.remove('hidden');

            } else {

                modalPreview.classList.add('hidden');
            }
            loadSubtaskComments(currentSubtask.dataset.id);

            modal.classList.remove('hidden');
        }
    });

    document.getElementById('saveSubtask').addEventListener('click', function () {

        currentSubtask.querySelector('.sub-title').value =
            modalTitle.value;

        currentSubtask.querySelector('.sub-description').value =
            modalDescription.value;

        currentSubtask.querySelector('.sub-assigned').value =
            modalAssigned.value;

        currentSubtask.querySelector('.sub-due-date').value =
            modalDueDate.value;

        currentSubtask.querySelector('span').innerText =
            modalTitle.value;

            if (modalImage.files.length > 0) {
                const dataTransfer = new DataTransfer();

                dataTransfer.items.add(modalImage.files[0]);
                console.log(modalImage.files[0]);
                console.log(currentFileInput);

                currentFileInput.files = dataTransfer.files;

                currentSubtask.dataset.image = URL.createObjectURL(modalImage.files[0]);
            }

        const id = currentSubtask.dataset.id;

        if (id) {

            const formData = new FormData();

            formData.append('title', modalTitle.value);
            formData.append('description', modalDescription.value);
            formData.append('assigned_to', modalAssigned.value);
            formData.append('due_date', modalDueDate.value);

            if (modalImage.files.length > 0) {
                formData.append('image', modalImage.files[0]);
            }

            fetch('/checklists/' + id + '/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Saved');
            });
        }

        modal.classList.add('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function(){
        modal.classList.add('hidden');
    });



    //SUBTASK COMMENTS
function loadSubtaskComments(checklistId) {

    fetch('/checklists/' + checklistId + '/comments')
        .then(response => response.json())
        .then(comments => {

            let html = '';

            if (comments.length === 0) {
                html = `
                    <div class="text-center text-gray-500 py-6">
                        No comments yet.
                    </div>
                `;
            } else {
                comments.forEach(comment => {

                    html += `
                        <div class="flex gap-3 py-4 border-b ">

                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center font-semibold">
                                    ${comment.user.name.charAt(0).toUpperCase()}
                                </div>
                            </div>

                            <div class="flex-1">

                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">
                                        ${comment.user.name}
                                    </span>

                                    <span class="text-xs">
                                        ${new Date(comment.created_at).toLocaleString()}
                                    </span>

                                    ${comment.user_id == currentUserId ? `
                                        <button
                                            class="deleteSubtaskComment text-red-500 text-sm ml-auto"
                                            data-id="${comment.id}">
                                            Delete
                                        </button>
                                    ` : ''}
                                </div>

                                <div class="mt-2 border border-black rounded-xl px-4 py-3 text-gray-700 shadow-sm">
                                    ${comment.comment}
                                </div>

                            </div>

                        </div>
                    `;
                });
            }

            document.getElementById('subtaskCommentList').innerHTML = html;
        });

}

    document.getElementById('addComment').addEventListener('click', function(){

         console.log("Send button clicked");

        const input = document.getElementById('subtaskCommentInput');

        input.addEventListener('input', function () {
            console.log("Typing:", this.value);
        });
        console.log(input);

        const text = input.value;

        console.log("Comment:", "[" + text + "]");

        if(text.trim() == '') return;

        fetch('/checklists/' + currentSubtask.dataset.id + '/comments',{

            method:'POST',

            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            },

            body:JSON.stringify({
                comment:text
            })

        })

        .then(response => response.json())

        .then(() => {

            document.getElementById('subtaskCommentInput').value = '';

            loadSubtaskComments(currentSubtask.dataset.id);

        });

    });

    document.addEventListener('click', function (e) {

        if (!e.target.classList.contains('deleteSubtaskComment')) return;

        const id = e.target.dataset.id;

        fetch('/checklists/comments/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSubtaskComments(currentSubtask.dataset.id);
            }
        });

    });
</script>
<script>
    window.currentUserId = {{ auth()->id() }};
</script>

@endsection
