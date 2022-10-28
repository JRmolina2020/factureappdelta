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
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
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
    ->select(
        'f.id',
        'f.date_facture',
        'f.tot',
        'f.efecty',
        'f.other',
        'f.note',
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
    )->where('date_facture',$date)->get();
    return $facture_tot;
}
public function pos_facture(){
    $connector = new WindowsPrintConnector("POS58");
    $printer = new Printer($connector);
    //DATA
    $facture=Facture::find(16);
    $client = Client::where('id', $facture->client_id)->first();
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
    ->where('f.id', '=', 16)
    ->orderBy('p.name', 'ASC')->get();
    //END
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(2,2);
    $printer->text(strtoupper('BOLSOSVALLEDUPAR'));
    $printer->setTextSize(1,1);
    $id= str_pad($facture->id,7,"0",STR_PAD_LEFT);
    $printer -> text("Recibo de cotizacion#"."00000".$facture->id."\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer -> text("============================\n");
    $printer->text('fecha:' . Carbon::parse($facture->created_at)->format('d/m/Y h:m:s'). "\n");
    $printer -> text("============================\n");
    $printer -> text("CLIENTE:"." ".$client->fullname."\n");
    $printer -> text($facture->note."\n\n");
    $printer -> text("============================\n");
    $printer -> text("PRODUCTOS:\n");
    $printer -> text("============================\n");
    $products=json_decode($income);
    for($i=0;$i<count($products);$i++){
        $product= new FactureDetail();
        $name=$product->name=$products[$i]->name;
        $quantity=$product->quantity=$products[$i]->quantity;
        $price=$product->price=$products[$i]->price;
        $sub=$product->sub=$products[$i]->sub;
        $printer -> text($quantity."X"." ".$name."*".number_format($price,2). "\n");
        $printer -> text(number_format($sub,2). "\n\n");
       
    } 
    $printer -> text("*****************************\n");
    $printer -> text("SUB:"." ".number_format($facture->sub,2)."\n");
    $printer -> text("DES:"." ".number_format($facture->disc,2)."\n");
    $printer -> text("TOT:"." ".number_format($facture->tot,2)."\n");
    $printer -> text("*****************************\n\n");
    $printer -> text("Gracias por tu compra, recuerda verificar tus productos o pedido, los cambios se realizan antes de las 24hrs de la compra."."\n\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->selectPrintMode();
    $printer->setBarcodeHeight(80);
    $printer->barcode($id,Printer::BARCODE_CODE39);
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer -> cut();
    $printer -> close();
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
