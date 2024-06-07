<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponses;
use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;

    /**
     * Get all users
     *
     * @group Managing Users
     *
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=name
     * @queryParam filter[name] Filter by status name. Wildcards are supported. No-example
     * @queryParam filter[email] Filter by email. Wildcards are supported. No-example
     *
     */
    public function index(UserFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate()
        );
    }

    /**
     * Create a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */
    public function store(StoreUserRequest $request)
    {
        try {
            //policy
            Gate::authorize('store', User::class);

            return new UserResource(User::create($request->mappedAttributes()));

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to create this resource');
        }
    }

    /**
     * Display a user
     *
     * @group Managing Users
     *
     *
     */
    public function show(User $user)
    {
        if ($this->isParamIncluded('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    /**
     * Update a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //PATCH
        try {
            //policy
            Gate::authorize('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to update this resource');
        }
    }

    /**
     * Replace a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */
    public function replace(ReplaceUserRequest $request, User $user)
    {
        //PUT
        try {
            //policy
            Gate::authorize('replace', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        }  catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to replace this resource');
        }
    }

    /**
     * Delete a user
     *
     * @group Managing Users
     *
     * @response 200 {}
     */
    public function destroy(User $user)
    {
        try {
            //policy
            Gate::authorize('delete', $user);

            $user->delete();

            return ApiResponses::ok('User successfully deleted');
        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to delete this resource');
        }

    }
}
