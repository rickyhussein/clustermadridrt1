@extends('layouts.login')
@section('container')
    <style>
        .row {
            display: flex;
            align-items: stretch;
        }

        .separator {
            width: 1px;
            background-color: #ccc;
            margin: 0 10px;
        }
    </style>
    <form class="tf-form" action="{{ url('/login-proses') }}" method="POST">
        @csrf
        <h1>{{ $title }}</h1>
        <div class="group-input">
            <label>Email</label>
            <input type="email" placeholder="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" name="email">
            @error('email')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
        </div>
        <div class="group-input auth-pass-input last">
            <label>Password</label>
            <input type="password" class="password-input @error('password') is-invalid @enderror" placeholder="Password" name="password">
            <a class="icon-eye password-addon" id="password-addon"></a>
            @error('password')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
        </div>

        <div class="group-input mt-4">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="mt-2">
                    Remember Me
                </label>
            </div>
        </div>

        <button type="submit" class="tf-btn accent large">Log In</button>
        <div class="mt-2 text-right"> <a href="{{ url('/forgot-password') }}">Forgot Password <i class="fa fa-key"></i></a></div>
    </form>
    
    <button id="btnInstallPWA" style="color: rgb(255, 135, 36); border:1px solid rgb(255, 135, 36);" class="tf-btn large"><i class="fa fa-download mr-1"></i> Install App</button>

    @push('script')
        <script>
            const btnInstall = document.getElementById('btnInstallPWA');
            let deferredPrompt = null;

            function isPWAInstalled() {
                return window.matchMedia('(display-mode: standalone)').matches
                    || window.navigator.standalone === true;
            }

            if (isPWAInstalled()) {
                btnInstall.style.display = 'none';
            }

            window.addEventListener('beforeinstallprompt', (e) => {
                if (isPWAInstalled()) return;

                e.preventDefault();
                deferredPrompt = e;

                btnInstall.style.display = 'block';
            });

            btnInstall.addEventListener('click', async () => {
                if (!deferredPrompt) return;

                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    btnInstall.style.display = 'none';
                }

                deferredPrompt = null;
            });
        </script>
    @endpush
@endsection
