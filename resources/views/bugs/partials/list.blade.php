@forelse($bugs as $bug)
    <div class="border border-black p-3 mb-2 rounded bg-gray-50">
    <h1 class="font-bold mb-1"> Title: {{ $bug->title }}</h1>

    <div class="text-sm text-gray-700 mb-2">
         {{ $bug->description }}
    </div>

    <div class="text-xs text-gray-500 mb-2">
        Severity: {{ $bug->severity }}
    </div>



    @if($bug->image)
        <img src="{{ asset('storage/' . $bug->image) }}"
             class="rounded border max-h-48 mb-2 object-cover">
    @endif


    <button class="text-sm text-red-500">Delete bug Report</button>
</div>
@empty
    <p class="text-gray-500">No bugs found for this task.</p>
@endforelse
