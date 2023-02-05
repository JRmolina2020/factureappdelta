<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Money;
use Illuminate\Support\Facades\DB;

class MoneyController extends Controller
{
       
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $money= DB::table('money')->select('id', 'name','status')->orderBy('id', 'desc')->get();
        return $money;
    }
    public function index_two(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $money= DB::table('money')->select('id', 'name','status')
        ->where('status','1')
        ->orderBy('id', 'desc')
        ->get();
        return $money;
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         Money::create([
            'name' => $request['name'],
            'status' => $request['status'],
      
           
        ]);
        return response()->json(['message' => 'Cuenta registrada'], 200);
    }

    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $money = Money::find($id, ['id', 'name','status']);
        $money->fill([
            'name' => request('name'),
            'status' => request('status'),
        ])->save();
        return response()->json(['message' => 'Cuenta modificada'], 201);
    }
    public function destroy(Request $request, $id)
    {   if (!$request->ajax()) return redirect('/');
        $money= Money::find($id);
        if (!$money) {
            return response()->json(["message" => "Cuenta no encontrada"], 404);
        }
        $money->delete();
        return response()->json(["message" => "Cuenta eliminada"]);
    }
}
