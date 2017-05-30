<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Response;
use App\Role;
use JWTAuth;
use Auth;
use File;

class RolesController extends Controller
{
    public function __construct()
    {
      $this->middleware("jwt.auth", ["only" => ["storeRole", "destroyRole", "updateRole"]]);
    }

    public function index ()
    {
      $role = Role::orderby("id","desc")->get();
      return Response::json($roles);
    }

    public function storeRole(Request $request)
    {
      $rules=[
        'roleName' => 'required',
      ];
      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(["error" => "Please fill out all fields."]);
      }
      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Not Authorized"]);
      }
      $role = new Role;
      $role->roleName = $request->input("roleName");
      $role->save();
      return Response::json(["success" => "Role was successfully added."]);
    }

    public function updateRole($id, Request $request)
    {
      $rules=[
        'roleName' => 'required',
      ];
      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(["error" => "Please fill out all fields"]);
      }
      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Not authorized"]);
      }

      $role = Role::find($id);
      $role->roleName = $request->input('roleName');
      $role->save();
      return Response::json(["success" => "Role Updated!"]);
    }

    public function showRole($id)
    {
      $role = Role::find($id);
      return Response::json($role);
    }

    public function destroyRole($id)
    {
      $role = Role::find($id);
      $role->delete();
      return Response::json(["success" => "Role Deleted!"]);
    }
}
