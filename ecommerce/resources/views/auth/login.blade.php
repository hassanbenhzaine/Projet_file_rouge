<x-guest-layout>
  @section('title', 'Login')
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />
          
        <main class="form-signin">
            <form method="POST" action="{{ route('login') }}">
               @csrf
               <a href="{{route('home')}}"><img class="mb-5" src="{{asset('img/logo-black.svg')}}" alt="logo" width="100" height=""></a>
              <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
          
              <div class="form-floating mb-2">
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required autofocus>
                <label for="floatingInput">Email address</label>
              </div>
              <div class="form-floating mb-2">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required autocomplete="current-password">
                <label for="floatingPassword">Password</label>
              </div>
          
              <div class="checkbox mb-5 d-flex justify-content-between">
                <label>
                  <input type="checkbox" value="remember"> Remember me
                </label>

                @if (Route::has('password.request'))
                <label>
                    <a href="{{ route('password.request') }}">Forget password?</a>
                </label>
                @endif

              </div>
              <button class="w-100 btn btn-lg btn-primary loginbutton" type="submit">Sign in</button>
              <p class="mt-5 mb-3 text-muted text-center">© {{date("Y")}} {{config('app.name')}}, all rights reserved.</p>
            </form>
          </main>

</x-guest-layout>
