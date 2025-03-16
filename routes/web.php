<?php

use App\Http\Controllers\AuthManager;
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
});
