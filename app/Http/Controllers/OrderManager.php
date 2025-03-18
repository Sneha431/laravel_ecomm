<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderManager extends Controller
{
    //
    function showCheckout()
    {
        return view("checkout");
    }
    function checkoutPost(Request $request)
    {
        $request->validate([
            "address" => "required",
            "pincode" => "required",
            "phone" => "required",
        ]);
        $cartItems = DB::table("cart")
            ->join("products", "cart.product_id", "=", "products.id")
            ->select("product_id", DB::raw("count(*) as quantity"), "products.title", "products.slug", "products.image", "products.price")
            ->where("cart.user_id", auth()->user()->id)
            ->groupBy("cart.product_id", "products.price", "products.title")

            ->get();
        if ($cartItems->isEmpty()) {
            return redirect(route("cart.show"))->with("error", "Cart is empty");
        }

        $productids = [];
        $quantities = [];
        $totalPrice = 0;
        $line_items = [];
        foreach ($cartItems as $item) {
            $productids[] = $item->product_id;
            $quantities[] = $item->quantity;
            $totalPrice += $item->price * $item->quantity;
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->title

                    ],
                    'unit_amount' => $item->price * 100
                ],
                'quantity' => $item->quantity
            ];
        }
        $order = new Orders();

        $order->user_id = auth()->user()->id;
        $order->address = $request->address;
        $order->pincode = $request->pincode;
        $order->phone = $request->phone;
        $order->product_id = json_encode($productids);
        $order->quantity = json_encode($quantities);
        $order->total_price = $totalPrice;



        if ($order->save()) {
            DB::table("cart")->where("user_id", auth()->user()->id)->delete();
            $stripe = new \Stripe\StripeClient(
                config("app.APP_STRIPE_SECRET_KEY")
            );
            $checkout_session = $stripe->checkout->sessions->create([
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'order_id' => $order->id
                ],
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['order_id' => $order->id]) . '?success=true',
                'cancel_url' => route('payment.error', ['order_id' => $order->id]) . '?success=false',
            ]);
            return redirect($checkout_session->url);
        }
        return redirect(route("cart.show"))->with("error", "Something went wrong");
    }
    function paymentSuccess($order_id)
    {
        // $order = Orders::find($request->order_id);
        // $order->payment_id = $request->payment_id;
        // $order->status = "completed";
        // $order->save();
        // return redirect(route("index"))->with("success", "Order placed successfully");
        return "Success" . $order_id;
    }
    function paymentError($order_id)
    {
        // return redirect(route("index"))->with("error", "Payment failed");
        return "Error" . $order_id;
    }
    function webhookstripe(Request $request)
    {
        $endpoint_secret = config("app.APP_STRIPE_WEBHOOK_SECRET");
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        // Handle the event 
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;
            $paymentId = $session->payment_intent;
            $order = Orders::find($orderId);
            if ($order) {
                $order->payment_id = $paymentId;
                $order->status = "payment_completed";
                $order->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
    function orderHistory()
    {
        // $orders = Orders::where("user_id", auth()->user()->id)->get();

        // $orders = $orders->map(function ($order) {
        //     $productIds = json_decode($order->product_id, true);
        //     $quantities = json_decode($order->quantity, true);
        //     $products = Products::whereIn("id", $productIds)->get();
        //     $order->products_details = $products->map(function ($product)
        //     use ($productIds, $quantities) {
        //         $index = array_search($product->id, $productIds);
        //         return [
        //             'name' => $product->title,
        //             'quantity' => $quantities[$index] ?? 0,
        //             'price' => $product->price
        //         ];
        //     });
        //     return $order;
        // });
        // return $orders;

        $orders = Orders::where("user_id", auth()->user()->id)->orderBy('id', 'desc')->paginate(5);

        $orders->getCollection()->map(function ($order) {
            $productIds = json_decode($order->product_id, true);
            $quantities = json_decode($order->quantity, true);
            $products = Products::whereIn("id", $productIds)->get();
            $order->products_details = $products->map(function ($product)
            use ($productIds, $quantities) {
                $index = array_search($product->id, $productIds);
                return [
                    'name' => $product->title,
                    'quantity' => $quantities[$index] ?? 0,
                    'price' => $product->price,
                    'slug' => $product->slug,
                    'image' => $product->image
                ];
            });
            return $order;
        });
        return view("history", compact("orders"));
        // return $orders;
    }
}