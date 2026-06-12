<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- SIDEBAR --}}
                <div class="bg-white p-4 rounded shadow h-fit">

                    <h3 class="font-bold mb-3">Project Menu</h3>

                    <ul class="space-y-2 text-sm">

                        <li class="text-blue-600 font-semibold">Overview</li>
                        <li>Epics</li>
                        <li>Sprints</li>
                        <li>Tasks</li>
                        <li>Board</li>

                    </ul>

                </div>

                {{-- MAIN CONTENT --}}
                <div class="md:col-span-3 bg-white p-6 rounded shadow">

                    <h2 class="text-2xl font-bold mb-4">
                        Overview
                    </h2>

                    <p class="text-gray-700 mb-4">
                        {{ $project->description }}
                    </p>

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

                </div>

            </div>

        </div>
    </div>
</x-app-layout>