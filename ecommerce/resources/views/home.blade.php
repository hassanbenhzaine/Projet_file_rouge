<x-app-layout>
@section('title', 'Home')
<main class="flex-grow-1">
    <x-homecarousel/>
<div class="py-5 bg-light">

    <div class="container">
        <div class="row mb-5">
            <div class="col text-center fs-1 fw-light">Featured products</div>
        </div>
    </div>

    <livewire:featured-products/>

    <div class="container">
        <div class="row my-5">
            <div class="col text-center fs-1 fw-light">Popular categories</div>
        </div>
    </div>

    <x-popular-categories/>

</div>

</main>

</x-app-layout>
