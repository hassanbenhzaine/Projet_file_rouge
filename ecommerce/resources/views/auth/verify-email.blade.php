<x-guest-layout>
    @section('title', 'Verify email')
    <main class="form-signin">
        <form id="verification" method="POST" action="{{ route('verification.send') }}">
           @csrf
           <a href="{{route('home')}}"><img class="mb-5" src="{{asset('img/logo-black.svg')}}" alt="logo" width="100" height=""></a>
          <h1 class="h5 mb-5 fw-normal">Verify your account by clicking on the link we just emailed to you.</h1>
          <button form="verification" class="w-100 btn btn-lg btn-primary loginbutton" type="submit">Resend Verification Email</button>
          <p class="mt-5 mb-3 text-muted text-center">&copy; 2021 KORAS, All rights reserved.</p>
        </form>
        <div class="checkbox mt-5 d-flex justify-content-end">
            <form id="logout" method="POST" action="{{ route('logout') }}">
                @csrf
                <button form="logout" type="submit" class="p-0 btn btn-link" style="box-shadow:initial">
                    Log out
                </button>
            </form>
          </div>
      </main>

        @if (session('status') == 'verification-link-sent')
            <div class="modal" id="error" tabindex="-1" style="display: block; background: #0000007a;">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Verification link sent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="document.getElementById('error').style.display = 'none'"></button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            A new verification link has been sent to the email address you provided during registration.
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        @endif


</x-guest-layout>
