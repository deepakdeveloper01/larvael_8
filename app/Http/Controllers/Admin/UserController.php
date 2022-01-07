<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Validator;

class UserController extends BaseController
{
    public function index(Request $request){

        $data = User::orderBy('id','DESC')->paginate(5);
        return view('admin.users.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create() {
        $roles = Role::pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }

    public function store(Request $request) {


        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        if ( $validation->fails() ) {
            // change below as required
            return \Redirect::back()->withInput()->withErrors( $validation->messages() );
        }
  
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('admin.users.index') ->with('success','User created successfully');

    }

    public function show($id){
        $user = User::find($id);
        return view('admin.users.show',compact('user'));
    }

    public function edit($id) {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id){
      $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        if ( $validation->fails() ) {
            // change below as required
            return \Redirect::back()->withInput()->withErrors( $validation->messages() );
        }
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        
        $user->assignRole($request->roles);
        return redirect()->route('users.index')->with('success','User updated successfully');

    }

    public function destroy($id){
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

}