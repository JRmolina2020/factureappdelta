<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {;
         if (!$request->ajax()) return redirect('/');
        $roles = Role::with('permissions:id,name')->orderBy('id', 'desc')->get();
        return response()->json($roles);
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $roles = Role::create([
            'name' => $request['name'],

        ]);
        return response()->json(['message' => 'El rol ha sido creado'], 200);
    }
    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');
        $roles = Role::find($id);
        $roles->fill([
            'name' => request('name'),
        ])->save();
       $roles->syncPermissions($request['permissions']);
        return response()->json(['message' => 'El rol ha sido modificado'], 201);
    }
    public function destroy(Request $request, $id)
    {   if (!$request->ajax()) return redirect('/');
        $role = Role::find($id);
        if (!$role) {
            return response()->json(["message" => "Rol no encontrado"], 404);
        }
        $role->delete();
        return response()->json(["message" => "Rol eliminado"]);
    }
}
