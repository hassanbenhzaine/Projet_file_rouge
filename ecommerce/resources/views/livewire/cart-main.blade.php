@section('title', 'Cart')
    <div class="container flex-grow-1">
        <main>
          <div class="row g-5 py-5">
            <div class="col-md-5 col-lg-4 order-md-last">
              <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Your cart</span>
                <span class="badge bg-primary rounded-pill">{{Cart::content()->count()}}</span>
              </h4>
              <ul class="list-group mb-3">
                
                @if (Cart::content()->count() > 0)

                @foreach (Cart::content() as $item)
                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <form class="d-flex flex-row" wire:submit.prevent="deleteFromCart('{{$item->rowId}}')">
                              
                    <button type="submit" wire:click="$emitTo('navigation', '$refresh')" class="me-3 border-0 bg-white d-flex align-items-center">
                        <img width="12px" height="auto" src="{{asset('img/close.svg')}}" alt="closebutton">
                    </button>
                    <div>
                        <h6 class="my-0">{{substr_replace($item->name, "...",42)}}</h6>
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
                @else
                <li class="list-group-item d-flex justify-content-center">
                  <strong class="py-2">Cart is empty</strong>
              </li>
                @endif

              </ul>

            </div>
            <div class="col-md-7 col-lg-8">
              <form method="POST" action="{{route('cartSubmit')}}" id="payment-form" data-secret="{{$data['intent']['client_secret']}}" class="needs-validation" novalidate="">
                @csrf
                <input type="hidden" name="strip" value="{{$data['intent']['id']}}">
                <h4 class="mb-3">Contact informtion</h4>
                <div class="row g-3">
                  <div class="col-12">
                    <label for="fullName" id="fullName" class="form-label">Full name</label>
                    <input  name="fullName" type="text" class="form-control" id="fullName" placeholder="" value="@auth{{Auth::user()->name}}@endauth" required="">
                    <div class="invalid-feedback">
                      Valid full name is required.
                    </div>
                  </div>
      
                  <div class="col-sm-6">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" id="email" value="@auth{{Auth::user()->email}}@endauth" @auth disabled @endauth>
                    <div class="invalid-feedback">
                      Please enter a valid email address for shipping updates.
                    </div>
                  </div>
      
      
                  <div class="col-sm-6">
                    <label for="phone" class="form-label">Phone <span class="text-muted">(Optional)</span></label>
                    <input name="phone" type="phone" class="form-control" id="phone" placeholder="" value="@auth{{Auth::user()->phone}}@endauth">
                    <div class="invalid-feedback">
                      Please enter a valid phone number for shipping updates.
                    </div>
                  </div>
                </div>

                <h4 class="mb-3 mt-5">Shipping address</h4>
                @if(count($customerAddresses) > 0)
                <div class="row g-3">
                  <div  class="col-12">
                    <select name="selectedAddress" class="form-select" required>
                      @foreach ($customerAddresses as $address)
                        <option value="{{$address->id}}">{{$address->address1}}, {{$address->address2}} {{$address->zip_code}} - {{Countries::getOne($address->country, 'en')}}</option>
                      @endforeach
                    </select>
                    <div class="invalid-feedback">
                      Please select a shipping address.
                    </div>
                  </div>
                </div>
                @else
                <div class="row g-3">
      
                  <div class="col-12">
                    <label for="address1" class="form-label">Address</label>
                    <input name="address1" type="text" class="form-control" id="address1" placeholder="1234 Main St" required="">
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>
      
                  <div class="col-12">
                    <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input name="address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                  </div>
      
                  <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <select name="country" class="form-select" id="country" required="" >
                      @if($data['address'] != null)
                      <option value="{{$data['address']['country']}}" selected>{{Countries::getOne($data['address']['country'], 'en')}}</option>
                      @else
                      <option></option>
                      @endif

                        @foreach (Countries::getList('en') as $key=> $value)

                        <option value="{{$key}}">{{$value}}</option>

                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                      Please select a valid country.
                    </div>
                  </div>

      
                  <div class="col-md-6">
                    <label for="zip" class="form-label">Zip</label>
                    <input name="zipCode" type="text" class="form-control" id="zip" placeholder="" required="">
                    <div class="invalid-feedback">
                      Zip code required.
                    </div>
                  </div>
                </div>
                @endif

                {{-- <hr class="my-4">
      
                <div class="form-check">
                  <input name="saveInfo" type="checkbox" class="form-check-input" id="save-info" checked="yes">
                  <label class="form-check-label" for="save-info">Save this information for next time</label>
                </div> --}}
      
                <hr class="mb-4 mt-5">
      
                <h4 class="mb-3">Payment options</h4>

                <div class="my-3">
                  <div class="form-check">
                    <input id="creditOrDebit" name="paymentMethod" type="radio" class="form-check-input" checked="yes" required="">
                    <label class="form-check-label" for="creditOrDebit">Credit / Debit card</label>
                  </div>
                </div>

                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
                <hr class="my-4">
                <button id="submitButton" type="button" class="w-100 btn btn-primary btn-lg">Pay now</button>
              </form>
              
            </div>
          </div>
          <script src="https://js.stripe.com/v3/"></script>
          <script src="{{asset('js/cart.js')}}"></script>
        </main>
      
      </div>

      