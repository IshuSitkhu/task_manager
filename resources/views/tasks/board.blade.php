@extends('layouts.project')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold">Kanban Board</h2>
</div>

<div class="grid grid-cols-4 gap-4">

    {{-- TODO --}}
    <div class=" p-3 rounded border border-dark">

        <div class="flex justify-between items-center mb-3 p-3 h-10  bg-black">
            <h3 class="font-bold text-white">Todo</h3>

            <a href="{{ route('projects.tasks.create', $project->id) }}?status=todo"
               class="text-xs bg-black text-white px-2 py-1 rounded">
                + Add
            </a>
        </div>

        <div class="space-y-2">

            @foreach($tasks->where('status', 'todo') as $task)
                <div class="bg-white p-3 rounded shadow">
                    <div class="font-semibold">{{ $task->title }}</div>
                    <div class="text-xs text-gray-500">
                       Epic- {{ $task->epic->title ?? 'No Epic' }}
                    </div>
                </div>
            @endforeach

        </div>

    </div>

    {{-- IN PROGRESS --}}
    <div class="p-3 rounded border border-dark">

        <div class="flex justify-between items-center mb-3 p-3 h-10 bg-blue-600 ">
            <h3 class="font-bold text-white">In Progress</h3>

            <a href="{{ route('projects.tasks.create', $project->id) }}?status=in_progress"
               class="text-xs bg-blue-600 text-white px-2 py-1 rounded">
                + Add
            </a>
        </div>

        <div class="space-y-2">

            @foreach($tasks->where('status', 'in_progress') as $task)
                <div class="bg-white p-3 rounded shadow">
                    <div class="font-semibold">{{ $task->title }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $task->epic->title ?? 'No Epic' }}
                    </div>
                </div>
            @endforeach

        </div>

    </div>

    {{-- REVIEW --}}
    <div class=" p-3 rounded  border border-dark">

        <div class="flex justify-between items-center mb-3 p-3 h-10 bg-purple-600">
            <h3 class="font-bold text-white">Review</h3>

            <a href="{{ route('projects.tasks.create', $project->id) }}?status=review"
               class="text-xs bg-purple-600 text-white px-2 py-1 rounded">
                + Add
            </a>
        </div>

        <div class="space-y-2">

            @foreach($tasks->where('status', 'review') as $task)
                <div class="bg-white p-3 rounded shadow">
                    <div class="font-semibold">{{ $task->title }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $task->epic->title ?? 'No Epic' }}
                    </div>
                </div>
            @endforeach

        </div>

    </div>

    {{-- DONE --}}
    <div class=" p-3 rounded  border border-dark">

        <div class="flex justify-between items-center p-3 mb-3 h-10 bg-green-600">
            <h3 class="font-bold text-white ">Done</h3>

            <a href="{{ route('projects.tasks.create', $project->id) }}?status=done"
               class="text-xs bg-green-600 text-white px-2 py-1 rounded">
                + Add
            </a>
        </div>

        <div class="space-y-2">

            @foreach($tasks->where('status', 'done') as $task)
                <div class="bg-white p-3 rounded shadow">
                    <div class="font-semibold">{{ $task->title }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $task->epic->title ?? 'No Epic' }}
                    </div>
                </div>
            @endforeach

        </div>

    </div>

</div>

@endsection
