<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use JWTAuth;
use Auth;
use File;

class ProductsController extends Controller
{
  public function __construct()
  {
    $this->middleware("jwt.auth", ["only" => ["storeProduct", "destroyProduct", "usdateProduct"]]);
  }

  public function index()
  {
    $products = Product::orderby("id","desc")->get();
    return Response::json($products);
  }

  public function storeProduct(Request $request)
  {
    $rules = [
      'product' => 'required',
      'categoryID' => 'required',
      'image' => 'required',
      'description' => 'required',
      'price' => 'required',
      'stock' => 'required',
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"Error. Please Fill Out All Fields!"]);
      }

      $user=Auth::user();
      if($user->roleID ! = 1)
      {
        return Response::json(["error" => "Not Allowed!"]);
      }

    $product = new Product;

    $product->product = $request->input('product');
    $product->categoryID = $request->input('categoryID');
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move('storage/', $imageName);
    $product->image = $request->root()."/storage/".$imageName;

    $product->save();

    return Response::json(["success" => "Congratulations, You Did It!"]);
  }

  public function updateProduct($id, Request $request)
  {
    $rules = [
      'product' => 'required',
      'categoryID' => 'required',
      'image' => 'required',
      'description' => 'required',
      'price' => 'required',
      'stock' => 'required',

    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);
    if($validator->fails())
    {
      return Response::json(['error'=>"ERROR! Product did not update!"]);
    }

    $user=Auth::user();
    if($user->roleID ! = 1)
    {
      return Response::json(["error" => "Not Allowed!"]);
    }

    $product = Product::find($id);

    $product->product = $request->input('product');
    $product->categoryID = $request->input('categoryID');
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move("storage/", $imageName);
    $product->image = $request->root()."storage/".$imageName;

    $product->save();

    return Response::json(['success' => "Product Has Been Updated!"]);
  }

  public function showProduct($id)
  {
    $product = Product::find($id);
    return Response::json($product);
  }

  public function destroyProduct($id)
  {
    $product = Product::find($id);
    $product->delete();
    return Response::json(['success' => "Product Deleted!"]);
  }
}
