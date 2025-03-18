<?php

use App\Http\Controllers\AuthManager;
use App\Http\Controllers\OrderManager;
use App\Http\Controllers\ProductManager;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name("home");

Route::get("/", [ProductManager::class, "index"])->name("index");
Route::get("login", [AuthManager::class, "login"])->name("login");
Route::get("logout", [AuthManager::class, "logout"])->name("logout");
Route::post("login", [AuthManager::class, "loginPost"])->name("login.post");
Route::get("register", [AuthManager::class, "register"])->name("register");
Route::post("register", [AuthManager::class, "registerPost"])->name("register.post");
Route::get("/product/{slug}", [ProductManager::class, "details"])->name("product.details");
Route::middleware(["auth"])->group(function () {
  Route::get("/cart/{id}", [ProductManager::class, "addToCart"])->name("cart.add");
  Route::get("/cart", [ProductManager::class, "showCart"])->name("cart.show");
  Route::get("/cart/delete/{id}", [ProductManager::class, "deleteCart"])->name("cart.delete");
  Route::get("/checkout", [OrderManager::class, "showCheckout"])->name("checkout.show");
  Route::post("/orders", [OrderManager::class, "checkoutPost"])->name("checkout.post");
  Route::get("/payment/success/{order_id}", [OrderManager::class, "paymentSuccess"])->name("payment.success");
  Route::get("/payment/error/{order_id}", [OrderManager::class, "paymentError"])->name("payment.error");
  Route::get("/order/history", [OrderManager::class, "orderHistory"])->name("order.history");
});