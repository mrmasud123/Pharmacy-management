<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
 
use Yajra\DataTables\Facades\DataTables;
class RolesController extends Controller
{
    public function index()
    {
        $roles= Role::with('permissions')->get();
        return view('admin.roles.index', ['title' => 'Roles', 'roles' => $roles]);
    }
    
    public function create(){
        return view('admin.roles.create', ['title' => 'Roles','title' => 'Create Role']);
    }
    
    public function store(Request $request){
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validatedData['name'],
        ]);

        if (isset($validatedData['permissions'])) {
            $role->permissions()->attach($validatedData['permissions']);
        }

        return redirect()->route('admin.roles')->with('success', 'Role created successfully.');
    }
    
    public function show(Role $role){
        $role->load('permissions');
        $permissions= Permission::all()->makeHidden(['created_at', 'updated_at']);
        return view('admin.roles.show-permissions', ['title' => 'Role Details', 'role' => $role, 'permissions' => $permissions]);
    }
    
    public function assignPermission(Request $request, Role $role){
        $validatedData = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (isset($validatedData['permissions'])) {
            $role->permissions()->sync($validatedData['permissions']);
        }

        return redirect()->route('admin.roles')->with('success', 'Permissions updated successfully.');
    }


    public function data()
    {
        return DataTables::of(Role::query()->with('permissions'))
            ->addColumn('permissions', function ($role) {
    
                $perms = $role->permissions;
    
                if ($perms->isEmpty()) {
                    return '<span class="text-gray-500">No permissions</span>';
                }
    
                $html = $perms->take(3)->map(function ($p) {
                    return '<span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600">'.$p->name.'</span>';
                })->implode(' ');
    
                if ($perms->count() > 3) {
                    $html .= '<span class="text-xs text-gray-500 ml-1">+'.($perms->count()-3).' more</span>';
                }
    
                return $html;
            })
    
            ->addColumn('action', function ($role) {
                return '
                    <a href="'.route('admin.add.permissions.to.role', $role->id).'" class="text-yellow-600 mr-2">Permissions</a>
                    <button class="text-blue-600 mr-2 editBtn" data-id="'.$role->id.'">Edit</button>
                    <button class="text-red-600 deleteBtn" data-id="'.$role->id.'">Delete</button>
                ';
            })
    
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }
}