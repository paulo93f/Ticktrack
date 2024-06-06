<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponses;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class AuthorTicketsController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index($authorId, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $authorId)->filter($filters)->paginate());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author, Ticket $ticket)
    {
        try {
            Gate::authorize('delete', $ticket);

            $ticket->delete();
            return ApiResponses::ok('Ticket successfully deleted');
        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to delete this resource');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, User $author)
    {
        try {
            //policy
            Gate::authorize('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes([
                'author' => $author->id
            ])));

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to store this resource');
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, User $author, Ticket $ticket)
    {
        //PUT
        try {
            Gate::authorize('replace', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to replace this resource');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, User $author, Ticket $ticket)
    {
        //PUT
        try {
            Gate::authorize('update', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to update this resource');
        }
    }
}
