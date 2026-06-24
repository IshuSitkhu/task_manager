<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // GET COMMENTS
    public function index(Task $task)
    {
        // permission check
        $this->authorizeProject($task);

        $comments = $task->comments()->with('user')->latest()->get();

        return response()->json($comments);
    }

    // STORE COMMENT
    public function store(Request $request, Task $task)
    {
        // permission check
        $this->authorizeProject($task);

        $request->validate([
            'message' => 'required|string'
        ]);

        $comment = Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }

    //  permission logic
    private function authorizeProject(Task $task)
    {
        $userId = Auth::id();

        if (!$task->project) {
            abort(403, 'Project not found');
        }

        $isMember = $task->project->members()
            ->where('users.id', $userId)
            ->exists();

        abort_unless($isMember, 403);
    }

// UPDATE COMMENT
public function update(Request $request, Comment $comment)
{
    $this->authorizeComment($comment);

    $request->validate([
        'message' => 'required|string'
    ]);

    $comment->update([
        'message' => $request->message
    ]);

    return response()->json([
        'success' => true,
        'comment' => $comment->load('user')
    ]);
}

// DELETE COMMENT
public function destroy(Comment $comment)
{
    $this->authorizeComment($comment);

    $comment->delete();

    return response()->json([
        'success' => true
    ]);
}

private function authorizeComment(Comment $comment)
{
    $userId = Auth::id();

    // must be logged-in owner
    if ($comment->user_id !== $userId) {
        abort(403, 'You are not allowed to modify this comment');
    }

    // optional extra safety: ensure same project access
    if (!$comment->task || !$comment->task->project) {
        abort(403, 'Invalid comment context');
    }

    $isMember = $comment->task->project->members()
        ->where('users.id', $userId)
        ->exists();

    abort_unless($isMember, 403);
}
}
