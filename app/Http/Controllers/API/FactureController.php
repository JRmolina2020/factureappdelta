<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\FactureDetail;
use App\Models\Client;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;


class FactureController extends Controller
{

    public function store(Request $request)
    {
       

        try {
            $mytime=Carbon::now('America/Bogota');
            DB::beginTransaction();
            $facture = new Facture();
            $facture->client_id = $request->client_id;
            $facture->date_facture =$mytime->toDateString();
            $facture->sub = $request->sub;
            $facture->disc = $request->disc;
            $facture->tot = $request->tot;
            $facture->efecty = $request->efecty;
            $facture->other = $request->other;
            $facture->note = $request->note;
            $facture->status = $request->status;
            $facture->type_sale = $request->type_sale;
            $facture->user_id=auth()->id();
            $facture->save();
            $details = $request-> dataDetails;
            foreach ($details as $ep => $det) {
                $details = new FactureDetail();
                $details->facture_id = $facture->id;
                $details->product_id = $det['product_id'];
                $details->quantity = $det['quantity'];
                $details->price = $det['price'];
                $details->sub = $det['sub'];
                $details->disc = $det['disc'];
                $details->tot = $det['tot'];
                $details->save();
            }
            DB::commit();
            return response()->json(['message' => 'Venta registrada'], 200);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
  public function index(Request $request,$date)
  {
    if (!$request->ajax()) return redirect('/');
    $facture = DB::table('factures as f')
    ->join('clients as c', 'c.id', '=', 'f.client_id')
    ->join('users as u', 'u.id', '=', 'f.user_id')
    ->select(
        'f.id',
        'f.date_facture',
        'f.tot',
        'f.efecty',
        'f.other',
        'f.note',
        'f.status',
        'f.type_sale',
        'u.name',
        'c.nit',
        'c.fullname',
    )
    ->where('f.date_facture',$date)
    ->orderBy('f.id', 'desc')->get();
return $facture;
  }
  public function factureUnique(Request $request,$id)
    {
        if (!$request->ajax()) return redirect('/');
      $facture = DB::table('factures as f')
      ->join('clients as c', 'c.id', '=', 'f.client_id')
      ->select(
          'f.id',
          'f.date_facture',
          'f.sub',
          'f.disc', 
          'f.tot',
          'f.note',
          'f.status',
          'f.type_sale',
          'c.nit',
          'c.fullname',
          'c.phone',
          'f.created_at'

      )
      ->where('f.id', '=', $id)
      ->orderBy('f.id', 'desc')->get();
  return $facture;
    }
    

public function type_sale (Request $request,$date){
    if (!$request->ajax()) return redirect('/');
    $facture_tot = DB::table('factures')
    ->select(
    DB::raw('SUM(tot) as tot'),
    DB::raw('SUM(efecty) as efecty'),
    DB::raw('SUM(other) as other'),
    )->where('date_facture',$date)
    ->where('status',1)
    ->get();
    return $facture_tot;
}
//nequi
public function type_sale_one (Request $request,$date,$type){
    if (!$request->ajax()) return redirect('/');
    $facture_tot = DB::table('factures')
    ->select(
    DB::raw('SUM(other) as other'),
    )
    ->where('date_facture',$date)
    ->where('type_sale',$type)
    ->where('status',1)
    ->get();
    return $facture_tot;
}

public function updateStatus($id)
{
    
    $facture = Facture::findOrFail($id, ['id']);
    $facture->status = '1';
    $facture->save();
    return response()->json(["message" => "El estado ha cambiado a pagado"]);
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
