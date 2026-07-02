@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">Epics</h2>

        <a href="{{ route('projects.epics.create', $project->id) }}"
           class="px-3 py-1 bg-black text-white rounded">
            New Epic
        </a>

    </div>

    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm text-left ">

            <thead class="bg-black/80 border text-white uppercase text-xs">
                <tr>
                    <th class="p-3 border  ">Epic</th>
                    <th class="p-3 border text-center">Owner</th>
                    <th class="p-3 border text-center">Planned timeline</th>
                    <th class="p-3 border text-center">Status</th>
                    <th class="p-3 border text-center">Priority</th>
                    <th class="p-3 border text-center">Progress</th>
                    <th class="p-3 border text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($epics as $epic)
                    <tr class= "border-b cursor-pointer mb-6">

                        <td class="p-2 font-semibold border">
                            {{ $epic->title }}
                        </td>

                        <td class="p-2 text-sm text-gray-800 border text-center">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        <td class="p-2 text-sm border text-center ">

                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-2 text-center border ">
                            <span class="px-2 py-1 rounded text-sm
                            @if($epic->status == 'not_started')  bg-red-200
                            @elseif($epic->status == 'in_progress') bg-blue-200
                            @elseif($epic->status == 'testing') bg-yellow-200
                            @else bg-green-200 @endif
                            ">
                                {{ ucfirst($epic->status) }}
                            </span>
                        </td>

                        <td class="p-2 text-center border">
                            <span class="px-2 py-1 rounded text-sm
                                 @if($epic->priority == 'low') text-green-800
                                @elseif($epic->priority == 'medium') text-yellow-800
                                @elseif($epic->priority == 'high') text-blue-800
                                @else text-red-800 @endif">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>

                        <td class="p-2 text-center border">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-green-500 h-2 rounded"
                                     style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">
                            {{ $epic->progress }}%
                        </td>

                        <td class="p-2 flex justify-end text-center border gap-2">
                            <button onclick="toggleEpic({{ $epic->id }})"
                                        class="text-sm bg-blue-600 text-white px-1 rounded">
                                    Show Tasks
                            </button>

                                    <a href="{{ route('projects.epics.edit', [$project->id, $epic->id]) }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('projects.epics.destroy', [$project->id, $epic->id]) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                onclick="return confirm('Delete epic?')">
                                            Delete
                                        </button>
                                    </form>
                        </td>

                        <td>

                    </tr>

                    <tr id="epic-{{ $epic->id }}" class="hidden  ">

                        <td colspan="7" class="pt-6 pb-8 px-9 border mb-6">

                            @if($epic->tasks->count() > 0)
                                <h3 class="font-semibold text-sm">
                                    Connected Tasks
                                </h3>
                                <table class="w-full text-sm text-left bg-white border rounded">

                                    <thead class=" bg-gray-600 text-white text-left uppercase text-xs border-black">
                                        <tr>
                                            <th class="p-2 border text-center border">Task</th>
                                            <th class="p-2 border text-center border">Sprint</th>
                                            <th class="p-2 border text-center border">Status</th>
                                            <th class="p-2 border text-center border">Type</th>
                                            <th class="p-2 border text-center border">Priority</th>
                                            <th class="p-3 text-center border">Due Date</th>
                                            <th class="p-2 border text-center border">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($epic->normalTasks as $task)

                                            <tr class="border-t text-xs">

                                                <td class="p-2 border font-medium text-xs text-center">
                                                    {{ $task->title }}
                                                </td>

                                                <td class="p-2 text-xs border text-center ">
                                                    {{ $task->sprint ?  $task->sprint->name : 'Backlog' }}
                                                </td>

                                                <td class="p-2 border text-center">
                                                   @php
                                                        $status = $task->projectStatus;
                                                    @endphp

                                                    <span class="px-2 py-1 rounded border text-white text-xs"
                                                        style="background-color: {{ $status->color ?? '#6b7280' }}">

                                                        {{ $status->name ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>

                                                <td class="p-2 border text-center ">
                                                    @php
                                                        $type = $task->projectType;
                                                    @endphp

                                                    <span class="px-2 py-1 border rounded text-white text-xs"
                                                        style="background-color: {{ ($task->type)->color ?? '#6b7280' }}">

                                                        {{ ($task->type)->name  ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>

                                                <td class="p-2 text-yellow-600 border text-center">
                                                    <span class="px-2 py-1 rounded text-xs
                                                        @if($task->priority == 'low') text-green-600
                                                        @elseif($task->priority == 'medium') text-yellow-600
                                                        @elseif($task->priority == 'high') text-orange-600
                                                        @else text-red-600 @endif">

                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>

                                                <td class="border p-3 text-center">
                                                    @if($task->due_date)
                                                        <span class="px-2 py-1 rounded text-sm
                                                            @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'done') text-red-600
                                                            @else text-gray-800 @endif">

                                                            {{ $task->due_date }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td class="p-2 flex border text-xs gap-3 justify-center">

                                                    <button onclick="openTaskModal({{ $task->id }})"
                                                            class="px-3 text-blue-600">
                                                        Edit
                                                    </button>

                                                    <form method="POST"
                                                          action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                                class=" text-red-600 hover:text-red-400"
                                                                onclick="confirmDelete(this.form)">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            @else
                                <p class="text-gray-500">No tasks in this epic</p>
                            @endif
                        </td>

                        <hr>

                    </tr>
                @empty

                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No epics found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <h2 class="text-2xl font-bold mb-4 text-red-600 mt-8">Backlog</h2>

        <table class="w-full text-sm text-left ">

            <thead class="bg-red-600 text-white uppercase text-xs">
                <tr>
                    <th class="p-3 border  ">Epic</th>
                    <th class="p-3 border text-center">Owner</th>
                    <th class="p-3 border text-center">Planned timeline</th>
                    <th class="p-3 border text-center">Status</th>
                    <th class="p-3 border text-center">Priority</th>
                    <th class="p-3 border text-center">Progress</th>
                    <th class="p-3 border text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($backlogEpics as $epic)
                    <tr class=" border-b cursor-pointer mb-6">

                        <td class="p-2 font-semibold border">
                            {{ $epic->title }}
                        </td>

                        <td class="p-2 text-sm text-gray-800 border text-center">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        <td class="p-2 text-sm border text-center ">

                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-2 text-center border ">
                            <span class="px-2 py-1 rounded text-sm
                            @if($epic->status == 'not_started')  bg-red-200
                            @elseif($epic->status == 'in_progress') bg-blue-200
                            @elseif($epic->status == 'testing') bg-yellow-200
                            @else bg-green-200 @endif
                            ">
                                {{ ucfirst($epic->status) }}
                            </span>
                        </td>

                        <td class="p-2 text-center border">
                            <span class="px-2 py-1 rounded text-sm
                                @if($epic->priority == 'low') text-green-800
                                @elseif($epic->priority == 'medium') text-yellow-800
                                @elseif($epic->priority == 'high') text-blue-800
                                @else text-red-800 @endif">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>

                        <td class="p-2 text-center border">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-green-500 h-2 rounded"
                                    style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">
                            {{ $epic->progress }}%
                        </td>

                        <td class="p-2 border text-center border">
                            <button onclick="toggleBacklogEpic({{ $epic->id }})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Show Backlog Tasks
                            </button>
                        </td>

                        <td>

                    </tr>

                    <tr id="backlog-epic-{{ $epic->id }}" class="hidden  ">

                        <td colspan="7" class="pt-6 pb-8 px-9 border mb-6">

                            @if($epic->backlogTasks->count() > 0)

                                <h3 class="font-semibold text-sm">
                                    Backlog Tasks
                                </h3>

                                <table class="w-full text-sm text-left  border rounded">

                                     <thead class="bg-red-600 text-white uppercase text-xs">
                                        <tr>
                                            <th class="p-2 border text-center border">Task</th>
                                            <th class="p-2 border text-center border">Sprint</th>
                                            <th class="p-2 border text-center border">Status</th>
                                            <th class="p-2 border text-center border">Type</th>
                                            <th class="p-2 border text-center border">Priority</th>
                                            <th class="p-3 text-center border">Due Date</th>
                                            <th class="p-2 border text-center border">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>


                                    @foreach($epic->backlogTasks as $task)


                                        <tr class="border-t text-xs">

                                            <td class="p-2 border font-medium text-xs text-center">
                                                    {{ $task->title }}
                                            </td>

                                            <td class="p-2 border text-center">
                                                {{ $task->sprint?->name ?? 'Backlog' }}
                                            </td>

                                            <td class="p-2 border text-center">
                                                    @php
                                                        $status = $task->projectStatus;
                                                    @endphp

                                                    <span class="px-2 py-1 rounded border text-white text-xs"
                                                        style="background-color: {{ $status->color ?? '#6b7280' }}">

                                                        {{ $status->name ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                            </td>

                                            <td class="p-2 border text-center ">
                                                    @php
                                                        $type = $task->projectType;
                                                    @endphp

                                                    <span class="px-2 py-1 border rounded text-white text-xs"
                                                        style="background-color: {{ ($task->type)->color ?? '#6b7280' }}">

                                                        {{ ($task->type)->name  ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                            </td>

                                            <td class="p-2 text-yellow-600 border text-center">
                                                <span class="px-2 py-1 rounded text-xs
                                                    @if($task->priority == 'low') text-green-600
                                                    @elseif($task->priority == 'medium') text-yellow-600
                                                    @elseif($task->priority == 'high') text-orange-600
                                                    @else text-red-600 @endif">

                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>

                                            <td class="p-3 border text-center text-red-600 font-semibold">
                                                {{ $task->due_date }}
                                            </td>

                                            <td class="p-2 flex border text-xs gap-3 justify-center">
                                                <button onclick="openTaskModal({{ $task->id }})"
                                                        class="px-3 text-blue-600">
                                                    Edit
                                                </button>

                                                <form method="POST"
                                                    action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                            class=" text-red-600 hover:text-red-400"
                                                            onclick="confirmDelete(this.form)">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            @endif



                        </td>

                        <hr>

                    </tr>
                @empty

                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No epics found
                        </td>
                    </tr>

                @endforelse

                </tbody>

        </table>

    <script>

        function confirmDelete(form) {
            Swal.fire({
                title: "Are you sure?",
                text: "This tasks will be deleted ",
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

        function toggleEpic(id)
        {
            let row = document.getElementById('epic-' + id);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

        function toggleBacklogEpic(id) {
            document
                .getElementById('backlog-epic-' + id)
                .classList.toggle('hidden');
        }

        function openTaskModal(taskId)
        {
            fetch(`/projects/{{ $project->id }}/tasks/${taskId}/editmodule`)
                        .then(res => res.text())
                        .then(html => {

                            //TAKE HTML AND PUT IN MODAL BODY
                            document.getElementById('modalBody').innerHTML = html;

                            //MODAL IS SHOWN, NOW ATTACH FORM SUBMIT HANDLER
                            document.getElementById('taskModal').classList.remove('hidden');
                        })
                        .catch(err => console.log(err));
        }

        function closeModal()
        {
            document.getElementById('taskModal').classList.add('hidden');
        }

        function attachFormSubmit()
        {
            const form = document.getElementById('taskForm');

                    //ATTACH SUBMIT HANDLER
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        //AJAX SUBMIT FORM
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: new FormData(form)
                        })
                        .then(res => res.json())
                        .then(() => {

                            closeModal();

                            location.reload();
                        });
                    });
        }




    </script>

    <div id="taskModal"
                class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-3xl h-full mt-4 p-4 rounded shadow">

                    <div class="flex mb-4 item-center justify-between ">
                        <h2 class="  text-xl font-bold">Edit Task</h2>
                            <button onclick="closeModal()"
                                class="text-xl">
                                ✕
                            </button>
                    </div>

                    <div class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded shadow">
                        <input type="hidden" id="currentTaskId">

                        <div id="modalBody">

                        </div>
                    </div>
                </div>

    </div>

@endsection
