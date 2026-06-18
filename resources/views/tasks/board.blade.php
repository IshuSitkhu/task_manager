@extends('layouts.project')

@section('content')

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Kanban Board</h2>

    <button onclick="openStatusModal()"
        class="px-3 py-1 bg-black text-white rounded">
        + Add New Status
    </button>
</div>

<div class="grid grid-cols-4 gap-4">

    @foreach($project->statuses as $status)

        <div class="p-3 rounded border">

            <div class="flex justify-between items-center mb-3 p-3 h-10"
                 style="background: {{ $status->color ?? '#000' }}">

                <h3 class="font-bold text-white">
                    {{ $status->name }}
                </h3>

                <a href="{{ route('projects.tasks.create', $project->id) }}?status={{ $status->slug }}"
                   class="text-xs bg-white text-black px-2 py-1 rounded">
                    + Add
                </a>
            </div>

            <div class="space-y-2">

                @foreach($tasks->where('status', $status->slug) as $task)
                    <div class="bg-white p-3 rounded shadow">
                        <div class="font-semibold">{{ $task->title }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $task->epic->title ?? 'No Epic' }}
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    @endforeach

</div>

<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white p-6 rounded w-96">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Add New Status</h2>
            <button onclick="closeStatusModal()" class="text-black font-bold text-lg">
                X
            </button>
        </div>

        <form method="POST" action="{{ route('projects.statuses.store', $project->id) }}">
            @csrf

            <input type="text" name="name" placeholder="Status Name"
                class="w-full border p-2 mb-3" required>

            <input type="text" name="slug" placeholder="slug (no spaces)"
                class="w-full border p-2 mb-3" required>

            <div class="mb-3 flex">
                <label class="block font-medium mb-1">Background Color: </label>
                <input type="color" name="color" class=" mb-3">
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                Save Status
            </button>
        </form>



    </div>
</div>

<script>
    function openStatusModal() {
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>

@endsection
