<div class="container text-center">
    <div class="row ">
        @foreach ($categories as $category)
        <a class="col-6 col-lg-2 col-md-3 col-sm-4" href="{{route('category', $category->name)}}">
            <img class="shadow-sm border-1 rounded-circle categoryimg" src="{{asset('img/categories'.'/'.$category->photo.'.webp')}}" alt="">
            <div class="m-3 h6">{{$category->name}}</div>
        </a>
        @endforeach


    </div>
</div>