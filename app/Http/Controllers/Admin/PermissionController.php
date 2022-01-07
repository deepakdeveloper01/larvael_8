<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Routing\Controller as BaseController;

class PermissionController extends BaseController
{
    function __construct()
    {
        /*$this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','store']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
*/
    }

    public function index(Request $request)
    {
        $roles = Permission::orderBy('id','DESC')->paginate(5);
        return view('admin.permissions.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin.permissions.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('admin.permissions.index')->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Permission::find($id);
        return view('admin.permissions.show',compact('role'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin.permissions.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('admin.permissions.index')->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('admin.permissions.index')->with('success','Role deleted successfully');
    }
}