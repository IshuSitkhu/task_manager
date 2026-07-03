<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Epic;
use App\Models\Sprint;
use App\Models\User;
use App\Models\TaskChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ChecklistComment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $query = Task::where('project_id', $project->id)
            ->with([
                'epic',
                'sprint',
                'assignee',
                'checklists',
                'projectStatus',
                'type'
            ]);

        // Employees see only their assigned tasks
        if (Auth::user()->role == 'employee') {
            $query->where('assigned_to', Auth::id());
        }

        $tasks = $query->latest()->get();

        // Backlog = due date before today AND not done
        $backlogTasks = $tasks->filter(function ($task) {
            return $task->due_date &&
                Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                $task->status != 'done';
        });

        // Remaining tasks
        $tasks = $tasks->reject(function ($task) {
            return $task->due_date &&
                Carbon::parse($task->due_date)->isBefore(Carbon::today()) &&
                $task->status != 'done';
        });

        return view('tasks.index', compact(
            'project',
            'tasks',
            'backlogTasks'
        ));
    }
    public function create(Project $project)
    {
        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members; // only project members
        $types = $project->taskTypes;

        return view('tasks.create', compact('project', 'epics', 'sprints', 'users','types' ));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'epic_id' => 'required|exists:epics,id',
            'sprint_id' => 'nullable|exists:sprints,id',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required',
            'priority' => 'required',
            'type_id' => 'required|exists:task_types,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'due_date' => 'required|date',

            'checklists' => 'nullable|array',
            'checklists.*.title' => 'required|string|max:255',
            'checklists.*.is_completed' => 'nullable|boolean',
            'checklists.*.description' => 'nullable|string',
            'checklists.*.assigned_to' => 'nullable|exists:users,id',
            'checklists.*.due_date' => 'nullable|date',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
        }


        $task = Task::create([
            'project_id' => $project->id,
            'epic_id' => $request->epic_id,
            'sprint_id' => $request->sprint_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'type_id' => $request->type_id,
            'github_link' => $request->github_link,
            'due_date' => $request->due_date,
             'image' => $imagePath,
        ]);

        if ($request->filled('checklists')) {
            $order = 0;

            foreach ($request->checklists as $item) {

                $task->checklists()->create([
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                    'assigned_to' => $item['assigned_to'] ?? null,
                    'due_date' => $item['due_date'] ?? null,
                    'is_completed' => $item['is_completed'] ?? 0,
                    'sort_order' => $order,
                ]);

                $order++;
            }
        }

        // return redirect()->route('projects.tasks', $project->id)
        //     ->with('success', 'Task created successfully');

        return redirect()->back()
            ->with('success', 'Task created successfully');
    }

    public function edit(Project $project, Task $task)
    {

        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members;

        return view('tasks.edit', compact('project', 'task', 'epics', 'sprints', 'users'));
    }

    public function editmodule(Project $project, Task $task)
    {
        //IT RETURN HTML FORM
        return view('tasks.partials.edit-modal-form', [
            'project' => $project,
            'task' => $task,
            'epics' => $project->epics,
            'sprints' => $project->sprints,
            'users' => $project->members,
        ]);
    }
    public function update(Request $request, Project $project, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'epic_id' => 'required|exists:epics,id',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required',
            'priority' => 'required',
            'type_id' => 'required|exists:task_types,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'checklists' => 'nullable|array',
            'checklists.*.title' => 'required|string|max:255',
            'checklists.*.description' => 'nullable|string',
            'checklists.*.assigned_to' => 'nullable|exists:users,id',
            'checklists.*.due_date' => 'nullable|date',
            'checklists.*.is_completed' => 'nullable|boolean',
        ]);

        //  STEP 1: KEEP OLD IMAGE BY DEFAULT
        $imagePath = $task->image;

        //  STEP 2: IF NEW IMAGE UPLOADED
        if ($request->hasFile('image')) {

            // delete old image if exists
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }

            // store new image
            $imagePath = $request->file('image')->store('tasks', 'public');
        }

        //  STEP 3: UPDATE TASK
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'epic_id' => $request->epic_id,
            'sprint_id' => $request->sprint_id,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'type_id' => $request->type_id,
            'github_link' => $request->github_link,
            'due_date' => $request->due_date,

            //  THIS IS WHERE IMAGE GOES
            'image' => $imagePath,
        ]);


        return redirect()->back()
            ->with('success', 'Task updated successfully');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully');
    }
    
    public function board(Project $project)
    {
        $project->load('statuses');

        $query = $project->tasks()
            ->with('epic', 'assignee', 'projectStatus', 'bugs', 'type');

        // Employees see only their assigned tasks
        if (Auth::user()->role == 'employee') {
            $query->where('assigned_to', Auth::id());
        }

        $tasks = $query->latest()->get();

        $types = $project->taskTypes;
        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members;

        return view('tasks.board', compact(
            'project',
            'tasks',
            'epics',
            'sprints',
            'users',
            'types'
        ));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,bug,done',
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function moveStatus(Request $request, Task $task)
    {
        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function toggleChecklist(Request $request, TaskChecklist $checklist)
    {
        $checklist->update([
            'is_completed' => $request->is_completed
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function updateChecklist(Request $request, TaskChecklist $checklist)
    {
        $imagePath = $checklist->image;

        if ($request->hasFile('image')) {

            if ($checklist->image) {
                Storage::disk('public')->delete($checklist->image);
            }

            $imagePath = $request->file('image')
                ->store('subtasks', 'public');
        }

        $checklist->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function storeChecklist(Request $request, Task $task)
    {
        $checklist = $task->checklists()->create([
            'title' => $request->title,
            'description' => null,
            'assigned_to' => null,
            'due_date' => null,
            'is_completed' => 0,
            'sort_order' => 0,
        ]);

        return response()->json($checklist);
    }

    public function destroyChecklist(TaskChecklist $checklist)
    {
        $checklist->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function subtaskModal(Task $task)
    {
        return view(
            'tasks.partials.subtasks',
            compact('task')
        );
    }

    //SUBTASK COMMENTS
    public function comments(TaskChecklist $checklist)
    {
        return response()->json(
            $checklist->comments()
                    ->with('user')
                    ->latest()
                    ->get()
        );
    }

    public function storeComment(Request $request, TaskChecklist $checklist)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = $checklist->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        $comment->load('user');

        return response()->json($comment);
    }

public function destroyComment(ChecklistComment $comment)
{
    // Optional: only allow the comment owner to delete
    if ($comment->user_id != Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $comment->delete();

    return response()->json([
        'success' => true
    ]);
}


}
