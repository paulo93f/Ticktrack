<?php

use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthorController;


Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class);

Route::middleware('auth:sanctum')->apiResource('authors', AuthorController::class);
Route::middleware('auth:sanctum')->apiResource('authors.tickets', AuthorTicketsController::class);
