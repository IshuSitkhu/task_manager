@extends('layouts.project')

@section('content')


<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Kanban Board</h2>

    {{-- @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif --}}
    <div class="flex flex-col gap-2">
        <button onclick="openStatusModal()"
            class="px-3 py-1 bg-black/50 rounded text-white rounded">
            + Add New Status
        </button>

        <button onclick="openTypeModal()"
            class="px-3 py-1 bg-black/70 rounded text-white rounded">
            + Add New Type
        </button>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    @foreach($project->statuses as $status)

        <div class="p-3 rounded border border-black/40">
            <div class="flex justify-between items-center mb-3 p-3 h-10"
                 style="background: {{ $status->color ?? '#000' }}">

                <h3 class="font-bold text-white">
                    {{ $status->name }}
                </h3>

                 <div class="lex justify-end relative">
                    <button onclick="toggleMenu({{ $status->id }})"
                        class="text-white text-sm">
                        ⋮
                    </button>

                    <div id="menu-{{ $status->id }}" class="hidden absolute right-0 top-8 mt-2 bg-white border rounded shadow-lg z-50 min-w-[150px]">
                        <form method="POST"
                        action="{{ route('projects.statuses.destroy', [$project->id, $status->id]) }}">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                onclick="confirmDelete(this.form)"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                Delete Status
                            </button>
                        </form>

                        <button onclick="openTaskModal('{{ $status->slug }}'); toggleMenu({{ $status->id }})"
                            class="text-sm bg-white text-black px-4 py-2 rounded">
                            Add Task
                        </button>
                    </div>

                </div>

                {{-- <button onclick="openTaskModal('{{ $status->slug }}')"
                    class="text-xs bg-white text-black px-2 py-1 rounded">
                    + Add Task
                </button> --}}


            </div>

            <div class="space-y-2 min-h-[200px]"
                ondragover="allowDrop(event)"
                ondrop="dropTask(event, '{{ $status->slug }}')">
                @foreach($tasks->where('status', $status->slug) as $task)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-xl hover:scale-[1.02] hover:border-blue-400 transition-all duration-200 p-4 cursor-pointer"
                    draggable="true"
                    data-task-id="{{ $task->id }}"
                    >

                    <div class="flex justify-between items-start gap-2"  onclick="openEditTaskModal({{ $task->id }})">
                        <div class="font-semibold text-gray-800 leading-snug">
                            {{ $task->title }}
                        </div>

                        <span class="text-white text-sm px-1 rounded"
                            style="background: {{ optional($task->type)->color ?? '#ef4444' }}">
                            {{ optional($task->type)->name ?? 'No Type' }}

                        </span>
                    </div>

                    <div class="mt-2 space-y-1 text-xs text-gray-500 "  onclick="openEditTaskModal({{ $task->id }})">
                        <div>
                            <span class="font-medium text-gray-600">Epic:</span>
                            {{ $task->epic->title ?? 'No Epic' }}
                        </div>

                        <div>
                            <span class="font-medium text-gray-600">Assignee:</span>
                            {{ $task->assignee->name ?? 'Unassigned' }}
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2">
                        {{-- <button
                            type="button"

                           onclick="event.stopPropagation(); toggleBugs({{ $task->id }})"
                            class="text-xs px-2 py-1 rounded bg-red-50 text-red-600">
                            Bugs ({{ $task->bugs->count() }})
                        </button>

                        <button
                            type="button"
                            onclick="event.stopPropagation(); toggleSubtasks({{ $task->id }})"
                            class="text-xs px-2 py-1 rounded bg-blue-50 text-blue-600">
                            {{ $task->checklists->count() }} Subtasks
                        </button> --}}

                        <button
                            type="button"
                            {{-- onclick="event.stopPropagation(); openBugListModal({{ $task->id }})" --}}
                            onclick="event.stopPropagation(); toggleBugs({{ $task->id }})"
                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition">

                             {{ $task->bugs->count() }} Bugs
                        </button>

                        <button
                            type="button"
                            onclick="event.stopPropagation(); toggleSubtasks({{ $task->id }})"
                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition">

                             {{-- {{ $task->checklists->count() }} Subtasks --}}
                             {{ $task->checklists->where('is_completed', 1)->count() }}/{{ $task->checklists->count() }}
                                Subtasks
                        </button>
                    </div>

                    <div id="subtasks-{{ $task->id }}"
                        class="hidden mt-3 border-t pt-2 max-h-40 overflow-y-auto space-y-2">

                        <div class="flex justify-end mb-2">
                            <button
                                type="button"
                                onclick="openSubtaskModal({{ $task->id }})"
                                class="text-sm h-7 px-3 rounded-full">
                                + Add subtask
                            </button>
                        </div>


                        @foreach($task->checklists as $subtask)

                            <div class="flex items-center justify-between bg-gray-50 rounded p-2 text-sm border"
                                data-id="{{ $subtask->id }}"
                                data-image="{{ $subtask->image ? asset('storage/'.$subtask->image) : '' }}">

                                <div class="flex items-center gap-2">

                                    <input type="checkbox"
                                        class="check-toggle"
                                        data-id="{{ $subtask->id }}"
                                        {{ $subtask->is_completed ? 'checked' : '' }}>

                                    <span>{{ $subtask->title }}</span>

                                    <input type="hidden"
                                        class="sub-title"
                                        value="{{ $subtask->title }}">

                                    <input type="hidden"
                                        class="sub-description"
                                        value="{{ $subtask->description }}">

                                    <input type="hidden"
                                        class="sub-assigned"
                                        value="{{ $subtask->assigned_to }}">

                                    <input type="hidden"
                                        class="sub-due-date"
                                        value="{{ $subtask->due_date }}">

                                    <input type="file"
                                        class="sub-image hidden">
                                </div>

                                <div class="flex gap-2">
                                    <button type="button"
                                            class="editSubtask text-blue-600 text-xs"
                                        >
                                        Edit
                                    </button>

                                    <button type="button"
                                            class="removeChecklist text-red-500 text-xs">
                                        Delete
                                    </button>
                                </div>

                            </div>

                        @endforeach

                    </div>

                    <div id="bugs-{{ $task->id }}"
                        class="hidden mt-3 border-t pt-2 max-h-40 overflow-y-auto space-y-2">

                        @foreach($task->bugs as $bug)

                            <div class="border rounded p-2 bg-gray-50 text-sm"
                                data-id="{{ $bug->id }}">

                                <div class="flex justify-between items-center">

                                    <span class="font-medium text-gray-700">
                                        {{ $bug->title }}
                                    </span>
                                        <button
                                            onclick="openEditBugModal({{ $bug->id }})"
                                            class="text-blue-600 text-xs">
                                            Edit
                                        </button>

                                </div>

                                @if($bug->description)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $bug->description }}
                                    </div>
                                @endif

                            </div>

                        @endforeach
                    </div>

                </div>
                @endforeach

            </div>

        </div>

    @endforeach

</div>

<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-6 rounded w-96">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Add New Status</h2>
            <button onclick="closeStatusModal()" class="text-black font-bold text-lg">
                ✖
            </button>
        </div>

        <form method="POST" action="{{ route('projects.statuses.store', $project->id) }}">
            @csrf

            <input type="text" name="name" placeholder="Status Name"
                class="w-full border p-2 mb-3" required>

            <input type="text" name="slug" placeholder="slug"
                class="w-full border p-2 mb-3" required>

            <div class="mb-3 flex">
                <label class="block font-medium mb-1">Background Color: </label>
                <input type="color" name="color" class=" mb-3">
            </div>

            <button type="submit"
                id="statusSubmitBtn"
                class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                Save Status
            </button>
        </form>



    </div>
</div>

<div id="typeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-6 rounded w-96">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Add New Type</h2>
            <button onclick="closeTypeModal()" class="text-black font-bold text-lg">
                ✖
            </button>
        </div>

        <form method="POST" action="{{ route('projects.types.store', $project->id) }}">
            @csrf

            <input type="text" name="name" placeholder="Type Name"
                class="w-full border p-2 mb-3" required>

            <input type="text" name="slug" placeholder="slug"
                class="w-full border p-2 mb-3" required>

            <div class="mb-3 flex">
                <label class="block font-medium mb-1">Background Color: </label>
                <input type="color" name="color" class=" mb-3">
            </div>

            <button type="submit"
                id="typeSubmitBtn"
                class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                Save Type
            </button>
        </form>



    </div>
</div>

<div id="taskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-4 rounded w-[700px] max-h-[99vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Create Task</h2>
            <button onclick="closeTaskModal()">✖</button>
        </div>

        @include('tasks.partials.add_modal-form', [
            'project' => $project,
            'epics' => $epics,
            'sprints' => $sprints,
            'users' => $users
        ])

    </div>
</div>

<div id="taskEditModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl h-full mt-4 p-4 rounded shadow">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">
                Edit Task
            </h2>

            <div class="flex gap-3 items-center">

                    {{-- <button onclick="openBugListFromEditModal()"
                            class="px-2 py-1 rounded bg-red-50 text-red-600 hover:bg-red-100 transition">
                        View Bugs
                    </button> --}}

                <button onclick="openBugFromEditModal()"
                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                    Report Bug
                </button>

                <button onclick="closeEditTaskModal()"
                    class="text-xl">
                    ✕
                </button>

            </div>
        </div>

        <div class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded shadow">
             <input type="hidden" id="currentTaskId">

            <div id="modalBody">

            </div>

            <div class="mt-4 border-t pt-3">

                <h1 class="font-bold m-2 text-center" >Activity</h1>
                <div class="mt-3 flex gap-2 m-6">
                    <input type="text" id="commentInput"
                        class="w-full border p-2 rounded"
                        placeholder="Add a comment...">

                    <button onclick="sendComment()"
                            class="bg-blue-600 text-white px-3 rounded">
                        Send
                    </button>
                </div>

                <h3 class="font-bold m-2">Comments</h3>

                <div id="commentList" class="space-y-2  text-sm">

                </div>
            </div>
        </div>


    </div>



</div>



<div id="bugModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white p-4 rounded w-[600px]">

        <div class="flex py-2 justify-between mb-4">
            <h2 class="  text-xl font-bold">Report Bug</h2>
            <button onclick="closeBugModal()">✕</button>
        </div>


        <div class="bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ route('projects.bugs.store', $project->id) }}">
                @csrf


                <input type="hidden" name="task_id" id="bugTaskId">

                <div class="mb-2">
                    <label class="block font-medium mb-1">Bug Title</label>
                    <input type="text" name="title" placeholder="Bug title"
                    class="w-full border p-2 rounded" required>
                </div>


                <div class="mb-2">
                    <label class="block font-medium mb-1">Description</label>
                    <textarea name="description"
                        class="w-full border rounded p-2 mb-2"
                        placeholder="Describe the bug"
                        rows="3">
                    </textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Severity</label>
                    <select name="severity" class="w-full rounded border p-2 mb-2">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full rounded border p-2 mb-2">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Assign Developer</label>
                    <select name="assigned_to" class="w-full border rounded p-2 mb-2">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="block border font-medium mb-3">
                    <lable class="block font-medium mb-1">Screenshot</lable>
                    <input type="file" name="image" class="w-full rounded p-2" placeholder="Bug Screenshot">
                </div>

                <button class="bg-red-600 text-white px-2 py-2 rounded justify-end">
                    Submit Bug
                </button>
            </form>
        </div>



    </div>
</div>

<div id="bugListModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-[600px] p-4 rounded">

        <div class="flex justify-between mb-3">
            <h2 class="text-xl font-bold">Bug List</h2>
            <button onclick="closeBugListModal()">✕</button>
        </div>

        <div id="bugListBody">
            <!-- AJAX bugs will load here -->
        </div>

    </div>
</div>

<div id="editCommentModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white w-[500px] p-4 rounded shadow">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">
                Edit Comment
            </h2>

            <button onclick="closeEditCommentModal()">
                ✕
            </button>
        </div>

        <input type="hidden" id="editCommentId">

        <textarea id="editCommentText"
                  rows="4"
                  class="w-full border rounded p-2">
        </textarea>

        <div class="flex justify-end mt-4 gap-2">
            <button onclick="closeEditCommentModal()"
                    class="px-4 py-2 border rounded">
                Cancel
            </button>

            <button onclick="updateComment()"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>

    </div>
</div>

<div id="subtaskModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-lg w-[700px] max-h-[90vh] overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold">
                Manage Subtasks
            </h2>

            <button
                onclick="closeSubtaskModal()"
                class="text-xl">
                ✕
            </button>
        </div>

        <input type="hidden" id="modalTaskId">

        <div class="flex gap-2 mb-4">

            <input
                type="text"
                id="checklistInput"
                class="flex-1 border rounded-lg p-3"
                placeholder="Enter subtask item...">

            <button
                type="button"
                id="addChecklist"
                class="bg-blue-600 text-white px-4 rounded-lg">
                +
            </button>

        </div>





    </div>

</div>



<script>
    const currentUserId = {{ auth()->id() }};
</script>

<script>

    function confirmDelete(form) {
        Swal.fire({
            title: "Are you sure?",
            text: "This status will be deleted if no tasks exist.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function openStatusModal() {
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function openTypeModal(){
        document.getElementById('typeModal').classList.remove('hidden')
    }

    function closeTypeModal(){
        document.getElementById('typeModal').classList.add('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    function openTaskModal(statusSlug = null){
        const modal = document.getElementById('taskModal');
        modal.classList.remove('hidden');

        if (statusSlug) {
            const select = document.getElementById('statusSelect');

            if (select) {
                select.value = statusSlug;
            }
        }
    }

    function closeTaskModal(){
        document.getElementById('taskModal').classList.add('hidden');
        const select = document.getElementById('statusSelect');
        if (select) {
            select.selectedIndex = 0;
        }
    }

    function closeEditTaskModal(){
        document.getElementById('taskEditModal').classList.add('hidden');
    }

    function openEditTaskModal(taskId){

        document.getElementById('currentTaskId').value = taskId;

        fetch(`/projects/{{ $project->id }}/tasks/${taskId}/editmodule`)
            .then(res => res.text())
            .then(html => {

                document.getElementById('modalBody').innerHTML = html;

                document.getElementById('taskEditModal').classList.remove('hidden');

                const fileInput = document.getElementById('imageInput');
                if (fileInput) fileInput.value = "";

                loadComments(taskId);
            });
    }

    function loadComments(taskId){
        fetch(`/tasks/${taskId}/comments`)
            .then(async res => {

                if (res.status === 403) {
                    const text = await res.text();

                    Swal.fire({
                        icon: 'error',
                        title: 'Access Denied',
                        text: 'You are not a member of this project'
                    });

                    return null;
                }

                return res.json();
            })
            .then(data => {
                if (!data) return;

                const container = document.getElementById('commentList');
                container.innerHTML = '';

                data.forEach(comment => {
                    container.innerHTML += `
                        <div class="border p-2 rounded bg-gray-50" data-id="${comment.id}">

                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    ${comment.user.name}
                                </div>

                                ${comment.user_id === currentUserId ? `
                                    <div class="flex gap-2 text-xs">
                                        <button onclick='openEditCommentModal(
                                                ${comment.id},
                                                ${JSON.stringify(comment.message)}
                                            )'
                                                class="text-blue-500">
                                            Edit
                                        </button>

                                        <button onclick="deleteComment(${comment.id})"
                                                class="text-red-500">
                                            Delete
                                        </button>
                                    </div>
                                ` : ''}
                            </div>

                            <div id="comment-text-${comment.id}">
                                ${comment.message}
                            </div>

                        </div>
                    `;
                });
            });
    }

    function openEditCommentModal(id, message) {
        document.getElementById('editCommentId').value = id;
        document.getElementById('editCommentText').value = message;

        document
            .getElementById('editCommentModal')
            .classList.remove('hidden');
    }

    function closeEditCommentModal() {
        document
            .getElementById('editCommentModal')
            .classList.add('hidden');
    }

    function updateComment() {

        const id = document.getElementById('editCommentId').value;
        const message = document.getElementById('editCommentText').value;

        if (!message.trim()) return;

        fetch(`/comments/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(async res => {

            if (!res.ok) {
                throw new Error();
            }

            return res.json();
        })
        .then(() => {

            closeEditCommentModal();

            Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: 'Comment updated successfully.'
            });

            loadComments(
                document.getElementById('currentTaskId').value
            );
        })
        .catch(() => {

            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'You can only edit your own comments.'
            });
        });
    }

    function deleteComment(id) {

        Swal.fire({
            title: 'Delete comment?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete'
        }).then((result) => {

            if (!result.isConfirmed) return;

            fetch(`/comments/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(async res => {
                if (!res.ok) {
                    throw new Error();
                }

                return res.json();
            })
            .then(() => {

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Comment deleted successfully'
                });

                loadComments(
                    document.getElementById('currentTaskId').value
                );
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You can only delete your own comments.'
                });
            });

        });
    }

    function sendComment(){
        const taskId = document.getElementById('currentTaskId').value;
        const input = document.getElementById('commentInput');

        if (!input.value.trim()) return;

        fetch(`/tasks/${taskId}/comments`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: input.value
            })
        })
        .then(async res => {

            if (res.status === 403) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You are not a member of this project so, you are not allowed to comment'
                });
                return null;
            }

            return res.json();
        })
        .then(data => {
            if (!data) return;

            input.value = '';
            loadComments(taskId);
        });
    }

    //glabal variable
    let draggedTaskId = null;

    //   DRAG START (GLOBAL SAFE)
    document.addEventListener('dragstart', function (event) {
        const task = event.target.closest('[data-task-id]');
        if (!task) return;

        draggedTaskId = task.dataset.taskId;
        console.log("Dragging task:", draggedTaskId);
    });

    //   ALLOW DROP
    function allowDrop(event) {
        event.preventDefault();
    }

   // DROP TASK
    function dropTask(event, newStatus) {
        event.preventDefault();

        if (!draggedTaskId) return;

        fetch(`/tasks/${draggedTaskId}/move-status`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => console.log(err));

        draggedTaskId = null;
    }

    function toggleMenu(statusId) {
        let menu = document.getElementById(`menu-${statusId}`);

        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden');
            }
        });

        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });

    document.getElementById("statusForm").addEventListener("submit", function () {
        const btn = document.getElementById("statusSubmitBtn");

        if (btn) {
            btn.disabled = true;
            btn.innerText = "Saving...";
        }
    });

    document.getElementById("typeForm").addEventListener("submit", function () {
        const btn = document.getElementById("typeSubmitBtn");

        if (btn) {
            btn.disabled = true;
            btn.innerText = "Saving...";
        }
    });

    //buymodal
    function openBugModal(taskId) {
        const input = document.getElementById('bugTaskId');
        const modal = document.getElementById('bugModal');

        if (!input || !modal) return;

        input.value = taskId;
        modal.classList.remove('hidden');
    }

    function openBugFromEditModal() {
        const taskId = document.getElementById('currentTaskId').value;

        closeEditTaskModal();
        openBugModal(taskId);
    }

    // function openBugListFromEditModal() {
    //     const taskId = document.getElementById('currentTaskId').value;

    //     openBugListModal(taskId);
    // }

    function closeBugModal() {
        document.getElementById('bugModal').classList.add('hidden');
    }

    //show bug report
    // function openBugListModal(taskId){
    //     fetch(`/projects/{{ $project->id }}/tasks/${taskId}/bugs`)
    //         .then(res => res.text())
    //         .then(html => {
    //             document.getElementById('bugListBody').innerHTML = html;
    //             document.getElementById('bugListModal').classList.remove('hidden');
    //         });
    // }

    // function closeBugListModal(){
    //     document.getElementById('bugListModal').classList.add('hidden');
    // }

    function toggleSubtasks(taskId) {

        const box = document.getElementById(
            'subtasks-' + taskId
        );

        box.classList.toggle('hidden');
    }

    document.addEventListener('change', function(e){

        if(e.target.classList.contains('board-check')){

            fetch('/checklists/' + e.target.dataset.id + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    is_completed: e.target.checked ? 1 : 0
                })
            });
        }
    });


    function toggleBugs(taskId) {
        let bugs = document.getElementById('bugs-' + taskId);
        let subtasks = document.getElementById('subtasks-' + taskId);

        subtasks.classList.add('hidden');

        bugs.classList.toggle('hidden');
    }

    function toggleSubtasks(taskId) {
        let bugs = document.getElementById('bugs-' + taskId);
        let subtasks = document.getElementById('subtasks-' + taskId);

        bugs.classList.add('hidden');

        subtasks.classList.toggle('hidden');
    }

    function openSubtaskModal(taskId) {
        console.log(taskId);

        document.getElementById('modalTaskId').value = taskId;





        document.getElementById('subtaskModal').classList.remove('hidden');
    }

    function closeSubtaskModal() {

        document.getElementById('subtaskModal').classList.add('hidden');


    }

</script>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: "{{ session('error') }}"
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}"
    });
</script>
@endif



@include('bugs.partials.bug-edit-modal')
@include('tasks.partials.subtask-modal')
@include('tasks.partials.subtask-script')

@endsection
