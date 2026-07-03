@forelse($bugs as $bug)
    <div class="border border-black p-3 mb-2 rounded bg-gray-50">
    <div class="flex justify-between items-start gap-2">
        <div class="font-semibold text-gray-800 leading-snug">
            Bug: {{ $bug->title }}
        </div>

        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600 whitespace-nowrap">
            {{ $bug->severity }}
        </span>
    </div>

    <div class="text-sm text-gray-700 mb-2">
         {{ $bug->description }}
    </div>

    @if($bug->image)
        <img src="{{ asset('storage/' . $bug->image) }}"
             class="rounded border max-h-48 mb-2 object-cover">
    @endif


    @if(auth()->user()->role == 'project_manager')
        <form method="POST" >
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="text-sm text-white border rounded p-3 bg-red-600"
                    onclick="return confirm('Delete this bug?')">
                Delete Bug Report
            </button>
        </form>
    @endif
</div>
@empty
    <p class="text-gray-500">No bugs found for this task.</p>
@endforelse
