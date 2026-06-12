@extends('layouts.project')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">Epics</h2>

        <a href="{{ route('projects.epics.create', $project->id) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            New Epic
        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Epic</th>
                    <th class="p-3">Owner</th>
                    <th class="p-3">Planned timeline</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Priority</th>
                    <th class="p-3">Progress</th>
                    <th class="p-3">Connected tasks</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($epics as $epic)

                    <tr class="border-b">

                        {{-- TITLE --}}
                        <td class="p-3 font-semibold">
                            {{ $epic->title }}
                        </td>

                        {{-- OWNER --}}
                        <td class="p-3">
                            {{ $epic->owner->name ?? 'N/A' }}
                        </td>

                        {{-- DATES --}}
                        <td class="p-3 text-xs">
                            <div>{{ $epic->planned_start_date ?? '-' }}</div>
                            <div>{{ $epic->planned_end_date ?? '-' }}</div>
                        </td>

                        {{-- STATUS --}}
                        <td class="p-3">
                            @php
                                $statusColors = [
                                    'not_started' => 'bg-gray-200 text-gray-700',
                                    'in_progress' => 'bg-blue-200 text-blue-800',
                                    'testing' => 'bg-purple-200 text-purple-800',
                                    'completed' => 'bg-green-200 text-green-800',
                                ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs {{ $statusColors[$epic->status] }}">
                                {{ str_replace('_', ' ', ucfirst($epic->status)) }}
                            </span>
                        </td>

                        {{-- PRIORITY --}}
                        <td class="p-3">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-200 text-green-800',
                                    'medium' => 'bg-yellow-200 text-yellow-800',
                                    'high' => 'bg-orange-200 text-orange-800',
                                    'critical' => 'bg-red-200 text-red-800',
                                ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs {{ $priorityColors[$epic->priority] }}">
                                {{ ucfirst($epic->priority) }}
                            </span>
                        </td>

                        {{-- PROGRESS --}}
                        <td class="p-3 w-40">
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-blue-500 h-2 rounded"
                                     style="width: {{ $epic->progress }}%"></div>
                            </div>
                            <span class="text-xs">{{ $epic->progress }}%</span>
                        </td>

                        <td class="p-3">
                            <span class="text-gray-500 text-sm">
                                0 tasks
                            </span>
                        </td>

                        

                        {{-- ACTIONS --}}
                        <td class="p-3 flex gap-2">

                            <a href="{{ route('projects.epics.edit', [$project->id, $epic->id]) }}"
                               class="text-blue-600">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('projects.epics.destroy', [$project->id, $epic->id]) }}">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600"
                                        onclick="return confirm('Delete epic?')">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No epics found. Create your first epic.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

@endsection