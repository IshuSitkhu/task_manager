<div id="BugEditModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded w-[650px] p-5">

        <div class="flex justify-between mb-4">

            <h2 class="text-xl font-bold">
                Edit Bug
            </h2>

            <button onclick="closeEditBugModal()">
                ✕
            </button>

        </div>

        <div id="bugEditBody">

        </div>

    </div>

</div>

<script>
    function openEditBugModal(id)
    {
        fetch(`/projects/{{ $project->id }}/bugs/${id}/edit`)
            .then(res => res.text())
            .then(html => {

                document.getElementById('bugEditBody').innerHTML = html;

                document
                    .getElementById('BugEditModal')
                    .classList.remove('hidden');

            });
    }

    function closeEditBugModal()
    {
        document
            .getElementById('BugEditModal')
            .classList.add('hidden');
    }
</script>
