<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\FactureController;
use App\Http\Controllers\API\FactureDetailController;
use App\Http\Controllers\AuthController;
use App\Models\Models\FactureDetail;

//routes view one
Route::get('/', function () {
    return view('login');
})->name('login');;
//route login functions 
Route::post('login',[AuthController::class, 'login']);
Route::group(['middleware' => 'auth'], function () {
//route view two
Route::get('/home', function () {
    return view('home');
});


Route::get('/usuarios', function () {
    return view('users.index');
});
Route::get('/roles', function () {
    return view('roles.index');
});
Route::get('/permisos', function () {
    return view('permissions.index');
});
Route::get('/perfil', function () {
    return view('users.profile');
});
Route::get('/productos', function () {
    return view('products.index');
});
Route::get('/clientes', function () {
    return view('clients.index');
});
Route::get('/facturas', function () {
    return view('factures.index');
});
Route::prefix('api')->group(function () {
//routes app fuctions
//routes users
Route::get('/users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::put('/user/password/{id}', [UserController::class, 'updatePassword'])->where('id', '[0-9]+');
Route::put('/users/{id}', [UserController::class, 'update']);
Route::put('/users/available/{id}', [UserController::class, 'available']);
Route::put('/users/locked/{id}', [UserController::class, 'locked']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
//end
//roles
Route::get('/roles', [RoleController::class, 'index']);
Route::post('roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
//end
//permissions
Route::get('/permissions', [PermissionController::class, 'index']);
Route::post('permissions', [PermissionController::class, 'store']);
Route::put('/permissions/{id}', [PermissionController::class, 'update']);
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);
//end
//products
Route::get('/products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update'])->where('id', '[0-9]+');
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
//end
//clients
Route::get('/clients', [ClientController::class, 'index']);
Route::post('clients', [ClientController::class, 'store']);
Route::put('/clients/{id}', [ClientController::class, 'update'])->where('id', '[0-9]+');
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->where('id', '[0-9]+');
//end
//facture
Route::post('factures', [FactureController::class, 'store']);
Route::get('/factures/{date}', [FactureController::class, 'index']);
Route::get('/factureUnique/{id}', [FactureController::class, 'factureUnique'])->where('id', '[0-9]+');
Route::get('/type_sale/{date}', [FactureController::class, 'type_sale']);
Route::delete('/factures/{id}', [FactureController::class, 'destroy'])->where('id', '[0-9]+');

//
//facture details
Route::get('/details/{id}', [FactureDetailController::class, 'index'])->where('id', '[0-9]+');
//end
});
});


