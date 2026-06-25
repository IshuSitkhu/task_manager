@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Create Epic
        </h2>

        <a href="{{ route('projects.epics', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Epics
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 rounded shadow">

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('projects.epics.store', $project->id) }}">
            @csrf

            {{-- TITLE --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Epic Title</label>
                <input type="text"
                       name="title"
                       class="w-full border rounded p-2"
                       placeholder="e.g. Authentication System"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="4"
                          placeholder="Describe the epic..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- OWNER --}}
                <div>
                    <label class="block font-medium mb-1">Owner</label>
                    <select name="owner_id" class="w-full border rounded p-2" required>

                        <option value="">Select Project Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2" required>
                        <option value="not_started">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="testing">Testing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                {{-- PROGRESS --}}
                <div>
                    <label class="block font-medium mb-1">Progress (%)</label>
                    <input type="number"
                           name="progress"
                           class="w-full border rounded p-2"
                           min="0"
                           max="100"
                           value="0">
                </div>

                {{-- START DATE --}}
                <div>
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="text"
                        id="start_date"
                        name="planned_start_date"
                        class="w-full border rounded p-2"
                        required>
                </div>



                {{-- END DATE --}}
                <div>
                    <label class="block font-medium mb-1">End Date</label>
                        <input type="text"
                            id="end_date"
                            name="planned_end_date"
                            class="w-full border rounded p-2"
                            required>
                </div>
{{--
                <div>
                    <label class="block font-medium mb-1"> Conected tasks</label>
                    <input type="text"
                           name="connected_task"
                           class="w-full border rounded p-2">
                </div> --}}
            </div>

            {{-- SUBMIT --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create Epic
                </button>
            </div>

        </form>

    </div>

@endsection
