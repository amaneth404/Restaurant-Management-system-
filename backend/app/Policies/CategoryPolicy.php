<?php

namespace App\Policies;
use Illuminate\Auth\Access\Response;
use App\Category;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any categories.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->role==1 ? Response::allow()
        : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param  App\User  $user
     * @param  App\Category  $category
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        return $user->role==1 ? Response::allow()
        : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role==1 ? Response::allow()
        : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param  App\User  $user
     * @param  App\Category  $category
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        return $user->role==1 ? Response::allow()
        : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  App\User  $user
     * @param  App\Category  $category
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        return $user->role==1 ? Response::allow()
        : Response::deny('You do not own this post.');
    }
}
