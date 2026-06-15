@extends('layouts.project')

@section('content')

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">
        Edit Sprint
    </h2>

    <a href="{{ route('projects.sprints', $project->id) }}"
       class="text-blue-600 hover:underline">
        ← Back to Sprints
    </a>

</div>

<div class="bg-white p-6 rounded shadow">

    <form method="POST"
          action="{{ route('projects.sprints.update', [$project->id, $sprint->id]) }}">

        @csrf
        @method('PUT')

        {{-- NAME --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Sprint Name</label>
            <input type="text"
                   name="name"
                   value="{{ $sprint->name }}"
                   class="w-full border rounded p-2"
                   required>
        </div>

        {{-- GOAL --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Sprint Goal</label>
            <textarea name="goal"
                      class="w-full border rounded p-2"
                      rows="4">{{ $sprint->goal }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- START DATE --}}
            <div>
                <label class="block font-medium mb-1">Start Date</label>
                <input type="date"
                       name="start_date"
                       value="{{ $sprint->start_date }}"
                       class="w-full border rounded p-2">
            </div>

            {{-- END DATE --}}
            <div>
                <label class="block font-medium mb-1">End Date</label>
                <input type="date"
                       name="end_date"
                       value="{{ $sprint->end_date }}"
                       class="w-full border rounded p-2">
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block font-medium mb-1">Status</label>
                <select name="status"
                        class="w-full border rounded p-2">

                    <option value="planned" {{ $sprint->status == 'planned' ? 'selected' : '' }}>
                        Planned
                    </option>

                    <option value="active" {{ $sprint->status == 'active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="closed" {{ $sprint->status == 'closed' ? 'selected' : '' }}>
                        Closed
                    </option>

                </select>
            </div>

            {{-- PROGRESS --}}
            <div>
                <label class="block font-medium mb-1">Progress (%)</label>
                <input type="number"
                       name="progress"
                       value="{{ $sprint->progress }}"
                       min="0"
                       max="100"
                       class="w-full border rounded p-2">
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="mt-6 flex justify-end">
            <button class="bg-blue-600 text-white px-6 py-2 rounded">
                Update Sprint
            </button>
        </div>

    </form>

</div>

@endsection
