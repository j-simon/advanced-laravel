<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // dump( auth()->user());
        // dd($user);
        
        // // View any users logic
        return auth()->user() === $user ; 
        //return $auth === $user ; 
        
    }

    public function view(User $user, User $model)
    {
        // View a specific user logic
        return auth()->user() === $user ; 
        // return true; // Adjust as needed
    }

    public function create(User $user)
    {
      
        // Create user logic
        return auth()->user() === $user ; 
        // return true; // Adjust as needed
    }

    public function update(User $user, User $model)
    {
        // Update user logic
        return auth()->user() === $user ; 
        // return true; // Adjust as needed
    }

    public function delete(User $user, User $model)
    {
        // Delete user logic
        return auth()->user() === $user ; 
        // return true; // Adjust as needed
    }
}
