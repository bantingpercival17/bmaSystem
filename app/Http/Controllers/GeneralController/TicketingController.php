<?php

namespace App\Http\Controllers\GeneralController;

use App\Http\Controllers\Controller;
use App\Mail\TicketMail;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketChat;
use App\Models\TicketConcern;
use App\Models\TicketIssue;
use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TicketingController extends Controller
{

    public function ticket_view()
    {
        $_ticket_concern = TicketIssue::where('is_removed', 0)->get();
        $_department = Department::where('is_removed', 0)->get();
        $_concern_list = TicketIssue::where('is_removed', 0)->orderBy('department_id', 'asc')->get();
        $_ticket_list = Ticket::where('is_removed', 'false')->get();
        return view('pages.general-view.ticketing.view', compact('_ticket_concern', '_department', '_concern_list', '_ticket_list'));
    }

    public function ticket_concern_store(Request $_request)
    {
        $_request->validate([
            'concern_name' => 'string|required',
            'department' => 'required'
        ]);

        TicketIssue::create([
            'issue_name' => ucwords(strtolower($_request->concern_name)),
            'department_id' => $_request->department
        ]);
        return back()->with('success', 'Successfully Create an Concern Issue');
    }
    public function ticket_concern_removed(Request $_request)
    {
        $_concern = TicketIssue::find(base64_decode($_request->concern));
        $_concern->is_removed = 1;
        $_concern->save();
        return back()->with('success', 'Successfully Removed an Concern Issue');
    }

    public function ticket_concern_view(Request $_request)
    {
        try {
            $_department = Department::where('code', Auth::user()->staff->department)->first();;
            $_issues =  TicketIssue::select('ticket_concerns.*')
                ->join('ticket_concerns', 'ticket_concerns.issue_id', 'ticket_issues.id')
                ->where('ticket_concerns.is_removed', false)
                ->where('ticket_concerns.is_resolved', false)
                ->where('ticket_issues.department_id', $_department->id)
                /* ->where('ticket_issues.is_removed', false) */->orderBy('ticket_concerns.created_at', 'desc')->get();
            $_ticket_issue = TicketIssue::where('is_removed', false)->where('department_id', '!=', $_department->id)->groupBy('department_id')->get();
            $_ticket = $_request->_ticket ? TicketConcern::find(base64_decode($_request->_ticket)) : [];

            if ($_request->_ticket) {
                // return $_ticket->is_ongoing;
                if ($_ticket->is_ongoing == 0) {
                    $_ticket->is_ongoing = 1;
                    $_ticket->save();
                }
                $_ticket_concerns = TicketConcern::where('ticket_id', $_ticket->ticket_id)->orderBy('created_at', 'asc')->get();
                $group_id = (Auth::id() > $_ticket->id) ? Auth::id() . $_ticket->id : $_ticket->id . Auth::id();
                //$messages = TicketChat::where('ticket_id', $_ticket->id)->get();
            } else {
                // $messages = [];
                $_ticket_concerns = [];
            }
            return view('pages.general-view.ticketing.user-view.message', compact('_issues', '_ticket',  '_ticket_issue', '_ticket_concerns'));
        } catch (\Exception $er) {
            return back()->with('error', $er->getMessage());
        }
    }
    public function ticket_message_chat(Request $_request)
    {
        //(Auth::id() > $_request->user) ? Auth::id() . $_request->user : $_request->user . Auth::id();
        $_data = array(
            'concern_id' => $_request->ticket,
            'staff_id' => $_request->staff,
            'sender_column' => 'staff_id',
            'message' => $_request->message,
            'group_id' => ($_request->staff > $_request->ticket) ? $_request->staff . $_request->ticket : $_request->ticket . $_request->staff,
        );
        try {
            $_ticket_chat = TicketChat::create($_data);
            //return $_ticket_chat->ticket->email;
            Mail::to($_ticket_chat->ticket->email)->send(new TicketMail($_ticket_chat->ticket));
            $data = array(
                'respond' => 200,
                'data' => $_data
            );
        } catch (\Exception $error) {
            $data = array(
                'respond' => 404,
                'message' => $error->getMessage()
            );
        }

        return compact('data');
    }
    public function ticket_concern_remove(Request $_request)
    {
        $_ticket = TicketConcern::find(base64_decode($_request->_concern));
        $_ticket->is_removed = true;
        $_ticket->save();
        /*   $_ticket->ticket_issue->is_removed = true;
        $_ticket->ticket_issue->save(); */
        return redirect(route('ticket.view'))->with('success', 'Successfully Removed');
    }
    public function ticket_concern_unseen(Request $_request)
    {
        $_ticket = TicketConcern::find(base64_decode($_request->_concern));
        $_ticket->is_ongoing = false;
        $_ticket->save();
        return redirect(route('ticket.view'))->with('success', 'Successfully');
    }
    public function ticket_concern_solve(Request $_request)
    {
        $_ticket = TicketConcern::find(base64_decode($_request->_concern));
        $_ticket->is_resolved = true;
        $_ticket->save();
        return redirect(route('ticket.view'))->with('success', 'Ticket Close');
    }
    public function ticket_transfer_concern(Request $_request)
    {
        try {
            $_ticket_concern = TicketConcern::find(base64_decode($_request->_ticket)); // Get First the Previous ticket concern
            //$_ticket_concern->is_resolved = true;
            $_ticket_concern->save();
            $_ticket = TicketConcern::create([
                'ticket_id' => $_ticket_concern->id,
                'issue_id' => base64_decode($_request->_transfer),
                'ticket_message' => 'CONCERN TRANSFER BY ' . Auth::user()->staff->department
            ]);
            //return $_ticket->id;
            $_data = array(
                'concern_id' => $_ticket->id,
                'staff_id' => Auth::user()->staff->id,
                'sender_column' => 'staff_id',
                'message' => "Good day, I'll tranfer this concern to designated department, the issue concern was " . $_ticket->ticket_issue->issue_name,
                'group_id' => (Auth::user()->staff->id > $_ticket->id) ? Auth::user()->staff->id . $_ticket->id : $_ticket->id . Auth::user()->staff->id,
            );
            $_ticket_chat = TicketChat::create($_data);
            return back()->with('success', 'Concern Transfer');
        } catch (\Exception $er) {
            return back()->with('error', $er->getMessage());
        }
    }
}
