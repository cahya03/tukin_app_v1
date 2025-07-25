<?php

namespace App\Policies;

use App\Models\User;

class ActivityLogPolicy
{
    public function view(User $user)
    {
        return $user->role === 'admin';
    }
}