<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Project
            </h2>

            <a href="{{ route('projects.index')}}"
            class="text-blue-600 hover:underline">
                ← Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-lg shadow">

                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Project Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $project->name) }}"
                            class="w-full border rounded-lg p-2"
                            required
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded-lg p-2"
                        >{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full border rounded-lg p-2"
                        >
                            <option value="active"
                                {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="completed"
                                {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="archived"
                                {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>
                                Archived
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Start Date
                        </label>

                        <input type="text"
                            id="project_start_date"
                            name="start_date"
                            value="{{ old('start_date', $project->start_date) }}"
                            class="w-full border rounded-lg p-2"
                            required
                        >
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">
                            End Date
                        </label>

                        <input type="text"
                            id="project_end_date"
                            name="end_date"
                            class="w-full border rounded-lg p-2"
                            value="{{ old('end_date', $project->end_date) }}"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Add Members</label>

                    <select name="members[]" multiple class="js-select2 w-full border p-2 rounded">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ in_array($user->id, old('members', $project->members->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    </div>

                    <div class="flex justify-end gap-3">

                        <button
                            type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Edit Project
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.js-select2').select2({
            placeholder: "Select members",
            width: '100%'
        });
    });
</script>
</x-app-layout>
