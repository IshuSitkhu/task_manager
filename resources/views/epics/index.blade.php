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

        <table class="w-full text-sm text-left">

            <thead class="bg-gray-100 ">
                <tr>
                    <th class="p-3">Epic</th>
                    <th class="p-3">Owner</th>
                    <th class="p-3">Planned timeline</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Priority</th>
                    <th class="p-3">Progress</th>
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


                    <tr class="bg-white ">

                        <td class="p-3 font-semibold">
                            {{ $epic->title }}
                        </td>

                        <td class="p-3">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        <td class="p-3 text-sm">

                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_start_date)->format('d M Y') }}
                            </span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span>
                                {{ \Carbon\Carbon::parse($epic->planned_end_date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm bg-gray-200">
                                {{ ucfirst($epic->status) }}
                            </span>
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm bg-yellow-200">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>

                        <td class="p-3">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-green-500 h-2 rounded"
                                     style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">
                            {{ $epic->progress }}%
                        </td>

                        <td class="p-3 flex justify-end">
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

                    <tr id="epic-{{ $epic->id }}" class="hidden ">

                        <td colspan="7" class="p-4 border-b ">

                            @if($epic->tasks->count() > 0)
                                <h3 class="font-semibold text-sm text-gray-700 ">
                                    Connected Tasks
                                </h3>
                                <table class="w-full text-sm text-left bg-gray-50 border rounded">

                                    <thead class="text-left text-black">
                                        <tr>
                                            <th class="p-2">Task</th>
                                            <th class="p-2">Sprint</th>
                                            <th class="p-2">Status</th>
                                            <th class="p-2">Type</th>
                                            <th class="p-2">Priority</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($epic->tasks as $task)

                                            <tr class="border-t">

                                                <td class="p-2 font-medium">
                                                    {{ $task->title }}
                                                </td>

                                                <td class="p-2 text-purple-600">
                                                    {{ $task->sprint ?  $task->sprint->name : 'Backlog' }}
                                                </td>

                                                <td class="p-2 text-blue-600">
                                                   {{ $task->status }}
                                                </td>

                                                <td class="p-2 text-green-600">
                                                    {{ $task->type }}
                                                </td>

                                                <td class="p-2 text-yellow-600">
                                                    {{ $task->priority }}
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
        function toggleEpic(id)
        {
            let row = document.getElementById('epic-' + id);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

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


    </script>

@endsection
