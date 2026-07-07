<div id="memberModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg p-6 rounded shadow">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-bold">Project Members</h2>

            <button onclick="document.getElementById('memberModal').classList.add('hidden')"
                class="text-gray-600 hover:text-black">
                ✖
            </button>

        </div>

        <div class="mb-5">
            <h3 class="font-semibold mb-3">Current Members</h3>

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

                        $color = $colors[$member->id % count($colors)];

                        $initials = collect(explode(' ', $member->name))
                        // substr takes the first character of each name part, strtoupper makes it uppercase
                            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                            ->take(2)
                            ->join('');
                    @endphp

                    <div class="group flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 hover:bg-gray-100 hover:shadow-md">

                        <div class="w-10 h-10 flex items-center justify-center rounded-md text-white {{ $color }} font-bold text-sm shadow transition-transform duration-200 group-hover:scale-110">
                            {{ $initials }}
                        </div>

                        <span class="text-sm font-medium transition-all duration-200 group-hover:font-semibold group-hover:text-gray-900">
                            {{ $member->name }}
                        </span>

                        @if(auth()->user()->role == 'project_manager')
                            <form method="POST"
                                action="{{ route('projects.removeMember', [$project->id, $member->id]) }}">
                                @csrf
                                @method('DELETE')

                                <button class=" text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-6 h-6 pt-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        @endif

                    </div>

                @endforeach

            </div>
        </div>

        {{-- INVITE FORM --}}
        @if(auth()->user()->role == 'project_manager')
            <form method="POST" action="{{ route('projects.addMembers', $project->id) }}">
                @csrf

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Invite Members
                    </label>

                    <select
                        name="members[]"
                        multiple
                        class="js-select2 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        @foreach($allUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-gray-500 mt-2">
                        Search and select one or more users to add to this project.
                    </p>
                </div>

                <button
                    type="submit"
                    class="mt-4 w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 hover:shadow-md">
                    Invite Members
                </button>

            </form>
        @endif

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
