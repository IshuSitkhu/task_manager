@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Create Epic
        </h2>

        <a href="{{ route('projects.epics', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Epics
        </a>

    </div>

    <div class="bg-white p-6 rounded shadow">

        <form method="POST" action="{{ route('projects.epics.store', $project->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Epic Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="w-full border rounded p-2"
                       placeholder="e.g. Authentication System"
                       required
                >

                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="4"
                          placeholder="Describe the epic...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-medium mb-1">Owner</label>
                    <select name="owner_id" class="w-full border rounded p-2" required>

                        <option value="">Select Project Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('owner_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>

                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2" required>
                        <option value="not_started" {{ old('status') == 'not_started' ? 'selected' : '' }} >Not Started</option>
                        <option value="in_progress" {{ old('in_progress') == 'not_started' ? 'selected' : '' }} >In Progress</option>
                        <option value="testing" {{ old('testing') == 'not_started' ? 'selected' : '' }} >Testing</option>
                        <option value="completed" {{ old('completed') == 'not_started' ? 'selected' : '' }}>Completed</option>
                    </select>

                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Progress (%)</label>
                    <input type="number"
                           name="progress"
                           class="w-full border rounded p-2"
                           value="{{ old('progress') }}"
                           min="0"
                           max="100"
                           value="0">
                </div>

                <div>
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="text"
                        id="start_date"
                        name="planned_start_date"
                        value="{{ old('planned_start_date') }}"
                        class="w-full border rounded p-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium mb-1">End Date</label>
                        <input type="text"
                            id="end_date"
                            name="planned_end_date"
                            value="{{ old('planned_end_date') }}"
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

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create Epic
                </button>
            </div>

        </form>

    </div>

@endsection
