<form id="editBugForm"
      method="POST"
      action="{{ route('projects.bugs.update', [$project, $bug]) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <input type="hidden" name="task_id" value="{{ $bug->task_id }}">

    <div class="mb-2">
        <label class="block font-medium mb-1">Bug Title</label>
        <input
            type="text"
            name="title"
            value="{{ $bug->title }}"
            class="w-full border rounded p-2">
    </div>

    <div class="mb-2">
        <label class="block font-medium mb-1">Description</label>

        <textarea
            name="description"
            rows="4"
            class="w-full border rounded p-2">{{ $bug->description }}</textarea>
    </div>

    <div class="mb-2">
        <label class="block font-medium mb-1">Severity</label>

        <select
            name="severity"
            class="w-full border rounded p-2">

            <option value="low"
                {{ $bug->severity=='low'?'selected':'' }}>
                Low
            </option>

            <option value="medium"
                {{ $bug->severity=='medium'?'selected':'' }}>
                Medium
            </option>

            <option value="critical"
                {{ $bug->severity=='critical'?'selected':'' }}>
                Critical
            </option>

        </select>
    </div>

    <div class="mb-2">
        <label class="block font-medium mb-1">Status</label>

        <select
            name="status"
            class="w-full border rounded p-2">

            <option value="open"
                {{ $bug->status=='open'?'selected':'' }}>
                Open
            </option>

            <option value="in_progress"
                {{ $bug->status=='in_progress'?'selected':'' }}>
                In Progress
            </option>

            <option value="fixed"
                {{ $bug->status=='fixed'?'selected':'' }}>
                Fixed
            </option>

        </select>
    </div>

    <div class="mb-2">
        <label class="block font-medium mb-1">
            Assign Developer
        </label>

        <select
            name="assigned_to"
            class="w-full border rounded p-2">

            @foreach($users as $user)
                <option
                    value="{{ $user->id }}"
                    {{ $bug->assigned_to==$user->id?'selected':'' }}>
                    {{ $user->name }}
                </option>
            @endforeach

        </select>
    </div>

<div class="mb-2">
    <label class="block font-medium mb-1">
        Screenshot
    </label>

    <div class="flex items-start gap-3">

        <input
            type="file"
            id="modalImage"
            name="image"
            class="flex-1 border border-gray-300 p-2 rounded-lg"
            onchange="previewBugImage(this)">

        <img
            id="modalPreview"
            src="{{ $bug->image ? asset('storage/'.$bug->image) : '' }}"
            class="{{ $bug->image ? '' : 'hidden' }} w-42 h-32 rounded-lg border object-cover">

    </div>
</div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Update Bug
    </button>

</form>

<script>
    function previewBugImage(input)
{
    const preview = document.getElementById('modalPreview');

    if (input.files && input.files[0]) {

        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');

    }
}
</script>
