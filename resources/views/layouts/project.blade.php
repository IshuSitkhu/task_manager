<x-app-layout>

    <div x-data="{ sidebarOpen: true }" class="py-6 bg-black/90 min-h-screen">

        <div class="mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center gap-12 mb-4">

                @if(isset($project))
                    <h3 class="font-bold text-xl text-white">
                        {{ $project->name }}
                    </h3>
                @endif

                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="px-3 py-1 bg-black text-white rounded"
                >
                    ☰
                </button>

            </div>

            <!-- GRID -->
            <div
                class="grid gap-6"
                :class="sidebarOpen
                    ? 'grid-cols-1 md:grid-cols-5'
                    : 'grid-cols-1 md:grid-cols-1'"
            >

                <!-- SIDEBAR -->
                <div
                    x-show="sidebarOpen"
                    x-transition
                    class="bg-white p-3 rounded shadow h-fit"
                >

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
                            <a href="{{ route('projects.tasks.board', $project->id) }}"
                               class="{{ request()->routeIs('projects.tasks.board') ? 'text-blue-600 font-semibold' : '' }}">
                                Kanban Board
                            </a>
                        </li>

                    </ul>

                </div>

                <div
                    class="bg-white p-6 rounded shadow"
                    :class="sidebarOpen ? 'md:col-span-4' : 'md:col-span-5'"
                >

                    @yield('content')

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
