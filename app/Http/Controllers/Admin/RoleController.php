<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class RoleController extends BaseController
{
    function __construct()
    {
        /*$this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);*/

    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('admin.roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin.roles.create',compact('permission'));
    }

    public function store(Request $request)
    {
        /*$this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);*/

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);
       // redirect on validation error
        if ( $validation->fails() ) {
            // change below as required
            return \Redirect::back()->withInput()->withErrors( $validation->messages() );
        }

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
       /* $this->validate($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);*/

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);
       // redirect on validation error
        if ( $validation->fails() ) {
            // change below as required
            return \Redirect::back()->withInput()->withErrors( $validation->messages() );
        }


        $role = Role::find($id);
        /**
         * undocumented constant
         **/
       
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('admin.roles.index')->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('admin.roles.index')->with('success','Role deleted successfully');
    }
}