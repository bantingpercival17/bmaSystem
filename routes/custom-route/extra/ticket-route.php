<?php

use App\Http\Controllers\GeneralController\TicketingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/ticket', [TicketingController::class, 'ticket_view'])->name('admin.ticket');
    Route::post('/ticket/concern-store', [TicketingController::class, 'ticket_concern_store'])->name('concern-store');
    Route::get('/ticket/concern-removed', [TicketingController::class, 'ticket_concern_removed'])->name('concern-removed');

    Route::get('ticket/view', [TicketingController::class, 'ticket_concern_view'])->name('ticket.view');
    Route::post('/ticket/chat-message', [TicketingController::class, 'ticket_message_chat'])->name('ticket.chat-store');
    Route::get('/ticket/concern-remove', [TicketingController::class, 'ticket_concern_remove'])->name('ticket.concern-remove');
    Route::get('/ticket/concern-unseen', [TicketingController::class, 'ticket_concern_unseen'])->name('ticket.concern-unseen');
    Route::get('/ticket/concern-solve', [TicketingController::class, 'ticket_concern_solve'])->name('ticket.concern-solve');
    Route::get('/ticket/transfer-concern', [TicketingController::class, 'ticket_transfer_concern'])->name('ticket.transfer-concern');
});
