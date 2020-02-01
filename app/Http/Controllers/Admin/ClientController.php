<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::where(function($q) use($request){

                  if($request->has('table_search'))
                  {

                    $search = $request->table_search;

                    if($search != '')
                    {
                        $activation = strpos($search , "active") !== false ? 1 :  null;
                        
                        $activation = strpos($search , "not")  !== false   ? 0 :  $activation;
                        
                        $q->where('name' , 'like' , '%'. $search . '%')
                            ->orWhere('email' , 'like' , '%' . $search . '%')
                            ->orWhere('phone' , 'like' , '%' . $search . '%' )
                            ->orWhere('is_active'  , $activation );                     
                    }
                  }
             })->paginate(5);

        return view('admin.clients.index' , compact('clients'));
    }

    public function show(Request $request , $id)
    {
        $client = Client::where('id' , $id)->withCount(['orders' , 'reviews' ])->first();
        return view('admin.clients.show' , compact('client'));
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if($client->orders()->count())
        {
            return redirect()->route('client.index')->with('error' , 'Can\'t delete this client , client has orders !');
        }

        if($client->reviews()->count())
        {
            return redirect()->route('client.index')->with('error' , 'Can\'t delete this client , client has reviews !');
        }

        $client->delete();

        return redirect()->route('client.index')->with('success' , 'client is deleted successfully');
    }

    public function toggle_activation(Request $request)
    {
        if($request->has('id'))
        {
            $client = Client::find($request->id);
            $client->is_active = !$client->is_active;
            $client->save();

            return responceJson(1 , 'success' , $client->is_active);
        }
    }
    
}
