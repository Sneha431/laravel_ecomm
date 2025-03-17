<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductManager extends Controller
{
    function index()
    {
        // $products = Products::all();
        // $products = Products::paginate(2);
        $products = Products::paginate(10);
        return view("products", compact("products"));
    }
    function details($slug)
    {
        $product = Products::where('slug', $slug)->first();
        return view("details", compact("product"));
    }
    function addToCart($id)
    {
        $cart = new Cart();
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $id;

        if ($cart->save()) {
            $cartItems = DB::table("cart")
                ->join("products", "cart.product_id", "=", "products.id")
                ->select("product_id", DB::raw("count(*) as quantity"), "products.title", "products.slug", "products.image", "products.price")
                ->where("cart.user_id", auth()->user()->id)
                ->groupBy("cart.product_id", "products.title", "products.price", "products.slug", "products.image")

                ->paginate(5);

            session()->put('cartcount', count($cartItems));

            return redirect()->back()->with("success", "Product added to the cart");
        }
        return redirect()->back()->error("success", "Something went wrong");
    }
    function showCart()
    {
        $cartItems = DB::table("cart")
            ->join("products", "cart.product_id", "=", "products.id")
            ->select("product_id", DB::raw("count(*) as quantity"), "products.title", "products.slug", "products.image", "products.price")
            ->where("cart.user_id", auth()->user()->id)
            ->groupBy("cart.product_id", "products.title", "products.price", "products.slug", "products.image")

            ->paginate(5);

        session()->put('cartcount', count($cartItems));

        return view('cart', compact("cartItems"));
    }
}
