<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Response;
use Hash;
use App\User;
use JWTAuth;
use Auth;
use File;

class UsersController extends Controller
{
  public function __contruct()
  {
    $this->middleware("jwt.auth", ["except" => ["signIn", "signUp"]]);
  }

  public function index()
  {
    return File::get('index.html');
  }

  public function SignIn(Request $request)
  {
    $email = $request->input("email");
    $password = $request->input("password");
    $check = User::where("email","=",$email)->where("password","!=",NULL)->first();
    if(!empty($check)){
      $cred = ["email", "password"];
      $credentials = compact("email","password",$cred);

      $token = JWTAuth::attempt($credentials);

      return Response::json(compact("token"));
    }
    else {
      return Response::json(["empty" => "User not found"]);
    }
  }

  public function signUp(Request $request)
  {
    $email = $request->input("email");
    $password = $request->input("password");
    $username = $request->input("username");

    $check = User::where("email","=", $email)->orWhere("name","=",$username)->first();

    if(empty($check)){
      $user = new User;
      $user->name = $username;
      $user->email = $email;
      $user->roleID = 3;

      $user->password = Hash::make($password);
      $user->save();

      return Response::json(["success" => "Successful Sign Up!"]);
    }
  }

  public function getUser()
    {
      $user = Auth::user();
      $user = User::find($user->id);
      return Response::json($user);
    }
  }
