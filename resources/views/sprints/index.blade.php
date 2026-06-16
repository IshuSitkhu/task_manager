@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
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
    {{-- <div class="bg-white rounded shadow overflow-x-auto mb-12">

         <table class="w-full text-sm text-left">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-3 text-left">Sprint</th>
                    <th class="p-3 text-left">Goal</th>
                    <th class="p-3 text-left">Timeline</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Progress</th>
                    <th class="p-3 text-left">Connected Tasks</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($sprints as $sprint)

                    <tr class="border-b">

                        <td class="p-3 font-medium">
                            {{ $sprint->name }}
                        </td>

                        <td class="p-3">
                            {{ $sprint->goal ?? '-' }}
                        </td>

                        <td class="p-3">

                            @if($sprint->start_date || $sprint->end_date)

                                {{ $sprint->start_date }}

                                →

                                {{ $sprint->end_date }}

                            @else

                                -

                            @endif

                        </td>

                        <td class="p-3">

                            <span class="px-2 py-1 rounded text-xs
                                @if($sprint->status == 'planned')
                                    bg-gray-200
                                @elseif($sprint->status == 'active')
                                    bg-blue-200
                                @else
                                    bg-green-200
                                @endif
                            ">
                                {{ ucfirst($sprint->status) }}
                            </span>

                        </td>

                        <td class="p-3">

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

                        <td class="p-3">

                            <span class="text-gray-600">
                                {{ $sprint->tasks->count() }} Tasks
                            </span>

                        </td>

                        <td class="p-3">

                            <div class="flex gap-2">

                                <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                                   class="text-blue-600">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('projects.sprints.destroy', [$project->id, $sprint->id]) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete sprint?')"
                                        class="text-red-600">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

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
    </div> --}}


    @foreach($sprints as $sprint)
                <div class="border rounded-lg mb-8 bg-white shadow-sm overflow-hidden">

            <div class="grid grid-cols-12 items-center gap-4 px-5 py-3  border-b text-sm font-medium text-gray-600">

                <div class="col-span-3">
                    <div class="font-semibold text-gray-900">
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
                            class="p-1.5 rounded hover:bg-gray-200 text-gray-600">
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

                <h3 class="font-semibold text-gray-700 mb-3">
                    Connected Tasks
                </h3>

                @if($sprint->tasks->count() > 0)

                    <div >

                        @foreach($sprint->tasks as $task)

                        <div class="p-4 border rounded-lg bg-gray-50 hover:bg-gray-50 transition shadow-sm">

                        <div class="flex justify-between items-center gap-6">

                            <div class="flex items-center gap-8 min-w-0">

                                <h1 class="font-semibold text-gray-800 text-base whitespace-nowrap">
                                    {{ $task->title }}
                                </h1>

                                <div class="flex items-center gap-6 text-xs text-gray-600 truncate">

                                    <span class="truncate px-2 py-0.5 rounded  bg-purple-100 text-purple-700 whitespace-nowrap">
                                        Epic: {{ $task->epic->title ?? 'N/A' }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded  text-blue-700 whitespace-nowrap">
                                        Status: {{ $task->status }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded text-green-700 whitespace-nowrap">
                                        Type: {{ $task->type }}
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

            <div id="taskModal"
                class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-3xl p-6 rounded shadow">

                    <div class="flex justify-between mb-4">
                        <h2 class="text-xl font-bold">Edit Task</h2>
                        <button onclick="closeModal()">✕</button>
                    </div>

                    <div id="modalBody">
                        {{-- AJAX FORM WILL LOAD HERE --}}
                    </div>

            </div>

    </div>








@endsection
