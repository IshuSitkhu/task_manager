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

                    {{-- HEADER --}}
                    <div class="flex justify-between items-center mb-4">

                        <h2 class="text-2xl font-bold">
                            Overview
                        </h2>

                        {{-- OPEN MODAL BUTTON --}}
                        <button onclick="document.getElementById('memberModal').classList.remove('hidden')"
                            class="px-3 py-1 bg-black text-white rounded">
                            Members
                        </button>

                    </div>

                    {{-- PROJECT INFO --}}
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

                    <p class="text-gray-700 mb-4 pt-6">
                        {{ $project->description }}
                    </p>

                </div>

            </div>
        </div>
    </div>

    {{-- ===================== MODAL ===================== --}}
    <div id="memberModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

        <div class="bg-white w-full max-w-lg p-6 rounded shadow">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-lg font-bold">Project Members</h2>

                <button onclick="document.getElementById('memberModal').classList.add('hidden')">
                    ✖
                </button>

            </div>

            {{-- CURRENT MEMBERS --}}
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Current Members</h3>

                <div class="flex flex-wrap gap-2">

                    @foreach($project->members as $member)

                        @php
                            $colors = [
                                'bg-red-500',
                                'bg-blue-500',
                                'bg-green-500',
                                'bg-purple-500',
                                'bg-pink-500',
                                'bg-yellow-500',
                                'bg-indigo-500',
                                'bg-teal-500',
                            ];

                            // stable random color per user (same user = same color always)
                            $color = $colors[$member->id % count($colors)];

                            $initials = collect(explode(' ', $member->name))
                                ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                ->take(2)
                                ->join('');
                        @endphp

                        <div class="flex items-center gap-2 px-3 py-2 rounded-md shadow-sm">

                            {{-- initials box --}}
                            <div class="w-10 h-10 flex items-center justify-center rounded-md text-white {{ $color }} font-bold text-sm shadow">
                                {{ $initials }}
                            </div>

                            {{-- name --}}
                            <span class="text-l font-medium">
                                {{ $member->name }}
                            </span>
                        </div>

                    @endforeach

                </div>
            </div>

            {{-- INVITE FORM --}}
            <form method="POST" action="{{ route('projects.addMembers', $project->id) }}">
                @csrf

                <label class="block mb-2 font-medium">Invite Members</label>

                <select name="members[]" multiple class="js-select2 w-full border p-2 rounded">
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="mt-3 bg-blue-600 text-white px-4 py-2 rounded">
                    Invite
                </button>
            </form>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.js-select2').select2({
                placeholder: "Select users",
                width: '100%'
            });
        });
    </script>

</x-app-layout>