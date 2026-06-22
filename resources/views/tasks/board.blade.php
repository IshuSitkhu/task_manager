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

    <button onclick="openStatusModal()"
        class="px-3 py-1 bg-black text-white rounded">
        + Add New Status
    </button>


</div>



<div class="grid grid-cols-4 gap-4">

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
                <div class="bg-white border border-gray-300 p-3 rounded shadow cursor-move"
                    draggable="true"
                    data-task-id="{{ $task->id }}">

                    <div class="font-semibold">{{ $task->title }}</div>

                    <div class="text-xs text-gray-500 py-1">
                        Epic: {{ $task->epic->title ?? 'No Epic' }}
                    </div>

                    <div class="text-xs mb-3 text-gray-500">
                        Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                    </div>

                     <button onclick="openBugModal()"
                            class="px-3 py-1 bg-red-600 text-sm text-white rounded">
                            Report Bug
                        </button>

                    <div class="mt-3 flex justify-end">

                        <button onclick="openEditTaskModal({{ $task->id }})"
                                class="text-sm px-3 py-1 bg-blue-50 text-blue-600 rounded">
                            Edit
                        </button>


                        <form method="POST" action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                            onclick="confirmDelete(this.form)"
                            class="text-sm px-3 py-1 bg-red-50 text-red-600 rounded">
                                Delete
                            </button>
                        </form>
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

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    function openTaskModal(statusSlug = null)
    {
        const modal = document.getElementById('taskModal');
        modal.classList.remove('hidden');

        if (statusSlug) {
            const select = document.getElementById('statusSelect');

            if (select) {
                select.value = statusSlug;
            }
        }
    }

    function closeTaskModal()
    {
        document.getElementById('taskModal').classList.add('hidden');



        const select = document.getElementById('statusSelect');
        if (select) {
            select.selectedIndex = 0;
        }
    }

    function closeEditTaskModal()
    {
        document.getElementById('taskEditModal').classList.add('hidden');
    }


    function openEditTaskModal(taskId)
    {
        fetch(`/projects/{{ $project->id }}/tasks/${taskId}/editmodule`)
            .then(res => res.text())
            .then(html => {

                document.getElementById('modalBody').innerHTML = html;

                document.getElementById('taskEditModal').classList.remove('hidden');

                // RESET FILE INPUT ISSUE FIX
                const fileInput = document.getElementById('imageInput');
                if (fileInput) fileInput.value = "";
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


    function openBugModal() {
        document.getElementById('bugModal').classList.remove('hidden');
    }

    function closeBugModal() {
        document.getElementById('bugModal').classList.add('hidden');
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


            <div id="taskEditModal"
                class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-3xl p-4 rounded shadow">

                    <div class="flex py-2 justify-between ">
                        <h2 class="  text-xl font-bold">Edit Task</h2>
                        <button onclick="closeEditTaskModal()">✕</button>
                    </div>

                    <div id="modalBody">
                        {{-- AJAX FORM WILL LOAD HERE --}}
                    </div>
                </div>

            </div>

    <div id="bugModal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-2xl p-4 rounded shadow">

            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-bold text-red-600">Report Bug</h2>
                <button onclick="closeBugModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('projects.tasks.store', $project->id) }}">
                @csrf

                <input type="hidden" name="status" value="bug">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="w-full border p-2" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="w-full border p-2"></textarea>
                </div>

                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Submit Bug
                </button>
            </form>
        </div>
    </div>

    <div id="bugModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white p-4 rounded w-[600px]">

        <h2 class="text-xl font-bold mb-3">Report Bug</h2>

        <form method="POST" enctype="multipart/form-data"
              action="{{ route('projects.bugs.store', $project->id) }}">
            @csrf

            <input type="text" name="title" placeholder="Bug title"
                   class="w-full border p-2 mb-2">

            <textarea name="description" class="w-full border p-2 mb-2"
                      placeholder="Describe the bug"></textarea>

            <select name="severity" class="w-full border p-2 mb-2">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="critical">Critical</option>
            </select>

            <input type="file" name="image" class="w-full mb-2">

            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Submit Bug
            </button>
        </form>

        <button onclick="closeBugModal()" class="mt-2 text-sm text-gray-500">
            Close
        </button>
    </div>
</div>

@endsection
