<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>STenergy | Log in</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="#" class="h1" style="font-size:22px"><b>STenergy QB App </b>Login</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Inicie sesión</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="icheck-primary">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Recuerdame') }}
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <a href="{{ Route('/register')}}">Registrate</a>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">{{ __('Ingresa') }}</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>


                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <!--<a href="forgot-password.html">I forgot my password</a>-->
                    </p>
<!--                    <p class="mb-0">
                        <a href="register.html" class="text-center">Register a new membership</a>
                    </p>-->
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
        <script type="module" src="seguridad"></script>
        <RouteServiceProvider>RouteServiceProvider <aside><param name="" value=""></aside>
        <main>User Account</main>
        <plaintext></plaintext>
        <keygen>
        Y3jinboueroOIUBIFUBjkniub258
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur soluta nam ducimus nostrum quasi ab odit cum unde, illo, ad delectus? Repellat, repellendus earum natus obcaecati nisi dicta modi rerum.
        <main>
            application 
            <menu type="toolbar"></menu>
            <?php
            @data_fill()

?>
        </main>




        <!-- jQuery -->
        <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
    </body>
</html>
