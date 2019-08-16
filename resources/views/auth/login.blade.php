@include('partials.head')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-4">
        <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
            <div class="container px-4">
                <a class="navbar-brand" href="../index.html">
                <img src="{{ asset('assetsAdminTemplate/img/brand/white.png')}}" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="../index.html">
                        <img src="{{ asset('assetsAdminTemplate/img/brand/blue.png')}}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                        <span></span>
                        <span></span>
                        </button>
                    </div>
                    </div>
                </div>
                <!-- Navbar items -->
                <!-- <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="../index.html">
                        <i class="ni ni-planet"></i>
                        <span class="nav-link-inner--text">Dashboard</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="../examples/register.html">
                        <i class="ni ni-circle-08"></i>
                        <span class="nav-link-inner--text">Register</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="../examples/login.html">
                        <i class="ni ni-key-25"></i>
                        <span class="nav-link-inner--text">Login</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="../examples/profile.html">
                        <i class="ni ni-single-02"></i>
                        <span class="nav-link-inner--text">Profile</span>
                    </a>
                    </li>
                </ul> -->
                </div>
            </div>
        </nav>
    </div>
    
    <div class="container-fluid mt--7">
    
        <div class="row mt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('Login') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>

                                            <!-- @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    @include('partials.footer')
  </div>
  
  <!--   Core   -->
  <script src="{{ asset('assetsAdminTemplateAdminTemplate/js/plugins/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplateAdminTemplate/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <!--   Optional JS   -->
  <script src="{{ asset('assetsAdminTemplateAdminTemplate/js/plugins/chart.js/dist/Chart.min.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplateAdminTemplate/js/plugins/chart.js/dist/Chart.extension.js')}}"></script>
  <!--   Argon JS   -->
  <script src="{{ asset('assetsAdminTemplateAdminTemplate/js/argon-dashboard.min.js?v=1.1.0')}}"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
  @yield('script')
</body>

</html>
