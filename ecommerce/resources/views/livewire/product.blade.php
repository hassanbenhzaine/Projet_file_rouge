@section('title', $product->title)
<div class="container">
    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card h-100  shadow-sm">

                <div id="carouselExampleIndicators" class="card-body carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                      @for ($i = 0; $i < count(json_decode($product->photos)); $i++)
                      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" class="active" aria-current="true" aria-label="Slide {{$i + 1}}"></button>
                      @endfor
                    </div>
                    <div class="carousel-inner">
                      @if (json_decode($product->photos) == null)
                      <img src="{{asset('storage/products').'/nophoto.png'}}" class="d-block w-100 rounded" alt="{{$product->title}}">
                      @else
                        @foreach (json_decode($product->photos) as $key => $photo)
                        <div class="carousel-item @if($key == 0) active @endif">
                          <img src="{{asset('storage/products').'/'.$photo}}" class="d-block w-100 rounded" alt="{{$product->title}}">  
                        </div> 
                        @endforeach
                      @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
            </div>
           
        </div>

        <div class="col-12 col-lg-7">
            <div class="card h-100  shadow-sm">

                <div class="card-body p-4 d-flex flex-column justify-content-between">
<h3 class="category text-uppercase fw-light"><a href="{{route('category', $product->name)}}">{{$product->name}}</a></h1>
<h1 class="h4 card-title mb-3">{{$product->title}}</h1>

                        <div class="mt-3">
                            Condition: <strong class="ms-1 text-capitalize">{{$product->condition}}</strong>
                        </div>

                            <div>Quantity: <div class="btn-group ms-2">
                                <input class="qtyinput form-control py-1" wire:model.defer="qty" type="number" min="1" placeholder="1">
                                </div>
                            </div>

                            <div class="btn-group mt-3">
                                <button wire:click="addToCart(3)" type="submit" class="py-1 btn btn-sm btn-outline-secondary">Add to cart</button>
                            </div>

                </div>

            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card h-100  shadow-sm">
                <div class="card-body p-4">
                  {!!$product->description!!}
                </div>
            </div>
           
        </div>
    </div>
</div>