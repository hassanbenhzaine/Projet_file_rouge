@empty($query)
@section('title', 'Search')
@else
@section('title', $query)
@endempty

<div class="container-fluid flex-grow-1">
    <div class="row h-100">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-2 text-muted">
                <span>Categories</span>
              </h6>
          <ul class="nav flex-column">

              <li class="nav-item">
                <form class="nav-link px-3 py-0" id="searchFilter" wire:submit.prevent="updateFilter('{{$query}}')">
                  @csrf
                
                    @foreach ($categories as $category)
                  <div class="form-check">
                      <input form="searchFilter" class="form-check-input" type="checkbox" wire:model.defer="selectedCategory" value="{{$category->id}}" id="flexCheckDefault{{$category->id}}">
                      <label class="form-check-label" for="flexCheckDefault{{$category->id}}">
                        {{$category->name}}
                      </label>
                    </div>
                    @endforeach
                </form>
              </li>

          </ul>
  
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-2 text-muted">
            <span>Price range</span>
          </h6>
          <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <div class="input-group mb-3">
                    <input form="searchFilter" type="number" min="0" wire:model.defer="minPrice" class="py-1 form-control"  aria-label="From">
                    <span class="py-1 input-group-text">To</span>
                    <input form="searchFilter" type="number" min="0" wire:model.defer="maxPrice" class="py-1 form-control"  aria-label="To">
                </div>
                <button type="submit" form="searchFilter" class="pricerangeButton btn btn-primary py-1 float-end">Apply</button>
            </li>
          </ul>
        </div>
      </nav>
  
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-5">

        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                  @if (count($products) > 0)
                  @foreach ($products as $product)
                  <div class="col">
                  <div class="card shadow-sm h-100">
                    <a href="{{route('product').'?id='.$product->id}}">
                      @if (json_decode($product->photos) == null)
                      <img class="w-100 pt-3 px-3" src="{{asset('storage/products').'/nophoto.png'}}" alt="{{$product->title}}">
                      @else
                      <img class="w-100 pt-3 px-3" src="{{asset('storage/products').'/'.json_decode($product->photos)[0]}}" alt="{{$product->title}}">
                      @endif     
                    </a>
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                      <p class="card-text productitle mb-4"><a href="{{route('product').'?id='.$product->id}}">{{$product->title}}</a></p>
                      <div class="row d-flex align-items-center w-100">
                          <strong class="text-dark fs-6 col-12 col-lg-4 p-0 text-lg-start text-end">${{$product->price}}</strong>
                          <div class="btn-group col-12 col-lg-8 p-0 mt-3 mt-lg-0">
                          @if (Cart::content()->where('id', $product->id)->count())
                          @php
                              $product = Cart::content()->where('id', $product->id);
                              foreach ($product as $sameProduct) {
                                  $rowId =  $sameProduct->rowId;
                              }
                          @endphp
                          <button wire:click="removeFromCart('{{$rowId}}')" type="submit" wire:click="$emitTo('navigation', '$refresh')" class="py-1 btn btn-sm btn-outline-danger">Remove from Cart</button>
                          @else
                          <input class="qtyinput form-control py-1" wire:model.defer="qty" type="number" min="1" placeholder="1">
                          <button wire:click="addToCart({{$product->id}})" type="submit" wire:click="$emitTo('navigation', '$refresh')" class="py-1 btn btn-sm btn-outline-secondary">Add to cart</button>
                          @endif
                          </div>
                      </div>
                      </div>
                  </div>
                  </div>
                  @endforeach
                  @else
                  <div class="text-center w-100 h1">No results found!</div>
                  @endif

                  @if ($products->hasPages())
                    <div class="col-12 d-flex justify-content-center">
                      {{$products->links('simple-pagination')}}
                  </div>
                  @endif

            </div>
        </div>

      </main>
    </div>
  </div>