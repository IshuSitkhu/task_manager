@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Edit Epic
        </h2>

        <a href="{{ route('projects.epics', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Epics
        </a>

    </div>

    <div class="bg-white p-6 rounded shadow">

        <form method="POST" action="{{ route('projects.epics.update', [$project->id, $epic->id]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1">Epic Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $epic->title) }}"
                       class="w-full border rounded p-2"
                       required
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          value="{{$epic->description}}"
                          rows="4">{{old('description' ,$epic->description )}}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-medium mb-1">Owner</label>
                    <select name="owner_id" class="w-full border rounded p-2" required>

                        <option value="">Select Project Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('owner_id', $epic->owner_id )== $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>

                        <option value="low" {{ old('priority', $epic->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{  old('priority', $epic->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority',  $epic->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority',$epic->priority )== 'critical' ? 'selected' : '' }}>Critical</option>

                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2" required>

                        <option value="not_started" {{ old('status', $epic->status )== 'not_started' ? 'selected' : '' }}>Not Started</option>
                        <option value="in_progress" {{ old('status', $epic->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="testing" {{ old('status', $epic->status )== 'testing' ? 'selected' : '' }}>Testing</option>
                        <option value="completed" {{ old('status',$epic->status) == 'completed' ? 'selected' : '' }}>Completed</option>

                    </select>
                </div>

                {{-- PROGRESS --}}
                <div>
                    <label class="block font-medium mb-1">Progress (%)</label>
                    <input type="number"
                           name="progress"
                           value="{{ old('progress', $epic->progress) }}"
                           class="w-full border rounded p-2"
                           min="0"
                           max="100">
                </div>


                <div>
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="text"
                        id="start_date"
                        name="planned_start_date"
                        value="{{ old('planned_start_date', $epic->planned_start_date) }}"
                        class="w-full border rounded p-2">
                </div>

                {{-- END DATE --}}
                <div>
                    <label class="block font-medium mb-1">End Date</label>
                    <input type="text"
                        id="end_date"
                        name="planned_end_date"
                        value="{{ old('planned_end_date', $epic->planned_end_date) }}"
                        class="w-full border rounded p-2">
                </div>

                {{-- CONNECTED TASKS (UI ONLY FOR NOW) --}}
                {{-- <div>
                    <label class="block font-medium mb-1">Connected Tasks</label>
                    <input type="text"
                           class="w-full border rounded p-2 bg-gray-100"
                           value="(Will be linked with Tasks module)"
                           disabled>
                </div> --}}

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
