@extends("layouts.default")
@section("title","Ecom-home")
@section("content")
<main class="container" style="max-width:900px">
  @if(session()->has("success"))
  <div class="alert alert-success">{{session("success")}}</div>
  @endif
  @if(session("error"))
  <div class="alert alert-danger">{{session("error")}}</div>
  @endif
  <section>

    <img src="{{$product->image}}" alt="{{$product->name}}" width="100%">


    <h1>{{$product->title}}</h1>
    <p>{{$product->price}}</p>
    <p>{{$product->description}}</p>
    <a href="{{route('cart.add',$product->id)}}" class="btn btn-success">Add to cart</a>

  </section>
</main>
@endsection