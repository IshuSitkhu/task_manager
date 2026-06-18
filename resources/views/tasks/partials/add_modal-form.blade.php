<div class="bg-white p-3 rounded shadow">
    <form method="POST" action="{{ route('projects.tasks.store', $project->id) }}">
            @csrf

            <div class="mb-3">
                <label class="block font-medium mb-1">Task Title</label>
                <input type="text"
                       name="title"
                       class="w-full border rounded p-2"
                       placeholder="e.g. Login API Fix"
                       required>
            </div>

            <div class="mb-3">
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="4"
                          placeholder="Describe the task..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                <div>
                    <label class="block font-medium mb-1">Epic</label>
                    <select name="epic_id" class="w-full border rounded p-2" required>
                        <option value="">Select Epic</option>

                        @foreach($epics as $epic)
                            <option value="{{ $epic->id }}">
                                {{ $epic->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Sprint (optional)</label>
                    <select name="sprint_id" class="w-full border rounded p-2">
                        <option value="">No Sprint</option>

                        @foreach($sprints as $sprint)
                            <option value="{{ $sprint->id }}">
                                {{ $sprint->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Assignee</label>
                    <select name="assigned_to" class="w-full border rounded p-2" required>
                        <option value="">Select Member</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Type</label>
                    <select name="type" class="w-full border rounded p-2" required>
                        <option value="feature">Feature</option>
                        <option value="bug">Bug</option>
                        <option value="ui">UI</option>
                        <option value="backend">Backend</option>
                        <option value="test">Test</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded p-2" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Status</label>

                    <select name="status" id="statusSelect" class="w-full border rounded p-2" required>
                        @foreach($project->statuses as $status)
                            <option value="{{ $status->slug }}">
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">GitHub Link</label>
                    <input type="text"
                           name="github_link"
                           class="w-full border rounded p-2"
                           placeholder="https://github.com/...">
                </div>

                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="date"
                           name="due_date"
                           class="w-full border rounded p-2">
                </div>

            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create Task
                </button>
            </div>

        </form>
</div>
