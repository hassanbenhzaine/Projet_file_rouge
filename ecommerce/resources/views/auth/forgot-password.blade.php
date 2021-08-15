<x-guest-layout>
        @section('title', 'Forgot password')
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <main class="form-signin">
            <form method="POST" action="{{ route('password.email') }}">
               @csrf
               <a href="{{route('home')}}"><img class="mb-5" src="{{asset('img/logo-black.svg')}}" alt="logo" width="100" height=""></a>
              <h1 class="h3 mb-3 fw-normal">Reset your password</h1>
          
              <div class="form-floating mb-2">
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required autofocus>
                <label for="floatingInput">Email address</label>
              </div>
          
              <div class="checkbox mb-5 d-flex justify-content-end">
                <label>
                    <a href="{{ route('login') }}">Back to sign in</a>
                </label>
              </div>
              
              <button class="w-100 btn btn-lg btn-primary loginbutton" type="submit">Email Password Reset Link</button>
              <p class="mt-5 mb-3 text-muted text-center">© {{date("Y")}} {{config('app.name')}}, all rights reserved.</p>
            </form>
          </main>


</x-guest-layout>
