@extends('layouts.project')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">
            Bug List
        </h2>

        <p class="text-gray-600 mt-1">
            Project:
            <span class="font-semibold">{{ $project->name }}</span>
        </p>
    </div>

        <span class="bg-gray-100 px-3 py-2 rounded text-sm font-semibold">
            Total Bugs: {{ $bugs->count() }}
        </span>
</div>


<div class="overflow-x-auto">

    <table class="min-w-full border border-gray-300">

        <thead class="bg-black/80 text-white uppercase text-xs">

        <tr>

            <th class="border px-4 py-2 text-left">#</th>

            <th class="border px-4 py-2 text-left">
                Title
            </th>

            <th class="border px-4 py-2 text-left">
                Description
            </th>

            <th class="border px-4 py-2 text-left">
                Task
            </th>

            <th class="border px-4 py-2 text-left">
                Severity
            </th>

            <th class="border px-4 py-2 text-left">
                Status
            </th>

            <th class="border px-4 py-2 text-left">
                Assigned To
            </th>

            <th class="border px-4 py-2 text-left">
                Action
            </th>

        </tr>

        </thead>

        <tbody>

        @forelse($bugs as $bug)

            <tr class="hover:bg-gray-50">

                <td class="border px-4 py-2">
                    {{ $loop->iteration }}
                </td>

                <td class="border px-4 py-2">
                    <div class="font-semibold">
                        {{ $bug->title }}
                    </div>
                </td>

                <td class="border px-4 py-2">
                    <div class="text-sm text-gray-500">
                            {{ Str::limit($bug->description,60) }}
                    </div>
                </td>

                <td class="border px-4 py-2 text-sm">
                    {{ $bug->task->title ?? '-' }}
                </td>

                <td class="border px-4 py-2">

                    @if($bug->severity=='critical')
                        <span class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                            Critical
                        </span>

                    @elseif($bug->severity=='medium')
                        <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                            Medium
                        </span>

                    @else
                        <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                            Low
                        </span>

                    @endif

                </td>

                <td  class="border px-4 py-2">

                    @if($bug->status=='fixed')
                    <span class="   text-sm">
                            Fixed
                    </span>

                    @elseif($bug->status=='in_progress')
                        <span class="   text-sm">
                            In Progress
                        </span>

                    @else
                        <span class=" text-sm">
                            Open
                        </span>

                    @endif



                </td>

                <td class="border px-4 py-2 text-sm">
                    {{ $bug->assignee->name ?? '-' }}
                </td>

                <td class="border px-4 py-2">

                    <div class="flex gap-2">
                        <button
                            onclick="openEditBugModal({{ $bug->id }})"
                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                            Edit
                        </button>

                        <form method="POST"
                            action="{{ route('projects.bugs.destroy', [$project, $bug]) }}">

                            @csrf
                            @method('DELETE')

                            <button
                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">

                                Delete

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="8" class="text-center py-8 text-gray-500">

                    No bugs reported yet.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div id="BugEditModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded w-[650px] p-5">

        <div class="flex justify-between mb-4">

            <h2 class="text-xl font-bold">
                Edit Bug
            </h2>

            <button onclick="closeEditBugModal()">
                ✕
            </button>

        </div>

        <div id="bugEditBody">

        </div>

    </div>

</div>

<script>
function openEditBugModal(id)
{
    fetch(`/projects/{{ $project->id }}/bugs/${id}/edit`)
        .then(res => res.text())
        .then(html => {

            document.getElementById('bugEditBody').innerHTML = html;

            document
                .getElementById('BugEditModal')
                .classList.remove('hidden');

        });
}

function closeEditBugModal()
{
    document
        .getElementById('BugEditModal')
        .classList.add('hidden');
}



</script>


@endsection
