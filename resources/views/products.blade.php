@extends("layouts.default")
@section("title","Ecom-home")
@section("content")
<main class="container">
  <section>


    <div class="row">
      @foreach($products as $product)
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-2 shadow-sm">
          <img src="{{$product->image}}" width="100%" />
          <div>
            <a href="{{route('product.details',$product->slug)}}">{{$product->title}}</a> |
            <span>${{$product->price}}</span>
            <span>{{$product->slug}}</span>
            <!-- <span>{{$product->description}}</span> -->
          </div>


        </div>
      </div>


      @endforeach
    </div>
    {{$products->links()}}
  </section>
</main>
@endsection