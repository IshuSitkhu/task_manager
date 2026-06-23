@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">Epics</h2>

        <a href="{{ route('projects.epics.create', $project->id) }}"
           class="px-3 py-1 bg-black text-white rounded">
            New Epic
        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm text-left ">

            <thead class="bg-black/80 text-white uppercase text-xs">
                <tr>
                    <th class="p-3">Epic</th>
                    <th class="p-3 text-center">Owner</th>
                    <th class="p-3 text-center">Planned timeline</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Priority</th>
                    <th class="p-3 text-center">Progress</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>

            {{-- <tbody>

                @forelse($epics as $epic)

                    <tr class="border-b">

                        <td class="p-3 font-semibold">
                            {{ $epic->title }}
                        </td>

                        <td class="p-3">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        <td class="p-3 text-xs">
                            <div>{{ $epic->planned_start_date ?? '-' }}</div>
                            <div>{{ $epic->planned_end_date ?? '-' }}</div>
                        </td>

                        <td class="p-3">
                            @php
                                $statusColors = [
                                    'not_started' => 'bg-gray-200 text-gray-700',
                                    'in_progress' => 'bg-blue-200 text-blue-800',
                                    'testing' => 'bg-purple-200 text-purple-800',
                                    'completed' => 'bg-green-200 text-green-800',
                                ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs {{ $statusColors[$epic->status] }}">
                                {{ str_replace('_', ' ', ucfirst($epic->status)) }}
                            </span>
                        </td>

                        <td class="p-3">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-200 text-green-800',
                                    'medium' => 'bg-yellow-200 text-yellow-800',
                                    'high' => 'bg-orange-200 text-orange-800',
                                    'critical' => 'bg-red-200 text-red-800',
                                ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs {{ $priorityColors[$epic->priority] }}">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>


                        <td class="p-3 w-20">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-blue-500 h-2 rounded"
                                     style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs">{{ $epic->progress }}%</span>
                        </td>


                        <td class="pt-5 flex gap-3">

                            <a href="{{ route('projects.epics.edit', [$project->id, $epic->id]) }}"
                               class="text-blue-600 ">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('projects.epics.destroy', [$project->id, $epic->id]) }}">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600"
                                        onclick="return confirm('Delete epic?')">
                                       Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No epics found. Create your first epic.
                        </td>
                    </tr>

                @endforelse

            </tbody> --}}

            <tbody>

                @forelse($epics as $epic)


                    <tr class="bg-white border-b hover:bg-gray-50 cursor-pointer mb-6">

                        <td class="p-2 font-semibold">
                            {{ $epic->title }}
                        </td>

                        <td class="p-2 text-sm text-gray-800 text-center">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        <td class="p-2 text-xs ">

                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-2 text-center">
                            <span class="px-2 py-1 rounded text-sm text-white
                            @if($epic->status == 'not_started')  bg-red-500
                            @elseif($epic->status == 'in_progress') bg-blue-500
                            @elseif($epic->status == 'testing') bg-yellow-500
                            @else bg-green-800 @endif
                            ">
                                {{ ucfirst($epic->status) }}
                            </span>
                        </td>

                        <td class="p-2 text-center">
                            <span class="px-2 py-1 rounded text-sm
                                 @if($epic->priority == 'low') text-green-800
                                @elseif($epic->priority == 'medium') text-yellow-800
                                @elseif($epic->priority == 'high') text-blue-800
                                @else text-red-800 @endif">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>

                        <td class="p-2 text-center">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-green-500 h-2 rounded"
                                     style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">
                            {{ $epic->progress }}%
                        </td>

                        <td class="p-2 flex justify-end text-center">
                            <button onclick="toggleEpic({{ $epic->id }})"
                                        class="text-sm text-blue-600">
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

                    <tr id="epic-{{ $epic->id }}" class="hidden  bg-black/80  ">

                        <td colspan="7" class="p-4 border-b mb-6">

                            @if($epic->tasks->count() > 0)
                                <h3 class="font-semibold text-sm text-white ">
                                    Connected Tasks
                                </h3>
                                <table class="w-full text-sm text-left bg-gray-50 border  rounded">

                                    <thead class=" text-left border-black">
                                        <tr>
                                            <th class="p-2 text-center">Task</th>
                                            <th class="p-2 text-center">Sprint</th>
                                            <th class="p-2 text-center">Status</th>
                                            <th class="p-2 text-center">Type</th>
                                            <th class="p-2 text-center">Priority</th>
                                            <th class="p-2 text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($epic->tasks as $task)

                                            <tr class="border-t text-xs">

                                                <td class="p-2 font-medium text-xs text-center">
                                                    {{ $task->title }}
                                                </td>

                                                <td class="p-2 text-xs text-center ">
                                                    {{ $task->sprint ?  $task->sprint->name : 'Backlog' }}
                                                </td>

                                                <td class="p-2  text-center">
                                                   @php
                                                        $status = $task->projectStatus;
                                                    @endphp

                                                    <span class="px-2 py-1 rounded text-white text-xs"
                                                        style="background-color: {{ $status->color ?? '#6b7280' }}">

                                                        {{ $status->name ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>

                                                <td class="p-2 text-center ">
                                                    @php
                                                        $type = $task->projectType;
                                                    @endphp

                                                    <span class="px-2 py-1 rounded text-white text-xs"
                                                        style="background-color: {{ ($task->type)->color ?? '#6b7280' }}">

                                                        {{ ($task->type)->name  ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>

                                                <td class="p-2 text-yellow-600 text-center">
                                                    <span class="px-2 py-1 rounded text-xs
                                                        @if($task->priority == 'low') text-green-600
                                                        @elseif($task->priority == 'medium') text-yellow-600
                                                        @elseif($task->priority == 'high') text-orange-600
                                                        @else text-red-600 @endif">

                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>

                                                <td class="p-2 flex text-xs gap-3 justify-center">
                                                    {{-- <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}"
                                                       class="text-sm text-blue-600">
                                                        Edit
                                                    </a> --}}

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

                <div class="bg-white w-full max-w-3xl p-6 rounded shadow">

                    <div class="flex py-6 justify-between ">
                        <h2 class="  text-xl font-bold">Edit Task</h2>
                        <button onclick="closeModal()">✕</button>
                    </div>

                    <div id="modalBody">
                        {{-- AJAX FORM WILL LOAD HERE --}}
                    </div>

    </div>

@endsection
