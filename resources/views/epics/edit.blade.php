@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Edit Epic
        </h2>

        <a href="{{ route('projects.epics', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Epics
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 rounded shadow">

        <form method="POST" action="{{ route('projects.epics.update', [$project->id, $epic->id]) }}">
            @csrf
            @method('PUT')

            {{-- TITLE --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Epic Title</label>
                <input type="text"
                       name="title"
                       value="{{ $epic->title }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          value="{{$epic->description}}"
                          rows="4">{{ $epic->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- OWNER --}}
                <div>
                    <label class="block font-medium mb-1">Owner</label>
                    <select name="owner_id" class="w-full border rounded p-2" required>

                        <option value="">Select Project Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $epic->owner_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>

                        <option value="low" {{ $epic->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $epic->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $epic->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ $epic->priority == 'critical' ? 'selected' : '' }}>Critical</option>

                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2" required>

                        <option value="not_started" {{ $epic->status == 'not_started' ? 'selected' : '' }}>Not Started</option>
                        <option value="in_progress" {{ $epic->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="testing" {{ $epic->status == 'testing' ? 'selected' : '' }}>Testing</option>
                        <option value="completed" {{ $epic->status == 'completed' ? 'selected' : '' }}>Completed</option>

                    </select>
                </div>

                {{-- PROGRESS --}}
                <div>
                    <label class="block font-medium mb-1">Progress (%)</label>
                    <input type="number"
                           name="progress"
                           value="{{ $epic->progress }}"
                           class="w-full border rounded p-2"
                           min="0"
                           max="100">
                </div>

                {{-- START DATE --}}
                <div>
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="date"
                           name="planned_start_date"
                           value="{{ $epic->planned_start_date }}"
                           class="w-full border rounded p-2">
                </div>

                {{-- END DATE --}}
                <div>
                    <label class="block font-medium mb-1">End Date</label>
                    <input type="date"
                           name="planned_end_date"
                           value="{{ $epic->planned_end_date }}"
                           class="w-full border rounded p-2">
                </div>

                {{-- CONNECTED TASKS (UI ONLY FOR NOW) --}}
                <div>
                    <label class="block font-medium mb-1">Connected Tasks</label>
                    <input type="text"
                           class="w-full border rounded p-2 bg-gray-100"
                           value="(Will be linked with Tasks module)"
                           disabled>
                </div>

            </div>

            {{-- SUBMIT --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Update Epic
                </button>
            </div>

        </form>

    </div>

@endsection