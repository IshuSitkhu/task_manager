<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- SIDEBAR --}}
                <div class="bg-white p-4 rounded shadow h-fit">

                    {{-- PROJECT TITLE --}}
                    @if(isset($project))
                        <h3 class="font-bold mb-4">
                            {{ $project->name }}
                        </h3>
                    @endif

                    {{-- MENU --}}
                    <ul class="space-y-2 text-sm">

                        <li>
                            <a href="{{ route('projects.overview', $project->id) }}"
                               class="{{ request()->routeIs('projects.overview') ? 'text-blue-600 font-semibold' : '' }}">
                                Overview
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('projects.epics', $project->id) }}"
                               class="{{ request()->routeIs('projects.epics') ? 'text-blue-600 font-semibold' : '' }}">
                                Epics
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('projects.sprints', $project->id) }}"
                               class="{{ request()->routeIs('projects.sprints') ? 'text-blue-600 font-semibold' : '' }}">
                                Sprints
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('projects.tasks', $project->id) }}"
                               class="{{ request()->routeIs('projects.tasks') ? 'text-blue-600 font-semibold' : '' }}">
                                Tasks
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Board
                            </a>
                        </li>

                    </ul>

                </div>

                {{-- MAIN CONTENT --}}
                <div class="md:col-span-3 bg-white p-6 rounded shadow">

                    {{-- PAGE CONTENT WILL LOAD HERE --}}
                    @yield('content')

                </div>

            </div>

        </div>
    </div>

</x-app-layout>