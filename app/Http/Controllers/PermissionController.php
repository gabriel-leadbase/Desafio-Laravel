<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        if ($request->user()->tokenCan('admin-roles')) {
            return response()->json([
                ['id' => 1, 'nome' => 'Carne'],
                ['id' => 1, 'nome' => 'arroz'],
                ['id' => 1, 'nome' => 'fruta']
            ]);
        } else {
            return response()->json([
                'message' => "Unauthenticated",
            ]);
        }
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('admin-roles')) {
            return response()->json([
                'message' => "Unauthenticated",
            ]);
        }
        $request->validate([
            'name' => 'required|string|unique:permissions',
        ]);

        $permission = new Permission([
            'name' => $request->name,
        ]);
        $permission->save();
        return response()->json([
            'res' => 'Permission created'
        ], 201);
    }


    public function destroy(Request $request)
    {
        if (!$request->user()->tokenCan('admin-roles')) {
            return response()->json([
                'message' => "Unauthenticated",
            ]);
        }
        $permission = Permission::where('name', $request->name)->first();
        if($permission){
            $permission->delete();
            return response()->json(['Permission deleted with success!'], 200);
        }
        return response()->json(['Permission not found'], 200);
    }
}
