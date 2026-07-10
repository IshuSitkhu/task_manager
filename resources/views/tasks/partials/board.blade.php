<div class="grid grid-cols-3 gap-6">
    @foreach($project->statuses as $status)
        <div class="p-3 rounded border border-black/40">
            <div class="flex justify-between items-center mb-3 p-3 h-10"
                 style="background: {{ $status->color ?? '#000' }}">

                <h3 class="font-bold text-white">
                    {{ $status->name }}
                </h3>

                 <div class="lex justify-end relative">
                    <button onclick="toggleMenu({{ $status->id }})"
                        class="text-white text-sm">
                        ⋮
                    </button>

                    <div id="menu-{{ $status->id }}" class="hidden absolute right-0 top-8 mt-2 bg-white border rounded shadow-lg z-50 min-w-[150px]">
                        @if(auth()->user()->role == 'project_manager')
                            <form method="POST"
                            action="{{ route('projects.statuses.destroy', [$project->id, $status->id]) }}">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                    onclick="confirmDelete(this.form)"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                    Delete Status
                                </button>
                            </form>
                        @endif

                        <button onclick="openTaskModal('{{ $status->slug }}'); toggleMenu({{ $status->id }})"
                            class="text-sm bg-white text-black px-4 py-2 rounded">
                            Add Task
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-2 min-h-[200px]"
                ondragover="allowDrop(event)"
                ondrop="dropTask(event, '{{ $status->slug }}')">
                @foreach($tasks->where('status', $status->slug) as $task)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-xl hover:scale-[1.02] hover:border-blue-400 transition-all duration-200 p-4 cursor-pointer"
                    draggable="true"
                    data-task-id="{{ $task->id }}"
                    >

                    <div class="flex justify-between items-start gap-2"  onclick="openEditTaskModal({{ $task->id }})">
                        <div class="font-semibold text-gray-800 leading-snug">
                            {{ $task->title }}
                        </div>

                        <span class="text-white text-sm px-1 rounded"
                            style="background: {{ optional($task->type)->color ?? '#ef4444' }}">
                            {{ optional($task->type)->name ?? 'No Type' }}

                        </span>
                    </div>

                    <div class="mt-2 space-y-1 text-xs text-gray-500 "  onclick="openEditTaskModal({{ $task->id }})">
                        <div>
                            <span class="font-medium text-gray-600">Epic:</span>
                            {{ $task->epic->title ?? 'No Epic' }}
                        </div>

                        <div>
                            <span class="font-medium text-gray-600">Assignee:</span>
                            {{ $task->assignee->name ?? 'Unassigned' }}
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <button
                            type="button"
                            {{-- onclick="event.stopPropagation(); openBugListModal({{ $task->id }})" --}}
                            onclick="event.stopPropagation(); toggleBugs({{ $task->id }})"
                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition">

                             {{ $task->bugs->count() }} Bugs
                        </button>

                        <button
                            type="button"
                            onclick="event.stopPropagation(); toggleSubtasks({{ $task->id }})"
                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition">

                             {{-- {{ $task->checklists->count() }} Subtasks --}}
                             {{ $task->checklists->where('is_completed', 1)->count() }}/{{ $task->checklists->count() }}
                                Subtasks
                        </button>
                    </div>

                    <div id="subtasks-{{ $task->id }}"
                        class="hidden mt-3 border-t pt-2 max-h-40 overflow-y-auto space-y-2">

                        @foreach($task->checklists as $subtask)

                            <div class="flex items-center justify-between bg-gray-50 rounded p-2 text-sm border"
                                data-id="{{ $subtask->id }}"
                                data-image="{{ $subtask->image ? asset('storage/'.$subtask->image) : '' }}">

                                <div class="flex items-center gap-2">

                                    <input type="checkbox"
                                        class="check-toggle"
                                        data-id="{{ $subtask->id }}"
                                        {{ $subtask->is_completed ? 'checked' : '' }}>

                                    <span>{{ $subtask->title }}</span>

                                    <input type="hidden"
                                        class="sub-title"
                                        value="{{ $subtask->title }}">

                                    <input type="hidden"
                                        class="sub-description"
                                        value="{{ $subtask->description }}">

                                    <input type="hidden"
                                        class="sub-assigned"
                                        value="{{ $subtask->assigned_to }}">

                                    <input type="hidden"
                                        class="sub-due-date"
                                        value="{{ $subtask->due_date }}">

                                    <input type="file"
                                        class="sub-image hidden">
                                </div>

                                <div class="flex gap-2">
                                    <button type="button"
                                            class="editSubtask text-blue-600 text-xs"
                                        >
                                        Edit
                                    </button>

                                    @if(auth()->user()->role == 'project_manager')
                                        <button type="button"
                                                class="removeChecklist text-red-500 text-xs">
                                            Delete
                                        </button>
                                    @endif
                                </div>

                            </div>

                        @endforeach

                    </div>

                    <div id="bugs-{{ $task->id }}"
                        class="hidden mt-3 border-t pt-2 max-h-40 overflow-y-auto space-y-2">

                        @foreach($task->bugs as $bug)

                            <div class="border rounded p-2 bg-gray-50 text-sm"
                                data-id="{{ $bug->id }}">

                                <div class="flex justify-between items-center">

                                    <span class="font-medium text-gray-700">
                                        {{ $bug->title }}
                                    </span>
                                        <button
                                            onclick="openEditBugModal({{ $bug->id }})"
                                            class="text-blue-600 text-xs">
                                            Edit
                                        </button>

                                </div>

                                @if($bug->description)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $bug->description }}
                                    </div>
                                @endif

                            </div>

                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    @endforeach
</div>
