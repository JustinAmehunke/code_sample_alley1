
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Login | STAK V2.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Old Mutual Insurance" name="description" />
        <meta content="ShrinQ" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{url('/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body class="auth-body-bg">
        <div class="bg-overlay"></div>
        <div class="wrapper-page">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-body">

                        <div class="text-center mt-4">
                            <div class="mb-3">
                                <a href="/" class="auth-logo">
                                    <img src="assets/images/logo_small_1.png" height="30" class="logo-dark mx-auto" alt="">
                                    <img src="assets/images/logo_small_1.png" height="30" class="logo-light mx-auto" alt="">
                                </a>
                            </div>
                        </div>
                            @if(session()->has('user_2fa'))
                                <h4 class="text-muted text-center font-size-18"><b>Help us protect your account</b></h4>
                            @else
                                <h4 class="text-muted text-center font-size-18"><b>Sign In</b></h4>
                            @endif
                        
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{-- @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif --}}
                        <div class="p-3">
                            <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
                                @csrf
                                @if(session()->has('user_2fa'))
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <p>For added security, you'll need to verify your identity.</p>
                                            <p>We've sent a verification code to <b> {{ is_numeric(session('user_2fa')) ? obfuscatePhoneNumber(session('user_2fa')) : obfuscateEmail(session('user_2fa'))}} </b></p>
                                        </div>
                                    </div>
                                @endif

                                @if (session()->has('user_2fa'))
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            {{-- <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password"> --}}
                                            <input class="form-control" id="password_auth" minlength="6" maxlength="6" type="number" name="password" placeholder="Verification code">
                                            @error('email')
                                                <span class="text-danger">Please enter a valid code</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <input class="form-control" id="email" type="hidden" name="email" value="{{session('user_2fa')}}" required>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <input class="form-control" id="email" type="text" name="email" value="{{old('email')}}" required autofocus autocomplete="username" placeholder="Enter Email Address/Mobile No.">
                                            @error('email')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            {{-- <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password"> --}}
                                            <input class="form-control" id="password" type="hidden" name="password" value="current-password" placeholder="Password">
                                            @error('password')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember" >
                                                <label class="form-label ms-1" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                   
                                <div class="form-group mb-3 text-center row mt-3 pt-1">
                                    <div class="col-12">
                                        <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group mb-0 row mt-2">
                                @if(session()->has('user_2fa'))
                                    <div class="col-sm-7 mt-3">
                                        <form method="POST" id="logout" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="text-muted" href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">
                                                <i class="mdi mdi-account-circle"></i>Change Email/Phone Number
                                            </a>
                                        </form>
                                    </div>
                               
                                    <div class="col-sm-5 mt-3 text-center">
                                        <a class="text-muted" href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();"><i class="mdi mdi-lock"></i>Resent code</a>
                                    </div>
                                @endif
                                
                            </div>
                        
                        </div>
                        <!-- end -->
                    </div>
                    <!-- end cardbody -->
                </div>
                <!-- end card -->
            </div>
            <!-- end container -->
        </div>
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('assets/js/app.js')}}"></script>
        <script>
            document.getElementById("password_auth").addEventListener("input", function() {
                var input = this;
                var inputValue = input.value;

                // If input length exceeds 6, trim it to 6 characters
                if (inputValue.length > 6) {
                    input.value = inputValue.slice(0, 6);
                }
            });
        </script>

    </body>
</html>

