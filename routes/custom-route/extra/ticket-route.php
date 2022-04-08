<?php

use App\Http\Controllers\GeneralController\TicketingController;
use Illuminate\Support\Facades\Route;

Route::get('/ticket', [TicketingController::class, 'ticket_view'])->name('admin.ticket');
Route::post('/ticket/concern-store', [TicketingController::class, 'ticket_concern_store'])->name('concern-store');
Route::get('/ticket/concern-removed', [TicketingController::class, 'ticket_concern_removed'])->name('concern-removed');

Route::get('ticket/view', [TicketingController::class, 'ticket_concern_view'])->name('ticket.view');
Route::post('/ticket/chat-message', [TicketingController::class, 'ticket_message_chat'])->name('ticket.chat-store');
