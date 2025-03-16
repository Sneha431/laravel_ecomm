@extends("layouts.default")
@section("title","Ecom-home")
@section("content")
<main class="container">
  <section>


    <div class="row">
      @foreach($cartItems as $cartItem)
      <div class="col-12">
        <div class="card mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="{{$cartItem->image}}" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title"><a href="{{route('product.details',$cartItem->slug)}}">{{$cartItem->title}}</a>
                </h5>
                <p class="card-text">Price : ${{$cartItem->price}} </p>
              </div>
            </div>
          </div>
        </div>

      </div>


      @endforeach
    </div>

  </section>
</main>
@endsection