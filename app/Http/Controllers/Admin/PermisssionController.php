<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermisssionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        return view('admin.permissions.index', [ 'permissions' => $permissions, 'title' => "Permissions"]);
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

       $permission= Permission::create([
            'name' => $validatedData['name'],
        ]);
        $createdBy= Auth::user()->name;
        $redirectRoute = route('admin.permissions');
        $data=[
            'title'      => 'Permission Created',
            'message'    => "Permission \"{$permission->name}\" was created by {$createdBy}.",
            'name'  => $permission->name,
            'redirect_route' => $redirectRoute,
            'created_by' => $createdBy,
        ];
        app(NotificationService::class)->sendNotification($data);
        return redirect()->route('admin.permissions')->with('success', 'Permission created successfully.');
    }
}
