<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\SupportTicket;
use Illuminate\Http\Request;

use Btcc\Http\Requests;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = user()->supportTickets;


        return view('support.index',['ticket'=>new SupportTicket(),'tickets'=>$tickets]);
    }



    public function create()
    {



        return view('support.create',['ticket'=>new SupportTicket()]);
    }



    public function store(Requests\CreateSupportTicketRequest $request)
    {
        $ticket = new SupportTicket();
        $ticket->fill($request->only(['subject','message']));
        $ticket->status = 1;
        $result = user()->supportTickets()->save($ticket);

        if ($result) {

            \Flash::success('Your ticket have been added');
        }

        return redirect()->route('supportTicket.index');
    }
}
