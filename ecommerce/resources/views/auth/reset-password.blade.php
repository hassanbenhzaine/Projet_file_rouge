<x-guest-layout>


    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <main class="form-signin">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <a href="{{route('home')}}"><img class="mb-5" src="{{asset('img/logo-black.svg')}}" alt="logo" width="100" height=""></a>
          <h1 class="h3 mb-3 fw-normal">Enter your new password</h1>
          <div class="form-floating mb-2">
            <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="name@example.com" required >
            <label for="floatingEmail">Email address</label>
          </div>
          <div class="form-floating mb-2">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
          </div>
          <div class="form-floating mb-2">
            <input type="password" name="password_confirmation" class="form-control" id="floatingRepeatPassword" placeholder="Repeat password" name="password_confirmation" required>
            <label for="floatingRepeatPassword">Repeat password</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary loginbutton" type="submit">Reset Password</button>
          <p class="mt-5 mb-3 text-muted text-center">© {{date("Y")}} {{config('app.name')}}, all rights reserved.</p>
        </form>
      </main>

</x-guest-layout>
