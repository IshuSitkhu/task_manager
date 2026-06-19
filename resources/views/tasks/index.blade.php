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

{{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

{{-- TASK TABLE --}}
<div class="bg-white shadow rounded overflow-x-auto">

    <table class="w-full text-sm text-left text-gray-500 ">

        <thead class="bg-black/80 text-white uppercase text-xs">
            <tr>
                <th class="p-3 text-left">Task</th>
                <th class="p-3 text-left">Epic</th>
                <th class="p-3 text-left">Sprint</th>
                <th class="p-3 text-left">Assignee</th>
                <th class="p-3 text-left">Priority</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y" >

            @forelse($tasks as $task)
                <tr class="border-b">

                    <td class="p-3 font-semibold">
                        {{ $task->title }}
                    </td>

                    <td class="p-3">
                        {{ $task->epic->title ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $task->sprint->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $task->assignee->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-white text-xs
                            @if($task->priority == 'low') bg-green-500
                            @elseif($task->priority == 'medium') bg-yellow-500
                            @elseif($task->priority == 'high') bg-orange-500
                            @else bg-red-500 @endif">

                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>

                    <td class="p-3">
                        @php
                            $status = $task->projectStatus;
                        @endphp

                        <span class="px-2 py-1 rounded text-white text-xs"
                            style="background-color: {{ $status->color ?? '#6b7280' }}">

                            {{ $status->name ?? ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>

                    <td class="p-3">
                        {{ ucfirst($task->type) }}
                    </td>

                    <td class="p-3 flex gap-2">
                        <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}" class="text-blue-600">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}">
                            @csrf
                            @method('DELETE')

                            <button class="text-red-600">
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

@endsection
