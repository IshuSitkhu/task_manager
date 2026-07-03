<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistComment extends Model
{
    protected $fillable = [
        'checklist_id',
        'user_id',
        'comment'
    ];

    public function checklist()
    {
        return $this->belongsTo(TaskChecklist::class, 'checklist_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
