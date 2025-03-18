@extends("layouts.default")
@section("title","Ecom-home")
@section("content")
<main class="container">
  <section>


    <div class="row">
      @if(session()->has("success"))
      <div class="alert alert-success">{{session("success")}}</div>
      @endif
      @if(session("error"))
      <div class="alert alert-danger">{{session("error")}}</div>
      @endif
      @foreach($orders as $order)
      <div class="col-12">
        <div class="card mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="{{$order->products_details[0]['image']}}" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">
                  Order : #{{$order->id}}
                </h5>
                <p class="card-text">
                  Payment ID : #{{$order->payment_id}}
                </p>
                <p class="card-text">
                  Total Price : ${{$order->total_price}}
                </p>
                <h6>Products</h6>
                <ul>
                  @foreach($order->products_details as $product)
                  <li>
                    <a href="{{route('product.details',$product['slug'])}}">
                      {{$product['name']}}</a>
                    -Quantity : {{$product['quantity']}}
                    - Price : ${{$product['price']}}
                  </li>

                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>


      @endforeach

    </div>
    <div>
      {{$orders->links()}}
    </div>

  </section>
</main>
@endsection