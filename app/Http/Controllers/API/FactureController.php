<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{

  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $income = DB::table('factures as f')
    ->join('clients as c', 'c.id', '=', 'f.client_id')
    ->select(
        'f.id',
        'f.date_facture',
        'f.tot',
        'c.nit',
        'c.fullname',
        'f.state',
    )
    ->orderBy('f.id', 'desc')->get();
return $income;
  }
  public function factureUnique($id)
    {
      $income = DB::table('factures as f')
      ->join('clients as c', 'c.id', '=', 'f.client_id')
      ->select(
          'f.id',
          'f.date_facture',
          'f.sub',
          'f.disc', 
          'f.tot',
          'f.state',
          'c.nit',
          'c.fullname',
          'c.phone'

      )
      ->where('f.id', '=', $id)
      ->orderBy('f.id', 'desc')->get();
  return $income;
    }
  // add post
  public function store(Request $request)
  {
      if (!$request->ajax()) return redirect('/');
       Facture::create([
          'client_id' => $request['client_id'],
          'date_facture' => $request['date_facture'],
          'sub' => $request['sub'],
          'disc' => $request['disc'],
          'tot' => $request['tot'],
      ]);
      return response()->json(['message' => 'Factura creada'], 200);
  }

  public function update(Request $request, $id)
  {
      if (!$request->ajax()) return redirect('/');
      $facture = Facture::find($id, ['id']);
      $facture->fill([
          'client_id' => request('client_id'),
      ])->save();
      return response()->json(['message' => 'La cabezera de la factura ha sido modificada'], 201);
  }
  public function destroy(Request $request, $id)
  {   if (!$request->ajax()) return redirect('/');
      $facture = Facture::find($id);
      if (!$facture) {
          return response()->json(["message" => "Facturada no encontrada"], 404);
      }
      $facture->delete();
      return response()->json(["message" => "Factura eliminada"]);
  }

}
