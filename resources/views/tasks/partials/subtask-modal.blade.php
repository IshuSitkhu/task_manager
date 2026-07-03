
<div id="subtaskModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[100]">
        <div class="bg-white p-6 rounded-lg w-[1200px] overflow-y-auto mt-8">

            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-bold">Edit Subtask</h2>

                <button type="button"
                        id="closeModal"
                        class="font-bold text-lg">
                    ✖
                </button>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <!-- LEFT -->
                <div class="flex flex-col gap-2">
                    <div class="mb-2 ">
                            <label class="block mb-1 font-medium">
                                Title
                            </label>

                            <input type="text"
                                id="modalTitle"
                                class="w-full border p-2 rounded">
                        </div>

                        <div class="mb-2">
                            <label class="block mb-1 font-medium">
                                Description
                            </label>

                            <textarea id="modalDescription"
                                    class="w-full border p-2 rounded"
                                    rows="2"></textarea>
                        </div>

                        <div class="mb-2">
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

                        <div class="mb-2">
                            <label class="block mb-1 font-medium">
                                Due Date
                            </label>

                            <input type="text"
                                id="modalDueDate"
                                class="w-full border p-2 rounded">
                        </div>

                        <div class="mb-2 ">
                            <label class="block mb-1 font-medium">
                                Image
                            </label>

                            <div class="flex items-start gap-3">
                                <input type="file"
                                    id="modalImage"
                                    name="modalImage"
                                    class="flex-1 border border-gray-300 p-2 rounded-lg">

                                <img id="modalPreview"
                                    class="hidden w-32 h-32 rounded-lg border object-cover">
                            </div>


                        </div>

                    </div>


                <!-- RIGHT -->
                <div class="flex flex-col justify-between">
                    <h3 class="font-semibold text-lg mb-2">
                        Comments
                    </h3>

                    <div id="subtaskCommentList"
                        class="border rounded-lg  max-h-80 overflow-y-auto p-3 ">

                        <!-- Comments -->

                    </div>

                    <div class="flex gap-2 mt-3">

                        <input
                            type="text"
                            id="subtaskCommentInput"
                            class="flex-1 border rounded p-2"
                            placeholder="Write a comment...">

                        <button
                            type="button"
                            id="addComment"
                            class="bg-green-600 text-white px-4 rounded">
                            Send
                        </button>

                    </div>


                </div>

            </div>

            <!-- Save Button -->
            <div class="mt-3 flex">

                <button
                    type="button"
                    id="saveSubtask"
                    class="bg-blue-600 text-white px-5 py-2 rounded">
                    Save
                </button>

            </div>

        </div>




</div>
