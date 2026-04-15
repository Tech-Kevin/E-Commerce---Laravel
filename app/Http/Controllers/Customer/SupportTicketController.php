<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.support.tickets', compact('tickets'));
    }

    public function create()
    {
        return view('customer.support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'open',
        ]);

        return redirect()->route('support.show', $ticket)->with('success', 'Ticket created successfully');
    }

    public function show($ticketId)
    {
        $ticket = SupportTicket::with('replies.user')->findOrFail($ticketId);

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.support.show', compact('ticket'));
    }

    public function reply(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'reply_text' => 'required|string|max:1000',
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'reply_text' => $validated['reply_text'],
            'is_admin_reply' => false,
        ]);

        $ticket->update(['status' => 'waiting_customer']);

        return redirect()->route('support.show', $ticket)->with('success', 'Reply added successfully');
    }

    public function close($ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->update(['status' => 'closed']);

        return redirect()->route('support.index')->with('success', 'Ticket closed');
    }
}
