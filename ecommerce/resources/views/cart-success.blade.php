@section('title', 'Order complete #'.$orderInfo['number'])
<x-app-layout>

    <div class="container-fluid flex-grow-1">
        <div class="row h-50 d-flex justify-content-center align-items-end">
            <div class="col-3 col-md-2 col-lg-2">
                <img src="http://localhost:8000/img/order-complete.svg" alt="order confirm" class="w-100">
            </div>
        </div>
        <div class="row h-50 mt-3">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <h3 class="text-dark">Your order is complete, number <span class="text-primary">#{{$orderInfo['number']}}</span></h3>
            </div>
            <div class="mt-2 col-12 d-flex justify-content-center align-items-start">
                <a class="btn btn-success" href="{{route('dashboard', ['view' => 'orders'])}}">Continue to order</a>
            </div>
        </div>
    </div>

</x-app-layout>
