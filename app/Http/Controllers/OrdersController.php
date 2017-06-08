<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Auth;
use JWTAuth;
use File;

class OrdersController extends Controller
{
  public function __construct()
  {
    $this->middleware("jwt.auth", ["only" => ["index", "showUserOrders", "storeOrder", "destroyOrder", "updateOrder"]]);
  }

  public function index()
  {
    $user = Auth::user();
    $orders = Order::join("users", "orders.userID", "=", "users.id")
                    ->join("products", "orders.productID", "=", "products.id")
                    ->orderby("orders.id", "desc")
                    ->select("orders.id", "orders.amount", "orders.totalPrice", "orders.userID", "orders.productID", "orders.comment", "users.name", "products.product", "products.stock")
                    ->get();

    if($user->roleID != 1)
    {
      return Response::json(["error" => "Not Allowed!"]);
    }

    return Response::json($orders);
  }

  public function storeOrder(Request $request)
  {
    $rules = [
      'productID' => 'required',
      'amount' => 'required',
    ];
    $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"Error. Please Fill Out All Fields!"]);
      }

    $product = Product::find($request->input("productID"));
    if(empty($product))
    {
      return Response::json(["error" => "Invalid Product."]);
    }

    if($product->stock==0)
    {
      return Response::json(["error" => "Sorry, product is not available at this time."]);
    }

    $order = new Order;
    $order->userID = Auth::user()->id;
    $order->productID = $request->input('productID');
    $order->amount = $request->input('amount');
    $order->totalPrice = $request->input('amount')*$product->price*$product->months;

    $order->comment = $request->input("comment");
    $order->save();

    return Response::json(["success" => "Your Order was Created", "total" => $order->totalPrice ]);
  }

  public function updateOrder($id, Request $request)
  {
    $rules = [
      'amount' => 'required',
      'comment' => 'required',
      'productID' => 'required',
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(['error'=>"ERROR! Please fill out all fields."]);
    }

    $product = Product::find($request->input("productID"));
    if(empty($product))
    {
      return Response::json(["error" => "Product not found."]);
    }

    if($product->stock==0)
    {
      return Response::json(["error" => "Sorry, product is not available at this time."]);
    }

    $order = Order::find($id);
    $order->userID = Auth::user()->id;
    $order->amount = $request->input("amount");
    $order->totalPrice = $request->input('amount')*$product->price*$product->months;
    $order->comment = $request->input("comment");
    $order->save();

    return Response::json(['success' => "Order Has Been Updated!", "total" => $order->totalPrice]);
  }

  public function showOrder($id)
  {
    $order = Order::find($id);
    return Response::json($order);
  }

  public function destroyOrder($id)
  {
    $order = Order::find($id);
    $user = Auth::user();
    if($user->roleID != 1 || $user->id != $order->userID)
    {
      return Response::json(["error" => "Not Allowed!"]);
    }

    $order->delete();
    return Response::json(['success' => "Order Deleted!"]);
  }
  public function showUserOrders()
  {
    $user = Auth::user();
    $orders = Order::where("orders.userID", "=", $user->id)
                    ->join("users", "orders.userID", "=", "users.id")
                    ->join("products", "orders.productID", "=", "products.id")
                    ->orderby("orders.id", "desc")
                    ->select("orders.id", "orders.amount", "orders.totalPrice", "orders.userID", "orders.productID", "orders.comment", "users.name", "products.product", "products.stock")
                    ->get();
    return Response::json($orders);
  }
}
