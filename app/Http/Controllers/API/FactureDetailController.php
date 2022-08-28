<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FactureDetail;
use Illuminate\Support\Facades\DB;

class FactureDetailController extends Controller
{
    public function index(Request $request,$id)
    {
        if (!$request->ajax()) return redirect('/');
      $income = DB::table('facture_details as fd')
      ->join('products as p', 'p.id', '=', 'fd.product_id')
      ->join('factures as f', 'f.id', '=', 'fd.facture_id')
      ->select(
          'f.id',
          'fd.id AS idd',
          'p.name', 
          'fd.quantity',
          'fd.price',
          'fd.sub',
          'fd.disc',
          'fd.tot'
      )
      ->where('f.id', '=', $id)
      ->orderBy('fd.id', 'desc')->get();
  return $income;
    }
  
}
