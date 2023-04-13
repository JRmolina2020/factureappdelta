<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData($date)
    {
        
        $incomes = DB::table('incomes as i')
        ->join('users as u', 'u.id', '=', 'i.user_id')
        ->join('products as p', 'p.id', '=', 'i.product_id')
        ->select(
            'i.id',
            'i.quantity',
            'i.date_income',
            'i.created_at',
            'p.name as product',
            'p.id as product_id',
            'u.name as user'
        )
        ->where('i.date_income',$date)
        ->orderBy('i.id', 'desc')->get();
        return $incomes;
    }

    public function indexDatatwo ($date,$datetwo){
        $income = DB::table('incomes as i')
    ->join('products as p', 'p.id', '=', 'i.product_id')
    ->select(
    'p.name as product','p.id',
    DB::raw('SUM(i.quantity) as quantity'),
    )
    ->groupBy('p.name','p.id')
    ->whereBetween('i.date_income', [$date, $datetwo])
    ->get();
    return $income;
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Income::create([
            'user_id' =>auth()->id(),
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'date_income' => $request['date_income'],
           
        ]);
        return response()->json(['message' => 'Entrada registrada'], 200);
    }

  

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $income = Income::find($id);
        $income->fill([
            'product_id' => request('product_id'),
            'quantity' => request('quantity'),
            'date_income' => request('date_income'),
        ])->save();
        return response()->json(['message' => 'Entrada modificada'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          
        $income = Income::find($id);
        if (!$income) {
            return response()->json(["message" => "Entrada no encontrado"], 404);
        }
        $income->delete();
        return response()->json(["message" => "Entrada eliminada"]);
    }
}
