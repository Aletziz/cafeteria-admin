<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = Contact::unread()->count();
        
        return view('contacts.index', compact('contacts', 'unreadCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Marcar como leído si no lo está
        if (!$contact->read) {
            $contact->update(['read' => true]);
        }
        
        return view('contacts.show', compact('contact'));
    }

    /**
     * Mark as read/unread.
     */
    public function toggleRead(Contact $contact)
    {
        $contact->update(['read' => !$contact->read]);
        
        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('contacts.index')->with('success', 'Mensaje eliminado correctamente.');
    }
}
