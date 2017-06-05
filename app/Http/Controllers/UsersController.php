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
  public function __construct()
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
    $cred = ["email", "password"];
    $credentials = compact("email","password",$cred);

    $token = JWTAuth::attempt($credentials);

    return Response::json(compact("token"));
  }

  public function signUp(Request $request)
  {
    $email = $request->input("email");
    $password = $request->input("password");
    $username = $request->input("username");
    $phone = $request->input("phone");
    $name = $request->input("name");
    $address = $request->input("address");


    $check = User::where("email","=", $email)->orWhere("username","=",$username)->first();

    if(empty($check)){
      $user = new User;
      $user->username = $username;
      $user->email = $email;
      $user->roleID = 3;
      $user->name = $name;
      $user->phone = $phone;
      $user->address = $address;



      $user->password = Hash::make($password);
      $user->save();

      return Response::json(["success" => "Successful Sign Up!"]);
    }
  }
  public function UpdateUsers($id, Request $request)
  {
  $rules = [
    'username' => 'required',
    'email' => 'required',
    'image' => 'required',
    'name' => 'required',
    'phone' => 'required',
    'address' => 'required',
    'bio' => 'required',
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);
    if($validator->fails())
    {
      return Response::json(['error'=>"ERROR! User Info did not Update!"]);
    }

    $user = User::find($id);

    $user->username = $request->input('username');
    $user->email = $request->input('email');
    $user->name = $request->input('name');
    $user->phone = $request->input('phone');
    $user->address = $request->input('address');
    $bio = $request->input("bio");
    $user->bio = $request->input('bio');
    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move("storage/", $imageName);
    $user->image = $request->root()."/storage/".$imageName;

    $user->save();

    return Response::json(['success' => "User Has Been Updated!"]);
  }
  public function getUser()
    {
      $user = Auth::user();
      $user = User::find($user->id);
      return Response::json(["user" => $user]);
    }

    public function allUsers()
    {
      $users = User::all();
      return Response::json($users);
    }
    public function showUser($id)
    {
      $user = User::find($id);
      return Response::json($user);
    }
  }
