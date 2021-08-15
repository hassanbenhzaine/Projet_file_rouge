<x-app-layout>
    @section('title', $category)
    <main>
       
    <div class="album py-5 bg-light">
    
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center fs-1 fw-light">Featured {{$category}}</div>
            </div>
        </div>
    
        <livewire:products-by-category :categoryName="$category"/>
    
        <div class="container">
            <div class="row my-5">
                <div class="col text-center fs-1 fw-light">Explore other categories</div>
            </div>
        </div>
    
        <x-popular-categories/>
    
    </div>
    
    </main>
    
    </x-app-layout>
    