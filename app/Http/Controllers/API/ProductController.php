<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  
    public function index()
    {
        $products = DB::table('products')->select('id', 'name','price','price_two','cost')
        ->orderBy('name', 'ASC')->get();
        return $products;
    }
 
   
    public function store(Request $request)
    {
         Product::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'price_two' => $request['price_two'],
            'cost' => $request['cost'],
        ]);
        return response()->json(['message' => 'El producto ha sido creado'], 200);
    }

    public function update($id)
    {
        $product = Product::find($id, ['id']);
        $product->fill([
            'name' => request('name'),
            'price' => request('price'),
            'price_two' => request('price_two'),
            'cost' => request('cost'),
           
        ])->save();
        return response()->json(['message' => 'El producto ha sido modificado'], 201);
    }
    public function destroy($id)
    {  
        $product = Product::find($id);
        if (!$product) {
            return response()->json(["message" => "Producto no encontrado"], 404);
        }
        $product->delete();
        return response()->json(["message" => "Producto eliminado"]);
    }

}
