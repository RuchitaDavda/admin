<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png')  }}" type="image/x-icon">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}">
    <link rel="stylesheet" href=" {{ url('assets/css/pages/auth.css') }}">

</head>


<body>
    <div id="auth">

        <div class="row justify-content-center align-items-center login-box">
            <div class="col-lg-4 col-12 card">
                <div id="auth-center">
                   
                    <div class="auth-logo justify-content-center align-items-center d-flex">
                        <a href="{{ url('') }}"><img src="{{ url('assets/images/logo/logo.png')  }}" alt="Logo"></a>
                    </div>

                    <div class="pt-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-4">

                                <input id="email" type="email" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input id="password" type="password" placeholder="Password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                            </div>
                            {{-- <div class="form-check form-check-lg d-flex align-items-end">



                                <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label text-gray-600" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div> --}}

                           
                            <button class="btn btn-primary btn-block btn-sm shadow-lg mt-5">Log in</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
