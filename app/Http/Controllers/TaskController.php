<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ChecklistComment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\ActivityService;

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

    public function store(TaskRequest $request, Project $project, ActivityService $activityService)
    {
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

        //CREATE LISTLIST ITEM
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

        $activityService->log(
            Auth::user(),
            'created_task',
            'Created task "' . $task->title . '"',
            $task,
            "Task"
        );

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

    public function update(TaskRequest $request, Project $project, Task $task, ActivityService $activityService)
    {

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

        $activityService->log(
            Auth::user(),
            'updated_task',
            'Updated task "' . $task->title . '"',
            $task,
            "Task"
        );


        return redirect()->back()
            ->with('success', 'Task updated successfully');
    }

    public function destroy(Project $project, Task $task, ActivityService $activityService)
    {

        $activityService->log(
            Auth::user(),
            'deleted_task',
            'Deleted task "' . $task->title . '"',
            $task,
            "Task"
        );

        $task->delete();

        return back()->with('success', 'Task deleted successfully');
    }

    public function board(Request $request, Project $project)
    {
        // LOAD ALL THE STATUS RELATED TO THE PROJECT, INCLUDING TASKS AND THEIR RELATIONSHIPS
        $project->load('statuses');

        $query = $project->tasks()
            ->with('epic', 'assignee', 'projectStatus', 'bugs', 'type');

        // Employees see only their assigned tasks
        if (Auth::user()->role == 'employee') {
            $query->where('assigned_to', Auth::id());
        }

        // epic filter
        if ($request->filled('epic')) {
            $query->where('epic_id', $request->epic);
        }

        // sprint filter
        if ($request->filled('sprint')) {
            $query->where('sprint_id', $request->sprint);
        }

        //due date
        // if ($request->due_date == 'today') {
        //     $query->whereDate('due_date', today());
        // }

        // if ($request->due_date == 'tomorrow') {
        //     $query->whereDate('due_date', today()->addDay());
        // }

        // if ($request->due_date == 'week') {
        //     $query->whereBetween('due_date', [
        //         today(),
        //         today()->endOfWeek()
        //     ]);
        // }

        // if ($request->due_date == 'overdue') {
        //     $query->whereDate('due_date', '<', today());
        // }

        $query->when($request->filled('due_date'), function ($query) use ($request) {
            match ($request->due_date) {
                'today' => $query->whereDate('due_date', today()),
                'tomorrow' => $query->whereDate('due_date', today()->addDay()),
                'week' => $query->whereBetween('due_date', [
                    today(),
                    today()->endOfWeek()
                ]),
                'overdue' => $query->whereDate('due_date', '<', today()),
                default => null,
            };
        });

        $tasks = $query->latest()->get();

        $types = $project->taskTypes;
        $epics = $project->epics;
        $sprints = $project->sprints;
        $users = $project->members;

        // THIS IS FOR NO REFRESH WHEN FILTER. THIS RENDER IN KANBAN BOARD
        if ($request->ajax()) {
            return view('tasks.partials.board', compact(
                'project',
                'tasks'
            ));
        }

        return view('tasks.board', compact(
            'project',
            'tasks',
            'epics',
            'sprints',
            'users',
            'types'
        ));
    }

    public function updateStatus(Request $request, Task $task, ActivityService $activityService)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,bug,done',
        ]);

        $oldStatus = $task->status;

        $task->update([
            'status' => $request->status
        ]);

        $activityService->log(
            Auth::user(),
            'updated_task_status',
            'Changed task "' . $task->title . '" status from "' . $oldStatus . '" to "' . $task->status . '"',
            $task,
            "Task"
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function moveStatus(Request $request, Task $task, ActivityService $activityService)
    {
        $oldStatus = $task->status;

        $task->update([
            'status' => $request->status
        ]);

        $activityService->log(
            Auth::user(),
            'moved_task',
            'Moved status of task "' . $task->title . '" from "' . $oldStatus . '" to "' . $task->status . '"',
            $task,
            "Task"
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function toggleChecklist(Request $request, TaskChecklist $checklist, ActivityService $activityService)
    {
        $checklist->update([
            'is_completed' => $request->is_completed
        ]);

        $description = $request->is_completed
            ? "Completed checklist '{$checklist->title}'"
            : "Unchecked checklist '{$checklist->title}'";

        $activityService->log(
            Auth::user(),
            'Toggle_checklist',
            $description,
            $checklist,
            "Subtask"
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function updateChecklist(Request $request, TaskChecklist $checklist, ActivityService $activityService)
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

        $activityService->log(
            Auth::user(),
            'Updated_subtask',
            "Updated checklist '{$checklist->title}'",
            $checklist,
            "Subtask"
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function storeChecklist(Request $request, Task $task, ActivityService $activityService)
    {
        $checklist = $task->checklists()->create([
            'title' => $request->title,
            'description' => null,
            'assigned_to' => null,
            'due_date' => null,
            'is_completed' => 0,
            'sort_order' => 0,
        ]);


        $activityService->log(
            Auth::user(),
            'Created_Subtask',
            "Created checklist '{$checklist->title}'",
            $checklist,
            "Subtask"
        );

        return response()->json($checklist);
    }

    public function destroyChecklist(TaskChecklist $checklist, ActivityService $activityService)
    {

        $title = $checklist->title;

        $activityService->log(
            Auth::user(),
            'Deleted_Subtask',
            "Deleted checklist '{$title}'",
            $checklist,
            "Subtask"
        );

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

    public function storeComment(Request $request, TaskChecklist $checklist, ActivityService $activityService)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = $checklist->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        $comment->load('user');

        $activityService->log(
            Auth::user(),
            'Added_Comment',
            "Added a comment '{$comment->comment}' to subtask '{$checklist->title}'",
            $checklist,
            "Subtask"
        );

        return response()->json($comment);
    }

    public function destroyComment(ChecklistComment $comment, ActivityService $activityService)
    {
        // Optional: only allow the comment owner to delete
        if ($comment->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $checklist = $comment->checklist; // assuming ChecklistComment belongsTo TaskChecklist

        $activityService->log(
            Auth::user(),
            'Deleted_Comment',
            "Deleted a comment from subtask '{$checklist->title}'",
            $checklist,
            "Subtask"
        );

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }


}
