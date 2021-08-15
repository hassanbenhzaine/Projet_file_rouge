<x-guest-layout>
  @section('title', 'Register')
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <main class="form-signin">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <a href="{{route('home')}}"><img class="mb-5" src="{{asset('img/logo-black.svg')}}" alt="logo" width="100" height=""></a>
              <h1 class="h3 mb-3 fw-normal">Let's get started</h1>
              <div class="form-floating mb-2">
                <input type="text" name="name" class="form-control" id="floatingName" placeholder="Name" required autofocus>
                <label for="floatingName">Name</label>
              </div>
              <div class="form-floating mb-2">
                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="name@example.com" required >
                <label for="floatingEmail">Email address</label>
              </div>
              <div class="form-floating mb-2">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required autocomplete="new-password">
                <label for="floatingPassword">Password</label>
              </div>
              <div class="form-floating mb-2">
                <input type="password" name="password_confirmation" class="form-control" id="floatingRepeatPassword" placeholder="Repeat password" name="password_confirmation" required>
                <label for="floatingRepeatPassword">Repeat password</label>
              </div>
              <div class="checkbox mb-5 d-flex justify-content-end">
                <label>
                    <a href="{{ route('login') }}">Already registered?</a>
                  </label>
              </div>
              <button class="w-100 btn btn-lg btn-primary loginbutton" type="submit">Sign up</button>
              <p class="mt-5 mb-3 text-muted text-center">© {{date("Y")}} {{config('app.name')}}, all rights reserved.</p>
            </form>
          </main>
        
</x-guest-layout>
