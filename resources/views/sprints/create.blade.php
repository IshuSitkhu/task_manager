@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Create Sprint
        </h2>

        <a href="{{ route('projects.sprints', $project->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Sprints
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 rounded shadow">

        <form method="POST"
              action="{{ route('projects.sprints.store', $project->id) }}">

            @csrf

            {{-- SPRINT NAME --}}
            <div class="mb-4">

                <label class="block font-medium mb-1">
                    Sprint Name
                </label>

                <input type="text"
                       name="name"
                       class="w-full border rounded p-2"
                       placeholder="e.g. Sprint 1"
                       required>

            </div>

            {{-- GOAL --}}
            <div class="mb-4">

                <label class="block font-medium mb-1">
                    Sprint Goal
                </label>

                <textarea name="goal"
                          rows="4"
                          class="w-full border rounded p-2"
                          placeholder="Describe sprint goal..."></textarea>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- START DATE --}}
                <div>

                    <label class="block font-medium mb-1">
                        Start Date
                    </label>

                    <input type="date"
                           name="start_date"
                           class="w-full border rounded p-2">

                </div>

                {{-- END DATE --}}
                <div>

                    <label class="block font-medium mb-1">
                        End Date
                    </label>

                    <input type="date"
                           name="end_date"
                           class="w-full border rounded p-2">

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="block font-medium mb-1">
                        Status
                    </label>

                    <select name="status"
                            class="w-full border rounded p-2"
                            required>

                        <option value="planned" selected>
                            Planned
                        </option>

                        <option value="active">
                            Active
                        </option>

                        <option value="closed">
                            Closed
                        </option>

                    </select>

                </div>

                {{-- PROGRESS --}}
                <div>

                    <label class="block font-medium mb-1">
                        Progress (%)
                    </label>

                    <input type="number"
                           name="progress"
                           min="0"
                           max="100"
                           value="0"
                           class="w-full border rounded p-2">

                </div>

            </div>

            {{-- SUBMIT --}}
            <div class="mt-6 flex justify-end">

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create Sprint
                </button>

            </div>

        </form>

    </div>

@endsection
