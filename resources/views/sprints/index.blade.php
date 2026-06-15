@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">
            Sprints
        </h2>

        <a href="{{ route('projects.sprints.create', $project->id) }}"
           class="px-3 py-1 bg-black text-white rounded">
            New Sprint
        </a>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded shadow overflow-x-auto">

         <table class="w-full text-sm text-left">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-3 text-left">Sprint</th>
                    <th class="p-3 text-left">Goal</th>
                    <th class="p-3 text-left">Timeline</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Progress</th>
                    <th class="p-3 text-left">Connected Tasks</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($sprints as $sprint)

                    <tr class="border-b">

                        {{-- Sprint Name --}}
                        <td class="p-3 font-medium">
                            {{ $sprint->name }}
                        </td>

                        {{-- Goal --}}
                        <td class="p-3">
                            {{ $sprint->goal ?? '-' }}
                        </td>

                        {{-- Timeline --}}
                        <td class="p-3">

                            @if($sprint->start_date || $sprint->end_date)

                                {{ $sprint->start_date }}

                                →

                                {{ $sprint->end_date }}

                            @else

                                -

                            @endif

                        </td>

                        {{-- Status --}}
                        <td class="p-3">

                            <span class="px-2 py-1 rounded text-xs
                                @if($sprint->status == 'planned')
                                    bg-gray-200
                                @elseif($sprint->status == 'active')
                                    bg-blue-200
                                @else
                                    bg-green-200
                                @endif
                            ">
                                {{ ucfirst($sprint->status) }}
                            </span>

                        </td>

                        {{-- Progress --}}
                        <td class="p-3">

                            <div class="w-full bg-gray-200 rounded h-2">

                                <div
                                    class="bg-green-500 h-2 rounded"
                                    style="width: {{ $sprint->progress }}%">
                                </div>

                            </div>

                            <span class="text-xs">
                                {{ $sprint->progress }}%
                            </span>

                        </td>

                        {{-- Connected Tasks --}}
                        <td class="p-3">

                            <span class="text-gray-600">
                                {{ $sprint->tasks->count() }} Tasks
                            </span>

                        </td>

                        {{-- Actions --}}
                        <td class="p-3">

                            <div class="flex gap-2">

                                <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                                   class="text-blue-600">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('projects.sprints.destroy', [$project->id, $sprint->id]) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete sprint?')"
                                        class="text-red-600">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No sprints found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

@endsection
