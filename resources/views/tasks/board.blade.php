@extends('layouts.project')

@section('content')


<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Kanban Board</h2>
    <form method="GET" class="mb-4">

        <select id="epicFilter" name="epic">

            <option value="">All Epics</option>

            @foreach($project->epics as $epic)

                <option value="{{ $epic->id }}"
                    {{ request('epic') == $epic->id ? 'selected' : '' }}>

                    {{ $epic->title }}

                </option>

            @endforeach

        </select>

        <select id="sprintFilter" name="sprint">

            <option value="">All Sprints</option>

            @foreach($project->sprints as $sprint)

                <option value="{{ $sprint->id }}">
                    {{ $sprint->name }}
                </option>

            @endforeach

        </select>

    </form>

    @if(auth()->user()->role == 'project_manager')
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
    @endif
</div>

<div id="kanban-board">
    @include('tasks.partials.board')
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

@if ($errors->any() && old('form_type') === 'create')
<script>
document.addEventListener('DOMContentLoaded', function () {
    openTaskModal('{{ old('status') }}');
});
</script>
@endif

<div id="taskEditModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white  w-[1000px] h-full mt-4 p-4 rounded shadow">

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

        <div class="bg-white max-h-[90vh] overflow-y-auto rounded shadow">
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

<script>
    const currentUserId = {{ auth()->id() }};
</script>

<script>
    console.log("Epic filter script started");

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

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }



    function openTypeModal(){
        document.getElementById('typeModal').classList.remove('hidden')
    }

    function closeTypeModal(){
        document.getElementById('typeModal').classList.add('hidden');
    }



    // ADD TASK CLICK GARE PAXI STATUS MA VALUE AAUXA
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
            //tHIS RESET THE DROPDOWN
            select.selectedIndex = 0;
        }
    }

    //edit task modal
    function openEditTaskModal(taskId){

        document.getElementById('currentTaskId').value = taskId;

        fetch(`/projects/{{ $project->id }}/tasks/${taskId}/editmodule`)
            .then(res => res.text())
            .then(html => {

                // yo step ma modal vitra html fill garxa
                document.getElementById('modalBody').innerHTML = html;

                document.getElementById('taskEditModal').classList.remove('hidden');

                const fileInput = document.getElementById('imageInput');
                if (fileInput) fileInput.value = "";

                // Load existing comments
                loadComments(taskId);
            });
    }

    function closeEditTaskModal(){
        document.getElementById('taskEditModal').classList.add('hidden');
    }

    //TASK COMMENT
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
            // reload comments after sending a new comment
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

    //EDIT COMMENT MODAL
    function openEditCommentModal(id, message) {
        document.getElementById('editCommentId').value = id;
        document.getElementById('editCommentText').value = message;

        document.getElementById('editCommentModal').classList.remove('hidden');
    }

    function closeEditCommentModal() {
        document.getElementById('editCommentModal').classList.add('hidden');
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

    const statusForm = document.getElementById("statusForm");

    if (statusForm) {

        statusForm.addEventListener("submit", function () {

            const btn = document.getElementById("statusSubmitBtn");

            if (btn) {
                btn.disabled = true;
                btn.innerText = "Saving...";
            }

        });

    }

    const typeForm = document.getElementById("typeForm");

    if (typeForm) {

        typeForm.addEventListener("submit", function () {

            const btn = document.getElementById("typeSubmitBtn");

            if (btn) {
                btn.disabled = true;
                btn.innerText = "Saving...";
            }

        });

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

    // EPIC FILTER
    // const epicFilter = document.getElementById('epicFilter');

    // if (epicFilter) {
    //     epicFilter.addEventListener('change', function () {

    //         const epic = this.value;

    //         fetch(`/projects/{{ $project->id }}/tasks/board?epic=${epic}`, {
    //             headers: {
    //                 'X-Requested-With': 'XMLHttpRequest'
    //             }
    //         })
    //         .then(response => response.text())
    //         .then(html => {

    //             document.getElementById('kanban-board').innerHTML = html;

    //         });

    //     });

    // } else {
    //     console.log("Epic filter not found!");
    // }

    const epicFilter = document.getElementById('epicFilter');
    const sprintFilter = document.getElementById('sprintFilter');

    function loadBoard() {

        const epic = epicFilter.value;
        const sprint = sprintFilter.value;

        fetch(`/projects/{{ $project->id }}/tasks/board?epic=${epic}&sprint=${sprint}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('kanban-board').innerHTML = html;
        });

    }

    epicFilter.addEventListener('change', loadBoard);
    sprintFilter.addEventListener('change', loadBoard);


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
