{{-- ===================== MODAL ===================== --}}
<div id="memberModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg p-6 rounded shadow">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-bold">Project Members</h2>

            <button onclick="document.getElementById('memberModal').classList.add('hidden')"
                class="text-gray-600 hover:text-black">
                ✖
            </button>

        </div>

        {{-- CURRENT MEMBERS --}}
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
                            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                            ->take(2)
                            ->join('');
                    @endphp

                    <div class="flex items-center gap-2 px-3 py-2 rounded-md shadow-sm bg-gray-50">

                        {{-- avatar --}}
                        <div class="w-10 h-10 flex items-center justify-center rounded-md text-white {{ $color }} font-bold text-sm shadow">
                            {{ $initials }}
                        </div>

                        {{-- name --}}
                        <span class="text-sm font-medium">
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
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">
                Invite Members
            </button>

        </form>

    </div>
</div>

{{-- SELECT2 SCRIPT (keep here OR move to layout later) --}}
<script>
    $(document).ready(function () {
        $('.js-select2').select2({
            placeholder: "Select users",
            width: '100%'
        });
    });
</script>