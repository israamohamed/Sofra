<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::where(function($query) use($request){
        
            $type = $request->type;
            $search = $request->search;

            if($type)
            {
                $query->where('type' , $request->type);
            }
            if($search)
            {
                $query->where('name' ,    'like' , '%' . $request->search . '%')
                      ->orWhere('email' , 'like' , '%' . $request->search . '%')
                      ->orWhere('phone' , 'like' , '%' . $request->search . '%')
                      ->orWhere('message','like' , '%' . $request->search . '%');
            }
        })->paginate(5);
        return view('admin.contacts.index' , compact('contacts'));
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return back()->with('success' , 'Contact is deleted successfully');
    }
}
