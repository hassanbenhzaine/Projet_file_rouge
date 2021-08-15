<header class="px-3 bg-dark sticky-top">
    @if ($showProfile == true)
<div class="modal fade show" id="editProfile" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: block; background: #00000073;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" wire:submit.prevent="saveProfile">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit profile</h5>
          <button onclick="document.getElementById('editProfile').remove()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input wire:model.defer="name" type="text" class="form-control" id="name" required>
                @error('name') <div class="invalid-feedback d-block">{{$message}}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input wire:model.defer="email" type="text" class="form-control" id="email" required>
                @error('email') <div class="invalid-feedback d-block">{{$message}}</div> @enderror
            </div>
            <div class="mb-3">

                <label for="phone" class="form-label">Phone number</label>
                <input wire:model.defer="phone" type="tel" class="form-control" id="phone" required>
                @error('phone') <div class="invalid-feedback d-block">{{$message}}</div> @enderror

            </div>

              <div class="mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label for="password" class="form-label">Password</label>
                            <input wire:model.defer="password" type="password" class="form-control" id="password" >
                        </div>
                        <div class="col-6">
                            <label for="password_confirmation" class="form-label">Repeat Password</label>
                            <input wire:model.defer="password_confirmation" type="password" class="form-control" id="password_confirmation">
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{$message}}</div> @enderror
                    </div>
              </div>

        </div>
        <div class="modal-footer">
        
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>

    
@endif

    <nav class="text-white navbar navbar-expand-lg navbar-dark p-0 d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
    <div class="container">
      
        <a href="/" class="navbar-brand bg-dark shadow-none p-0">
          <img width="100" height="32" src="{{asset('img/logo.svg')}}" alt="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <form class="col-12 col-lg-6 mt-3 mt-lg-0 me-lg-auto ms-lg-4" method="GET" action="{{route('search')}}">
            <input name="q" type="search" class="form-control form-control-dark " placeholder="Search..." aria-label="Search">
        </form>

        <div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item d-block d-lg-none">
              @if (Route::has('home'))
              <a href="{{ route('home') }}" type="button" class="nav-link @if(route('home')) active @endif">Home</a>
              @endif
          </li>

          @auth
          <li class="nav-item dropdown">
            <a class="nav-link active d-block text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu text-small dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li><a id="profile" class="dropdown-item" wire:click="editProfile">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                  <form action="{{ route('logout') }}" method="POST">
                      @csrf
                  <button type="submit" class="dropdown-item" href="{{ route('logout') }}">Sign out</button>
                  </form>
              </li>
            </ul>
          </li>
          @else
          <li class="nav-item d-none d-lg-block">
              @if (Route::has('login'))
              <a href="{{ route('login') }}" type="button" class="btn btn-outline-light me-2">Login</a>
              @endif
          </li>
          <li class="nav-item d-none d-lg-block">
            @if (Route::has('register'))
            <a href="{{ route('register') }}" type="button" class="btn signupbutton">Sign up</a>
            @endif
          </li>
          <li class="nav-item d-block d-lg-none">
            @if (Route::has('login'))
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            @endif
          </li>
          <li class="nav-item d-block d-lg-none">
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="nav-link">Sign up</a>
            @endif
          </li>
          @endauth
  


          <div class="nav-item dropdown d-block d-lg-none">  
            <a class="nav-link dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
              Cart ({{Cart::content()->count()}})
            </a>         
                <ul class="dropdown-menu p-0 border-0">
                    @if (Cart::content()->count() > 0)
                        @foreach (Cart::content() as $item)
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                          <form class="d-flex flex-row" wire:submit.prevent="deleteFromCart('{{$item->rowId}}')">
                            @php
                            $component = null;

                                switch (Route::currentRouteName()) {
                                  case 'search':
                                    $component = 'search';
                                    break;
                                  
                                  case 'home':
                                    $component = 'featured-products';
                                    break;
                                }
                            @endphp
                            <div>
                                <h6 class="my-0">{{substr_replace($item->name, "...",32)}}</h6>
                                <small class="text-muted">Quantity: {{$item->qty}}</small>
                            </div>
                          </form>
                          <span class="text-muted">${{$item->qty * $item->price}}</span>
                        </li>
                        @endforeach

                        <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong>${{Cart::priceTotal()}}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-end">
                        <a class="btn btn-primary" href="{{route('cart')}}">Checkout</a>
                        </li>

                      @else

                      <li class="list-group-item d-flex justify-content-center">
                      <strong class="py-2">Cart is empty</strong>
                      </li>

                    @endif
                  </ul>
          </div>




          </ul>
            {{-- ---- --}}
            <div class="btn-group ms-3 d-none d-lg-block">            
              <div class="dropdown">
                  <img src="{{asset('img/cart.svg')}}" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="carttotal position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{Cart::content()->count()}}</div>
  
                  <ul class="mb-3 dropdown-menu dropdown-menu-end p-0 border-0 cartwidget">
                      @if (Cart::content()->count() > 0)
                          @foreach (Cart::content() as $item)
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <form class="d-flex flex-row" wire:submit.prevent="deleteFromCart('{{$item->rowId}}')">
                              @php
                              $component = null;

                                  switch (Route::currentRouteName()) {
                                    case 'search':
                                      $component = 'search';
                                      break;
                                    
                                    case 'home':
                                      $component = 'featured-products';
                                      break;
                                  }
                              @endphp
                              <div>
                                  <h6 class="my-0">{{substr_replace($item->name, "...",32)}}</h6>
                                  <small class="text-muted">Quantity: {{$item->qty}}</small>
                              </div>
                            </form>
                            <span class="text-muted">${{$item->qty * $item->price}}</span>
                          </li>
                          @endforeach
  
                          <li class="list-group-item d-flex justify-content-between">
                          <span>Total (USD)</span>
                          <strong>${{Cart::priceTotal()}}</strong>
                          </li>
  
                          <li class="list-group-item d-flex justify-content-end">
                          <a class="btn btn-primary" href="{{route('cart')}}">Checkout</a>
                          </li>
  
                        @else
  
                        <li class="list-group-item d-flex justify-content-center">
                        <strong class="py-2">Cart is empty</strong>
                        </li>
  
                      @endif
                    </ul>
              </div>
            </div>
            {{-- ---- --}}
        </div>

      </div>
      </nav>
  </header>