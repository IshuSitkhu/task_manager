@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Edit Task
        </h2>

        <a href="{{ route('projects.tasks', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Tasks
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 rounded shadow">

        <form method="POST"
              action="{{ route('projects.tasks.update', [$project->id, $task->id]) }}">

            @csrf
            @method('PUT')

            {{-- TITLE --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Task Title</label>
                <input type="text"
                       name="title"
                       value="{{ $task->title }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="4">{{ $task->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- EPIC --}}
                <div>
                    <label class="block font-medium mb-1">Epic</label>
                    <select name="epic_id" class="w-full border rounded p-2" required>

                        @foreach($epics as $epic)
                            <option value="{{ $epic->id }}"
                                {{ $task->epic_id == $epic->id ? 'selected' : '' }}>
                                {{ $epic->title }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- SPRINT --}}
                <div>
                    <label class="block font-medium mb-1">Sprint</label>
                    <select name="sprint_id" class="w-full border rounded p-2">

                        <option value="">No Sprint</option>

                        @foreach($sprints as $sprint)
                            <option value="{{ $sprint->id }}"
                                {{ $task->sprint_id == $sprint->id ? 'selected' : '' }}>
                                {{ $sprint->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- ASSIGNEE --}}
                <div>
                    <label class="block font-medium mb-1">Assignee</label>
                    <select name="assigned_to" class="w-full border rounded p-2" required>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="block font-medium mb-1">Type</label>
                    <select name="type" class="w-full border rounded p-2">

                        @foreach(['feature','bug','ui','backend','test'] as $type)
                            <option value="{{ $type }}"
                                {{ $task->type == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2">

                        @foreach(['low','medium','high','critical'] as $priority)
                            <option value="{{ $priority }}"
                                {{ $task->priority == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2">

                        @foreach(['todo','in_progress','review','done'] as $status)
                            <option value="{{ $status }}"
                                {{ $task->status == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $status)) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- GITHUB LINK --}}
                <div>
                    <label class="block font-medium mb-1">GitHub Link</label>
                    <input type="text"
                           name="github_link"
                           value="{{ $task->github_link }}"
                           class="w-full border rounded p-2">
                </div>

                {{-- DUE DATE --}}
                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="date"
                           name="due_date"
                           value="{{ $task->due_date }}"
                           class="w-full border rounded p-2">
                </div>

            </div>

            {{-- SUBMIT --}}
            <div class="mt-6 flex justify-end">

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Update Task
                </button>

            </div>

        </form>

    </div>

@endsection
