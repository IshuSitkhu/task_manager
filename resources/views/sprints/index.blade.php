@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">
            Sprints
        </h2>

        <a href="{{ route('projects.sprints.create', $project->id) }}"
           class="px-3 py-1 bg-black text-white rounded">
            New Sprint
        </a>

    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">

         <table class="w-full text-sm text-left ">

            <thead class="bg-black/80 border text-white uppercase text-xs">
                <tr>
                    <th class="p-3 border">Sprint</th>
                    <th class="p-3 border text-center">Goal</th>
                    <th class="p-3 border text-center">Timeline</th>
                    <th class="p-3 border text-center">Status</th>
                    <th class="p-3 border text-center">Progress</th>
                    <th class="p-3 border text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sprints as $sprint)
                    <tr class= "border-b cursor-pointer mb-6">

                        <td class="p-2 font-semibold border">
                            {{ $sprint->name }}
                        </td>

                        <td class="p-3 border">
                            {{ $sprint->goal ?? '-' }}
                        </td>

                        <td class="p-3 border">

                            <span>
                                {{ \Carbon\Carbon::parse($sprint->start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($sprint->end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-3 text-center border">
                            <span class="px-2 py-1 rounded text-xs
                                @if($sprint->status == 'planned')
                                    bg-red-200
                                @elseif($sprint->status == 'active')
                                    bg-yellow-200
                                @else
                                    bg-green-200
                                @endif
                            ">
                                {{ ucfirst($sprint->status) }}
                            </span>

                        </td>

                        <td class="p-3 text-center border">

                            <div class="w-full bg-gray-200 rounded h-2">

                                <div
                                    class="bg-green-500 h-2 rounded"
                                    style="width: {{ $sprint->progress }}%">
                                </div>

                            </div>

                            <span class="text-xs">
                                {{ $sprint->progress }}%
                            </span>
                        </td>

                        <td class="p-2 flex justify-end text-center border gap-2">
                            <button onclick="toggleSprint({{ $sprint->id }})"
                                        class="text-sm bg-blue-600 text-white px-1 rounded">
                                    Show Tasks
                            </button>

                                <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('projects.sprints.destroy', [$project->id, $sprint->id]) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete sprint?')"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Delete
                                    </button>
                                </form>
                        </td>
                    </tr>

                    <tr id="sprint-{{ $sprint->id }}" class="hidden  ">

                        <td colspan="7" class="pt-6 pb-8 px-9 border mb-6">

                            @if($sprint->tasks->count() > 0)
                                <h3 class="font-semibold text-sm">
                                    Connected Tasks
                                </h3>
                                <table class="w-full text-sm text-left bg-white border rounded">

                                    <thead class=" bg-gray-600 text-white text-left uppercase text-xs border-black">
                                        <tr>
                                            <th class="p-2 border text-center border">Task</th>
                                            <th class="p-2 border text-center border">Epic</th>
                                            <th class="p-2 border text-center border">Status</th>
                                            <th class="p-2 border text-center border">Type</th>
                                            <th class="p-2 border text-center border">Priority</th>
                                            <th class="p-3 text-center border">Due Date</th>
                                            <th class="p-2 border text-center border">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($sprint->normalTasks as $task)

                                            <tr class="border-t text-xs">

                                                <td class="p-2 border font-medium text-xs text-center">
                                                    {{ $task->title }}
                                                </td>

                                                <td class="p-2 text-xs border text-center ">
                                                    {{ $task->epic->title ?? 'Backlog' }}
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
                                <p class="text-gray-500">No tasks in this sprint</p>
                            @endif
                        </td>

                        <hr>

                    </tr>
                @empty
                    <tr>

                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No sprints found.
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
                <th class="p-3 border">Sprint</th>
                <th class="p-3 border text-center">Goal</th>
                <th class="p-3 border text-center">Timeline</th>
                <th class="p-3 border text-center">Status</th>
                <th class="p-3 border text-center">Progress</th>
                <th class="p-3 border text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($backlogSprints as $sprint)
                <tr class= "border-b cursor-pointer mb-6">

                        <td class="p-2 font-semibold border">
                            {{ $sprint->name }}
                        </td>

                        <td class="p-3 border">
                            {{ $sprint->goal ?? '-' }}
                        </td>

                        <td class="p-3 border">

                            <span>
                                {{ \Carbon\Carbon::parse($sprint->start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($sprint->end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-3 text-center border">
                            <span class="px-2 py-1 rounded text-xs
                                @if($sprint->status == 'planned')
                                    bg-red-200
                                @elseif($sprint->status == 'active')
                                    bg-yellow-200
                                @else
                                    bg-green-200
                                @endif
                            ">
                                {{ ucfirst($sprint->status) }}
                            </span>

                        </td>

                        <td class="p-3 text-center border">

                            <div class="w-full bg-gray-200 rounded h-2">

                                <div
                                    class="bg-green-500 h-2 rounded"
                                    style="width: {{ $sprint->progress }}%">
                                </div>

                            </div>

                            <span class="text-xs">
                                {{ $sprint->progress }}%
                            </span>
                        </td>

                        <td class="p-2 flex justify-end text-center border gap-2">
                            <button onclick="toggleBacklogSprint({{ $sprint->id }})"
                                        class="text-sm bg-blue-600 text-white px-1 rounded">
                                    Show Backlog Tasks
                            </button>
                        </td>
                </tr>

                <tr id="backlog-sprint-{{ $sprint->id }}" class="hidden  ">

                    <td colspan="7" class="pt-6 pb-8 px-9 border mb-6">

                        @if($sprint->backlogTasks->count() > 0)

                            <h3 class="font-semibold text-sm">
                                Backlog Tasks
                            </h3>

                            <table class="w-full text-sm text-left  border rounded">

                                    <thead class="bg-red-600 text-white uppercase text-xs">
                                    <tr>
                                        <th class="p-2 border text-center border">Task</th>
                                        <th class="p-2 border text-center border">Epic</th>
                                        <th class="p-2 border text-center border">Status</th>
                                        <th class="p-2 border text-center border">Type</th>
                                        <th class="p-2 border text-center border">Priority</th>
                                        <th class="p-3 text-center border">Due Date</th>
                                        <th class="p-2 border text-center border">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>


                                @foreach($sprint->backlogTasks as $task)
                                    <tr class="border-t text-xs">

                                        <td class="p-2 border font-medium text-xs text-center">
                                                {{ $task->title }}
                                        </td>

                                        <td class="p-2 border text-center">
                                            {{ $task->epic->title ?? 'Backlog' }}
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
                        No sprint found
                    </td>
                </tr>

            @endforelse

            </tbody>

    </table>


    @foreach($sprints as $sprint)
                <div class="border rounded-lg mb-8 border-black shadow-sm overflow-hidden mt-8">

            <div class="grid grid-cols-12 items-center gap-4 px-5 py-3  border-b text-sm font-medium text-gray-600">

                <div class="col-span-3">
                    <div class="font-bold text-base text-gray-900">
                        {{ $sprint->name }}
                    </div>
                </div>

                <div class="col-span-3 text-gray-600 truncate">
                    {{ $sprint->goal ?? 'No goal defined' }}
                </div>

                <div class="col-span-3 text-gray-700">
                    <span>
                        {{ \Carbon\Carbon::parse($sprint->start_date)->format('d M Y') }}
                    </span>
                    <span class="text-gray-400 mx-1">→</span>
                    <span>
                        {{ \Carbon\Carbon::parse($sprint->end_date)->format('d M Y') }}
                    </span>
                </div>

                <div class="col-span-1">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($sprint->status == 'planned')
                            bg-gray-200 text-gray-700
                        @elseif($sprint->status == 'active')
                            bg-blue-100 text-blue-700
                        @else
                            bg-green-100 text-green-700
                        @endif
                    ">
                        {{ ucfirst($sprint->status) }}
                    </span>
                </div>

                <div class="col-span-1">
                    <div class="flex items-center gap-2">
                        {{-- <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-2 rounded-full transition-all"
                                style="width: {{ $sprint->progress }}%">
                            </div>
                        </div> --}}
                        <span class="text-xs text-gray-600 w-10 text-right">
                            {{ $sprint->progress }}%
                        </span>
                    </div>
                </div>

                <div class="col-span-1 flex justify-end relative">

                    <button onclick="toggleMenu({{ $sprint->id }})"
                            class="p-1.5 rounded hover:bg-gray-200 text-black">
                        ⋯
                    </button>

                    <div id="menu-{{ $sprint->id }}"
                        class="hidden absolute right-0 top-full mt-2 w-40 bg-white border rounded-lg shadow-lg z-50 overflow-hidden">

                        <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Edit
                        </a>

                        <form method="POST"
                            action="{{ route('projects.sprints.destroy', [$project->id, $sprint->id]) }}">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Delete sprint?')"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Delete
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            <div class="p-5">

                <h3 class="font-semibold text-gray-700 m-3">
                    Connected Tasks
                </h3>

                @if($sprint->tasks->count() > 0)

                    <div >

                        @foreach($sprint->tasks as $task)

                        <div class="p-4 border rounded-lg bg-gray-50 hover:bg-gray-50 transition shadow-sm">

                        <div class="flex justify-between items-center gap-6">

                            <div class="flex items-center gap-8 min-w-0">

                                <h1 class="font-semibold text-gray-800 text-sm whitespace-nowrap">
                                    {{ $task->title }}
                                </h1>

                                <div class="flex items-center gap-6 text-xs text-gray-600 truncate">

                                    <span class="truncate px-2 py-0.5 rounded  bg-purple-100 text-purple-700 whitespace-nowrap">
                                        Epic: {{ $task->epic->title ?? 'N/A' }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded  text-blue-700 whitespace-nowrap">
                                        Status: {{ $task->statusModel->name ?? $task->status }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded text-green-700 whitespace-nowrap">
                                        Type: {{ $task->typeRelation->name ?? '' }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded  text-yellow-700 whitespace-nowrap">
                                        Priority: {{ $task->priority }}
                                    </span>

                                </div>

                            </div>

                            <div class="flex items-center gap-2">

                                <button onclick="openTaskModal({{ $task->id }})"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-100">
                                    Edit
                                </button>

                                <form method="POST"
                                    action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Delete this task?')"
                                            class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>


                        </div>

                        @endforeach

                    </div>

                @else
                    <p class="text-gray-500 text-sm">No tasks in this sprint</p>
                @endif

                @if($sprint->backlogTasks->count() > 0)

                                <h2 class="text-xl font-bold mt-6 mx-9 text-red-600">
                                    Backlog
                                </h2>

                                <div class="overflow-x-auto pt-3 pb-8 px-9">
                                    <table class="w-full text-sm text-left bg-white shadow border rounded">

                                        <thead class="bg-red-600 text-white uppercase text-xs">
                                            <tr>
                                                <th class="p-2 border text-center">Task</th>
                                                <th class="p-2 border text-center">Epic</th>
                                                <th class="p-2 border text-center">Status</th>
                                                <th class="p-2 border text-center">Type</th>
                                                <th class="p-2 border text-center">Priority</th>
                                                <th class="p-3 text-center border">Due Date</th>
                                                <th class="p-2 border text-center">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($sprint->backlogTasks as $task)

                                            <tr class="border-t text-xs">

                                                <td class="p-2 border font-medium text-xs text-center">
                                                        {{ $task->title }}
                                                </td>

                                                <td class="p-2 border text-center">
                                                    {{  $task->epic->title ??  'Backlog' }}
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
                                                    <button
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
                                </div>

                            @endif

            </div>

        </div>
    @endforeach

    <script>
        function toggleMenu(id) {

            let menu = document.getElementById('menu-' + id);

            // close all others
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                if (el !== menu) el.classList.add('hidden');
            });

            // toggle current
            menu.classList.toggle('hidden');
        }

        // click outside to close
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('[id^="menu-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });

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

    <script>
        function toggleSprint(id)
        {
            let row = document.getElementById('sprint-' + id);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

        function toggleBacklogSprint(id) {
            document
                .getElementById('backlog-sprint-' + id)
                .classList.toggle('hidden');
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

    </div>








@endsection
