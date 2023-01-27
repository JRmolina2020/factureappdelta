<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
     
        $company= DB::table('companies')
        ->select('id','nit','name','representative','phone','direction','city','note')
        ->where('user_id',auth()->id())
        ->get();
        return $company;
    }
    public function index_two(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $companies = DB::table('companies as c')
        ->select(
        'id',
        'nit',
        'name',
        'representative', 
        'phone',
        'direction',
        'city',
        'note'
        )->get();
        return $companies;
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         Company::create([
            'user_id' =>6,
            'nit' => $request['nit'],
            'name' => $request['name'],
            'representative' => $request['representative'],
            'phone' => $request['phone'],
            'direction' => $request['direction'],
            'city' => $request['city'],
            'note' => $request['note'],
           
        ]);
        return response()->json(['message' => 'Datos de la empresa registrado'], 200);
    }

    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $product = Company::find($id, ['id']);
        $product->fill([
            'nit' => request('nit'),
            'name' => request('name'),
            'representative' => request('representative'),
            'phone' => request('phone'),
            'direction' => request('direction'),
            'phone' => request('phone'),
            'city' => request('city'),
            'note' => request('note'),
  
        ])->save();
        return response()->json(['message' => 'Información modificada'], 201);
    }
    public function destroy(Request $request, $id)
    {   if (!$request->ajax()) return redirect('/');
        $product = Company::find($id);
        if (!$product) {
            return response()->json(["message" => "Datos no encontrado"], 404);
        }
        $product->delete();
        return response()->json(["message" => "Información eliminada"]);
    }
}
