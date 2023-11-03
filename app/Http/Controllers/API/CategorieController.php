<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->select('id', 'name')->orderBy('id', 'desc')->get();
        return $categories;
    }
}
