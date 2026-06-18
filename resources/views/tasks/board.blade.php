@extends('layouts.project')

@section('content')

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Kanban Board</h2>

    <button onclick="openStatusModal()"
        class="px-3 py-1 bg-black text-white rounded">
        + Add New Status
    </button>


</div>

<div class="grid grid-cols-4 gap-4">

    @foreach($project->statuses as $status)

        <div class="p-3 rounded border">

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
                            <button type="submit" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
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

            <div class="space-y-2">
                @foreach($tasks->where('status', $status->slug) as $task)
                    <div class="bg-white p-3 rounded shadow">
                        <div class="font-semibold">{{ $task->title }}</div>
                        <div class="text-xs text-gray-500">
                            Epic: {{ $task->epic->title ?? 'No Epic' }}
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

            <input type="text" name="slug" placeholder="slug (no spaces)"
                class="w-full border p-2 mb-3" required>

            <div class="mb-3 flex">
                <label class="block font-medium mb-1">Background Color: </label>
                <input type="color" name="color" class=" mb-3">
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded w-full">
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

    // let draggedTaskId = null;

    // function dragTask(event) {
    //     draggedTaskId = event.target.getAttribute('data-task-id');
    // }

    // function allowDrop(event) {
    //     event.preventDefault();
    // }

    // function dropTask(event, newStatus) {
    //     event.preventDefault();

    //     if (!draggedTaskId) return;

    //     fetch(`/tasks/${draggedTaskId}/move-status`, {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         },
    //         body: JSON.stringify({
    //             status: newStatus
    //         })
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //         if (data.success) {
    //             location.reload(); // simple refresh
    //         }
    //     });
    // }

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

</script>

@endsection
