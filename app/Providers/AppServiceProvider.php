<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Relation::enforceMorphMap([
        'User' => User::class,
        
        'Task' => \App\Models\Task::class,
        'Bug' => \App\Models\Bug::class,
        'Sprint' => \App\Models\Sprint::class,
        'Epic' => \App\Models\Epic::class,
        'Comment' => \App\Models\Comment::class,
        'Project' => \App\Models\Project::class,
    ]);
}
}
