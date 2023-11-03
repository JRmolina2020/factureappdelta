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
        $products = DB::table('products as p')
            ->join('categories as c', 'c.id', '=', 'p.categorie_id')
            ->select(
                'p.id',
                'p.name',
                'p.price',
                'p.price_two',
                'p.cost',
                'p.color',
                'p.stock',
                'c.name as type',
                'c.id as idc',
            )
            ->orderBy('p.name', 'ASC')->get();
        return $products;
    }


    public function store(Request $request)
    {
        $products = $request->products;
        foreach ($products as $ep => $det) {
            $products = new Product();
            $products->name = $det['name'];
            $products->cost = $det['cost'];
            $products->price = $det['price'];
            $products->price_two = $det['price_two'];
            $products->categorie_id = $det['categorie_id'];
            $products->save();
        }
        return response()->json(['message' => 'El producto ha sido registrado'], 200);
    }

    public function update(Request $request)
    {
        $product = Product::find($request->id, ['id']);
        $products = $request->products;
        foreach ($products as $ep => $det) {
            $product->name = $det['name'];
            $product->cost = $det['cost'];
            $product->price = $det['price'];
            $product->price_two = $det['price_two'];
            $product->color = $det['color'];
            $product->categorie_id = $det['categorie_id'];
            $product->save();
        }
        return response()->json(['message' => 'El producto ha sido modificado'], 200);

        return $product;
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
