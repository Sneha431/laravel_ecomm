<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('index')}}">Ecommerce App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
      aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('index')}}">Home</a>
        </li>

        <!--The auth Blade directive checks if the user is authenticated
           (i.e., logged in).
If the user is authenticated, the content inside the auth block will be rendered.
 If not, nothing inside the block will be displayed.-->
        @auth<?php
              $cart = session()->get('cartcount', 0);

              ?>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('cart.show') }}">
            Cart @if($cart > 0) <span class="badge bg-primary"
              style="border-radius: 50%;padding:4px 7px;">{{$cart}}</span>
            @endif
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{route('order.history')}}">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}">Logout</a>
        </li>
        @endauth

      </ul>

    </div>
  </div>
</nav>