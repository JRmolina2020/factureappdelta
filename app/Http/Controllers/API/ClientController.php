<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
class ClientController extends Controller
{
       // all posts
       public function index(Request $request)
       {
           if (!$request->ajax()) return redirect('/');
           $clients= DB::table('clients')->select('id', 'nit','fullname','phone',)->orderBy('id', 'desc')->get();
           return $clients;
       }
    
       // add post
       public function store(Request $request)
       {
           if (!$request->ajax()) return redirect('/');
            Client::create([
                'nit' => $request['nit'],
               'fullname' => $request['fullname'],
               'phone' => $request['phone'],
              
           ]);
           return response()->json(['message' => 'Cliente registrado'], 200);
       }
   
       public function update(Request $request, $id)
       {
           if (!$request->ajax()) return redirect('/');
           $product = Client::find($id, ['id', 'nit','fullname', 'phone','city']);
           $product->fill([
               'nit' => request('nit'),
               'fullname' => request('fullname'),
               'phone' => request('phone'),
     
           ])->save();
           return response()->json(['message' => 'El cliente ha sido modificado'], 201);
       }
       public function destroy(Request $request, $id)
       {   if (!$request->ajax()) return redirect('/');
           $product = Client::find($id);
           if (!$product) {
               return response()->json(["message" => "Cliente no encontrado"], 404);
           }
           $product->delete();
           return response()->json(["message" => "Cliente eliminado"]);
       }
}
