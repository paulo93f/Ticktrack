<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(UserFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            //policy
            Gate::authorize('store', User::class);

            return new UserResource(User::create($request->mappedAttributes()));

        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorize to create this resource', 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->isParamIncluded('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        //PATCH
        try {
            $user = User::findOrFail($userId);

            //policy
            Gate::authorize('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorize to update this resource', 403);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceUserRequest $request, $userId)
    {
        //PUT
        try {
            $user = User::findOrFail($userId);

            //policy
            Gate::authorize('replace', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);

            //policy
            Gate::authorize('delete', $user);

            $user->delete();

            return $this->ok('User successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found', 404);
        }

    }
}
