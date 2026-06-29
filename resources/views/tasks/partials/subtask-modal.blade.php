
            <div id="subtaskModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[100]">

                <div class="bg-white p-6 rounded-lg w-[600px]">


                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">
                            Edit Subtask
                        </h2>
                        <button type="button"
                                    id="closeModal"
                                    class=" text-black font-bold text-lg">
                                ✖
                            </button>
                    </div>

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">
                            Title
                        </label>

                        <input type="text"
                            id="modalTitle"
                            class="w-full border p-2 rounded">
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">
                            Description
                        </label>

                        <textarea id="modalDescription"
                                class="w-full border p-2 rounded"
                                rows="4"></textarea>
                    </div>

                    <!-- ASSIGNEE -->
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">
                            Assignee
                        </label>

                        <select id="modalAssigned"
                                class="w-full border p-2 rounded">

                            <option value="">
                                Select Member
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">
                            Due Date
                        </label>

                        <input type="text"
                            id="modalDueDate"
                            class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4 ">
                        <label class="block mb-1 font-medium">
                            Image
                        </label>

                        <div class="flex items-start gap-2">
                            <input type="file"
                                id="modalImage"
                                name="modalImage"
                                class="flex-1 border border-gray-300 p-2 rounded-lg">

                            <img id="modalPreview"
                                class="hidden w-42 h-32 rounded-lg border object-cover">
                        </div>


                    </div>

                    <div class="flex gap-2">

                        <button type="button"
                                id="saveSubtask"
                                class="bg-blue-600 text-white px-4 py-2 rounded">
                            Save
                        </button>
                    </div>

                </div>

            </div>
