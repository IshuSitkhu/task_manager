<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Create Project Button --}}
            @if(auth()->user()->role == 'project_manager')
                <div class="mb-6">
                    <a href="{{ route('projects.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        + New Project
                    </a>
                </div>
            @endif

            <h2 class="font-semibold text-xl p-3 text-gray-800 leading-tight">
                Recent Projects
            </h2>
            {{-- Project Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)

                    <div class="bg-white shadow rounded-lg p-5 border">

                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold">
                                {{ $project->name }}
                            </h3>


                            <div>
                            
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                {{ ucfirst($project->status) }}
                            </span>
                            </div>
                            
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                                        Owner:
                                        <span class="font-medium text-gray-700">
                                            {{ $project->creator->name ?? 'Unknown' }}
                                        </span>
                            </p>

                        <!-- <p class="text-gray-600 mt-3">
                            {{ $project->description }}
                        </p> -->

                        <!-- <div class="mt-4 text-sm text-gray-500">
                            <p>
                                <strong>Start:</strong>
                                {{ $project->start_date ?? 'Not Set' }}
                            </p>

                            <p>
                                <strong>End:</strong>
                                {{ $project->end_date ?? 'Not Set' }}
                            </p>
                        </div> -->

                        <div class="mt-5 flex justify-between items-center">

                            <a href="{{ route('projects.overview', $project->id) }}"
                                class="text-blue-600 hover:underline">
                                    Open Project →
                            </a>

                            @if(auth()->user()->role == 'project_manager')
                                <a href="{{ route('projects.edit', $project) }}"
                                   class="text-yellow-600 hover:underline">
                                    Edit
                                </a>
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="col-span-3 bg-white p-6 rounded shadow text-center">
                        No projects found.
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>