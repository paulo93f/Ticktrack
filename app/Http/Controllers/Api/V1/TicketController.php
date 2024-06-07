<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponses;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    /**
     * Get all tickets
     *
     * @group Managing Tickets
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=title,-createdAt
     * @queryParam filter[status] Filter by status code: A, C, H, X. No-example
     * @queryParam filter[title] Filter by title. Wildcards are supported. Example: *fix*
     */
    public function index(TicketFilter $filter)
    {
        return TicketResource::collection(Ticket::filter($filter)->paginate());
    }

    /**
     * Create a ticket
     *
     * Creates a new ticket record. Users can only create tickets for themselves. Managers can create tickets for any user.
     *
     * @group Managing Tickets
     *
     * @response {"data":{"type":"ticket","id":107,"attributes":{"title":"asdfasdfasdfasdfasdfsadf","description":"test ticket","status":"A","createdAt":"2024-03-26T04:40:48.000000Z","updatedAt":"2024-03-26T04:40:48.000000Z"},"relationships":{"author":{"data":{"type":"user","id":1},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/1"}}},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/tickets\/107"}}}
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            //policy
            Gate::authorize('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes()));

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to store this resource');
        }
    }

    /**
     * Show a specific ticket.
     *
     * Display an individual ticket.
     *
     * @group Managing Tickets
     *
     */
    public function show(Ticket $ticket)
    {
        if ($this->isParamIncluded('author')) {
            return new TicketResource($ticket->load('author'));
        }

        return new TicketResource($ticket);
    }

    /**
     * Update Ticket
     *
     * Update the specified ticket in storage.
     *
     * @group Managing Tickets
     *
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //PATCH
        try {
            //policy
            Gate::authorize('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to update this resource');
        }
    }

    /**
     * Replace Ticket
     *
     * Replace the specified ticket in storage.
     *
     * @group Managing Tickets
     *
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        //PUT
        try {
            //policy
            Gate::authorize('replace', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to replace this resource');
        }
    }

    /**
     * Delete ticket.
     *
     * Remove the specified resource from storage.
     *
     * @group Managing Tickets
     *
     */
    public function destroy(Ticket $ticket)
    {
        try {
            //policy
            Gate::authorize('delete', $ticket);

            $ticket->delete();

            return ApiResponses::ok('Ticket successfully deleted');
        } catch (AuthorizationException $exception) {
            return ApiResponses::notAuthorized('You are not authorize to delete this resource');
        }

    }
}
