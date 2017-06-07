<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use JWTAuth;
use Auth;
use File;

class CategoriesController extends Controller
{
    public function __construct()
    {
      $this->middleware("jwt.auth", ["only" => ["storeCategory", "destroyCategory"]]);
    }

    public function home()
    {
      return File::get("index.html");
    }

    public function index()
    {
      $categories = Category::orderBy("id", "asc")->get();
      return Response::json($categories);
    }

    public function storeCategory(Request $request)
    {
      $rules = [
        "category" => "required",
      ];
      $validator = Validator::make(Purifier::clean($request->all()),$rules);
      if($validator->fails())
      {
        return Response::json(["error" => "You need to fill out all fields"]);
      }
      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Not Allowed!"]);
      }
      $category = new Category;
      $category->category = $request->input("category");
      $category->save();
      return Response::json(["success" => "Category was successfully added!"]);
    }

    public function updateCategory($id, Request $request)
    {
      $rules = [
        'category' => 'required',
      ];
      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"ERROR! Category did not update!"]);
      }
      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Not Allowed!"]);
      }
      $category = Category::find($id);
      $category->save();
      return Response::json(['success' => "Category has been updated!"]);
    }

    public function showCategory($id)
    {
      $category = Category::find($id);
      return Response::json($category);
    }

    public function destroyCategory($id)
    {
      $category = Category::find($id);
      $category->delete();
      return Response::json(['success' => "Category Deleted!"]);
    }
}
