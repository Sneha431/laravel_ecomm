@extends("layouts.default")
@section("title","checkout")
@section("content")
<main class="container" style="max-width: 900px;">

  <section>

    <div class="col-md-6">
      <h3>Shipping Address</h3>
      @if(session()->has("success"))
      <div class="alert alert-success">{{session("success")}}</div>
      @endif
      @if(session("error"))
      <div class="alert alert-danger">{{session("error")}}</div>
      @endif
      <form action="{{route('checkout.post')}}" method="post">
        @csrf
        <div class="form-group mb-3">
          <label class="form-label" for="address">Address</label>
          <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="form-group mb-3">

          <label class="form-label" for="phone">Phone Number</label>
          <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="form-group mb-3">

          <label class="form-label" for="pincode">Pincode</label>
          <input type="text" name="pincode" id="pincode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
      </form>
    </div>
  </section>
</main>
@endsection