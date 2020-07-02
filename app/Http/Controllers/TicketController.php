<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TicketFormRequest;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::take(10)->get();

        return view('admin_default.pages.ticket_index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin_default.pages.ticket_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketFormRequest $request)
    {
        $validator = $request->validated();

        $ticket = Ticket::create($request->all());
        $ticket['slug'] = uniqid();
        $save = $ticket->save();

        if ($save) {
            return redirect()->route('admin.tickets.index')
                ->withMessage('Congrats! You have created a ticket successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        return view('admin_default.pages.ticket_details', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return view('admin_default.pages.ticket_edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketFormRequest $request, $id)
    {
        $validator = $request->validated();

        try {
            $ticket = Ticket::find($id)->fill($request->all());
            $save = $ticket->save();
        } catch(\Exception $exp) {
            Log.info('aaa');
        }

        return redirect()->route('admin.tickets.show', $id)
            ->withMessage('Congrats! You have update this ticket successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function trash()
    {
        $tickets_trash = Ticket::onlyTrashed()->get();

        return view('admin_default.pages.ticket_trash', compact('tickets_trash'));
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id)->delete();
        
        if ($ticket->trashed()) {
            return redirect()->route('admin.tickets.index')
                ->withMessage('Congrats! You have sent a ticket to trash successfully');
        }
    }

    public function restore($id) {
        $ticket = Ticket::find($id)->restore();

        if ($ticket->restored()) {
            return redirect()->route('admin.tickets.index')
                ->withMessage('Congrats! You have restored a ticket successfully');
        }
    }
}
