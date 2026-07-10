<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        //Treat $user as an instance of my User model.
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            $view->with([
                'notifications' => collect(),
                'unread' => 0,
            ]);

            return;
        }

        $view->with([
            'notifications' => $user
                ->notifications()
                ->latest()
                ->take(5)
                ->get(),

            'unread' => $user
                ->notifications()
                ->where('is_read', false)
                ->count(),
        ]);
    }
}
