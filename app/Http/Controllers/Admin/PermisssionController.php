<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisssionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        return view('admin.permissions.index', [ 'permissions' => $permissions]);
    }
    
    public function create()
    {
        return view('admin.permissions.create', ['title' => 'Create Permission']);
    }
    
    public function store(Request $request)
    {
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $validatedData['name'],
        ]);

        return redirect()->route('admin.permissions')->with('success', 'Permission created successfully.');
    }
}
