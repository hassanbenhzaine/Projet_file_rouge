<div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        @foreach ($productsByCategory as $product)
        <div class="col">
        <div class="card shadow-sm h-100">
            <a href="{{route('product').'?id='.$product->productId}}">
                @if (json_decode($product->productPhotos) == null)
                <img class="w-100 pt-3 px-3" src="{{asset('storage/products').'/nophoto.png'}}" alt="{{$product->title}}">
                @else
                <img class="w-100 pt-3 px-3" src="{{asset('storage/products').'/'.json_decode($product->productPhotos)[0]}}" alt="{{$product->title}}">
                @endif     
            </a>
            <div class="card-body d-flex flex-column justify-content-between align-items-center">
                <p class="card-text productitle mb-4"><a href="{{route('product').'?id='.$product->productId}}">{{$product->title}}</a></p>
                <div class="row d-flex align-items-center w-100">
                    <strong class="text-dark fs-6 col-12 col-lg-4 p-0 text-lg-start text-end">${{$product->price}}</strong>
                    <div class="btn-group col-12 col-lg-8 p-0 mt-3 mt-lg-0">
                    @if (Cart::content()->where('id', $product->productId)->count())
                    @php
                        $products = Cart::content()->where('id', $product->productId);
                        foreach ($products as $product) {
                            $rowId =  $product->rowId;
                        }
                    @endphp
                    <button wire:click="removeFromCart('{{$rowId}}')" type="submit" wire:click="$emitTo('navigation', '$refresh')" class="py-1 btn btn-sm btn-outline-danger">Remove from Cart</button>
                    @else
                    <input class="qtyinput form-control py-1" wire:model.defer="qty" type="number" min="1" placeholder="1">
                    <button wire:click="addToCart({{$product->productId}})" type="submit" wire:click="$emitTo('navigation', '$refresh')" class="py-1 btn btn-sm btn-outline-secondary">Add to cart</button>
                    @endif
                    </div>
                </div>
                </div>
        </div>
        </div>
        @endforeach

        @if ($showButton == true)
        <div class="col-12 d-flex justify-content-center">
            <a wire:click="load" class="btn btn-primary my-2">Show more</a>
        </div>
        @endif

    </div>
    </div>