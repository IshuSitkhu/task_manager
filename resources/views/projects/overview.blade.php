@extends('layouts.project')

@section('content')

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">Overview</h2>

        <button onclick="document.getElementById('memberModal').classList.remove('hidden')"
            class="px-3 py-1 bg-black text-white rounded">
            Members
        </button>

    </div>

    <div class="grid grid-cols-2 gap-4 text-sm">

        <div>
            <strong>Owner:</strong>
            {{ $project->creator->name ?? 'Unknown' }}
        </div>

        <div>
            <strong>Status:</strong>
            {{ $project->status }}
        </div>

        <div>
            <strong>Start Date:</strong>
            {{ $project->start_date ?? 'Not set' }}
        </div>

        <div>
            <strong>End Date:</strong>
            {{ $project->end_date ?? 'Not set' }}
        </div>

        <div>
            <strong>Created at:</strong>
            {{ $project->created_at->format('Y-m-d') }}
        </div>

    </div>

    <p class="text-gray-700 mt-6">
        {{ $project->description }}
    </p>

    {{-- MEMBERS MODAL --}}
    @include('projects.partials.members-modal')

@endsection
