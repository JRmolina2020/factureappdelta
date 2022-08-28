<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // all posts
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $products = DB::table('products')->select('id', 'name','price','price_two')->orderBy('id', 'desc')->get();
        return $products;
    }
 
    // add post
    public function store(Request $request)
    {
       return 'hola';
    }

    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $product = Product::find($id, ['id', 'name', 'price','price_two']);
        $product->fill([
            'name' => request('name'),
            'price' => request('price'),
            'price_two' => request('price_two'),
           
        ])->save();
        return response()->json(['message' => 'El producto ha sido modificado'], 201);
    }
    public function destroy(Request $request, $id)
    {   if (!$request->ajax()) return redirect('/');
        $product = Product::find($id);
        if (!$product) {
            return response()->json(["message" => "Producto no encontrado"], 404);
        }
        $product->delete();
        return response()->json(["message" => "Producto eliminado"]);
    }

}
