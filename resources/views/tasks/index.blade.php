@extends('layouts.project')

@section('content')

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">
        Tasks
    </h2>

    <a href="{{ route('projects.tasks.create', $project->id) }}"
       class="px-3 py-1 bg-black text-white rounded">
        Create Task
    </a>

</div>

<div class="bg-white shadow rounded overflow-x-auto">

    <table class="w-full text-sm text-left ">

        <thead class="bg-black/80 text-white uppercase text-xs">
            <tr>
                <th class="border p-3 ">Task</th>
                <th class="border p-3 text-center">Epic</th>
                <th class=" border p-3 text-center">Sprint</th>
                <th class="border p-3 text-center">Assignee</th>
                <th class=" border p-3 text-center">Priority</th>
                <th class="border p-3 text-center">Status</th>
                <th class="border p-3 text-center">Type</th>
                <th class="border p-3 text-center">Due Dates</th>
                <th class="border p-3 text-center">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y" >

            @forelse($tasks as $task)
                <tr class="bg-white border-b hover:bg-gray-50 cursor-pointer mb-6">

                <td class="border p-3">
                    <div class="font-semibold">
                        {{ $task->title }}
                    </div>
                </td>

                    <td class="border p-3 text-sm text-center text-gray-800">
                        {{ $task->epic->title ?? '-' }}
                    </td>

                    <td class="border p-3 text-sm text-center text-gray-800">
                        {{ $task->sprint->name ?? '-' }}
                    </td>

                    <td class="border p-3 text-sm text-center text-gray-800">
                        {{ $task->assignee->name ?? '-' }}
                    </td>

                    <td class="border p-3 text-center">
                        <span class="px-2 py-1 rounded text-sm
                            @if($task->priority == 'low') text-green-600
                            @elseif($task->priority == 'medium') text-yellow-600
                            @elseif($task->priority == 'high') text-orange-600
                            @else text-red-600 @endif">

                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>

                    <td class="border p-3 text-center">
                        @php
                            $status = $task->projectStatus;
                        @endphp

                        <span class="px-2 py-1 rounded text-white text-xs"
                            style="background-color: {{ $status->color ?? '#6b7280' }}">

                            {{ $status->name ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>

                    <td class="border p-3 text-center ">
                        <span class="px-2 py-1 rounded text-sm">
                            {{-- @if($task->type == 'bug') text-red-600
                            @elseif($task->type == 'feature') text-green-600
                            @elseif($task->type == 'ui') text-blue-600
                            @elseif($task->type == 'backend') text-yellow-600
                            @else text-gray-600 @endif"> --}}

                        @php
                            $type = $task->projectType;
                        @endphp

                        <span class="px-2 py-1 rounded text-white text-xs"
                            style="background-color: {{ ($task->type)->color ?? '#6b7280' }}">

                            {{ ($task->type)->name  ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
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

                    <td class="border p-3 flex gap-2 ">
                        <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}" class="hover:text-blue-400 text-blue-600">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                            onclick="confirmDelete(this.form)"
                            class="text-red-600 hover:text-red-400">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">
                        No tasks found. Create your first task.
                    </td>
                </tr>
            @endforelse

        </tbody>

    </table>

</div>


<div class="mt-10">

    <h2 class="font-semibold text-sm mb-4 text-red-600">
        Backlog
    </h2>

    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead class="bg-red-600 text-white uppercase text-xs">
                <tr>
                    <th class="p-3 border text-center border">Task</th>
                    <th class="p-3 border text-center border">Epic</th>
                    <th class="p-2 border text-center border">Sprint</th>
                    <th class="p-3 border text-center border">Assignee</th>
                    <th class="p-3 border text-center border">Priority</th>
                    <th class="p-3 border text-center border">Due Date</th>
                    <th class="p-3 border text-center border">Status</th>
                    <th class="p-3 border text-center border">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($backlogTasks as $task)

                    <tr class="border-b hover:bg-red-50">

                        <td class="p-3 font-semibold border">
                            {{ $task->title }}
                        </td>

                        <td class="p-3 border text-center">
                            {{ $task->epic->title ?? '-' }}
                        </td>

                        <td class="p-2 border text-center border">
                            {{ $task->sprint?->name ?? 'Backlog' }}
                        </td>

                        <td class="p-3 border text-center border">
                            {{ $task->assignee->name ?? '-' }}
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

                        <td class="p-3 border text-center">
                            <span
                                class="px-2 py-1 rounded text-white text-xs"
                                style="background-color: {{ $task->projectStatus->color ?? '#6b7280' }}">

                                {{ $task->projectStatus->name ?? ucfirst($task->status) }}

                            </span>
                        </td>

                        <td class="border p-3 flex gap-2  text-center justify-center">
                            <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}" class="hover:text-blue-400 text-blue-600">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                onclick="confirmDelete(this.form)"
                                class="text-red-600 hover:text-red-400">
                                    Delete
                                </button>
                            </form>
                        </td>


                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                             No backlog tasks.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

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

@endsection
