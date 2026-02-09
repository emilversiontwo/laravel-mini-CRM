<?php

namespace App\Http\Controllers\Web\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->cookie('api_token')) {
            $token = $request->cookie('api_token');
        } else {
            $token = $request->user()->createToken('token')->plainTextToken;
        }

        return view('AdminPanel.Ticket.index', compact('token'));
    }

    public function edit(Ticket $ticket)
    {
        return view('AdminPanel.Ticket.edit', compact('ticket'));
    }
}
