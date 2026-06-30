<script>
    console.log('Subtask script loaded');
document.addEventListener('click', function (e) {

    if (e.target.id !== 'addChecklist') return;

    console.log("Add button clicked");

    const input = document.getElementById('checklistInput');
    const container = document.getElementById('checklistContainer');

    if (input.value.trim() === '') return;

    const taskId = document.getElementById('currentTaskId').value;

    fetch('/tasks/' + taskId + '/checklists', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            title: input.value
        })
    })
    .then(response => response.json())
    .then(checklist => {

        const id = checklist.id;

        const html = `
        <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50"
             data-id="${id}"
             data-image="">

            <div class="flex items-center gap-3 flex-1">

                <input type="checkbox"
                       class="check-toggle"
                       data-id="${id}">

                <span>${checklist.title}</span>

                <input type="hidden" class="sub-title" value="${checklist.title}">
                <input type="hidden" class="sub-description" value="">
                <input type="hidden" class="sub-assigned" value="">
                <input type="hidden" class="sub-due-date" value="">
                <input type="hidden" class="check-status" value="0">

                <input type="file" class="sub-image hidden">

            </div>

            <div class="flex gap-2">
                <button type="button"
                        class="editSubtask text-blue-600">
                    Edit
                </button>

                <button type="button"
                        class="removeChecklist text-red-500">
                    Delete
                </button>
            </div>

        </div>
        `;

        container.insertAdjacentHTML('beforeend', html);

        input.value = '';
    });
});

    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeChecklist')) {

            const row = e.target.closest('[data-id]');
            const id = row.dataset.id;

            fetch('/checklists/' + id + '/destroy', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                }
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    row.remove();
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('change', function (e) {

        if (e.target.classList.contains('check-toggle')) {

             console.log('Checkbox clicked');
        console.log(e.target.dataset.id);

            const hidden =
                    e.target.parentElement.querySelector('.check-status');

                if (hidden) {
                    hidden.value = e.target.checked ? 1 : 0;
                }

            fetch('/checklists/' + e.target.dataset.id + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    is_completed: e.target.checked ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            });
        }

    });

    const modal = document.getElementById('subtaskModal');
    console.log("modal:", modal);


    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalAssigned = document.getElementById('modalAssigned');
    const modalDueDate = document.getElementById('modalDueDate');
    const modalImage = document.getElementById('modalImage');
    const modalPreview = document.getElementById('modalPreview');

    modalImage.addEventListener('change', function () {

        if (this.files.length > 0) {

            modalPreview.src =
                URL.createObjectURL(this.files[0]);

            modalPreview.classList.remove('hidden');
        }
    });

    let currentSubtask = null;
    let currentFileInput = null;

    document.addEventListener('click', function(e){
         console.log("Clicked:", e.target);

        if(e.target.classList.contains('editSubtask')){
            console.log('Edit clicked');
              e.stopPropagation();

            currentSubtask = e.target.closest('[data-id]');
             console.log(currentSubtask);

            currentFileInput =
                currentSubtask.querySelector('.sub-image');
                console.log(currentFileInput);

            modalTitle.value =
                currentSubtask.querySelector('.sub-title').value;

            modalDescription.value =
                currentSubtask.querySelector('.sub-description').value;

            modalAssigned.value =
                currentSubtask.querySelector('.sub-assigned').value;

            modalDueDate.value =
                currentSubtask.querySelector('.sub-due-date').value;

            const imageUrl = currentSubtask.dataset.image;

            if (imageUrl) {

                modalPreview.src = imageUrl;
                modalPreview.classList.remove('hidden');

            } else {

                modalPreview.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            console.log(modal.className);
        }
    });

    document.getElementById('saveSubtask').addEventListener('click', function () {

        currentSubtask.querySelector('.sub-title').value =
            modalTitle.value;

        currentSubtask.querySelector('.sub-description').value =
            modalDescription.value;

        currentSubtask.querySelector('.sub-assigned').value =
            modalAssigned.value;

        currentSubtask.querySelector('.sub-due-date').value =
            modalDueDate.value;

        currentSubtask.querySelector('span').innerText =
            modalTitle.value;

            if (modalImage.files.length > 0) {
                const dataTransfer = new DataTransfer();

                dataTransfer.items.add(modalImage.files[0]);
                console.log(modalImage.files[0]);
                console.log(currentFileInput);

                currentFileInput.files = dataTransfer.files;

                currentSubtask.dataset.image = URL.createObjectURL(modalImage.files[0]);
            }

        const id = currentSubtask.dataset.id;

        if (id) {

            const formData = new FormData();

            formData.append('title', modalTitle.value);
            formData.append('description', modalDescription.value);
            formData.append('assigned_to', modalAssigned.value);
            formData.append('due_date', modalDueDate.value);

            if (modalImage.files.length > 0) {
                formData.append('image', modalImage.files[0]);
            }

            fetch('/checklists/' + id + '/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Saved');
            });
        }

        modal.classList.add('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function(){
        modal.classList.add('hidden');
    });
</script>
