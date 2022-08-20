<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\FactureDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
            $facture = new Facture();
            $facture->client_id = $request->client_id;
            $facture->date_facture ='2022/01/02' ;
            $facture->sub = $request->sub;
            $facture->disc = $request->disc;
            $facture->tot = $request->tot;
            $facture->state = $request->state;
            $facture->save();
            $details = $request-> dataDetails;
            foreach ($details as $ep => $det) {
                $details = new FactureDetail();
                $details->facture_id = $facture->id;
                $details->product_id = $det['product_id'];
                $details->quantity = $det['quantity'];
                $details->price = $det['price'];
                $details->sub = $det['sub'];
                $details->save();
            }
            DB::commit();
            return response()->json(['message' => 'Venta registrada'], 200);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
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
