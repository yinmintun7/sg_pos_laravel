<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form! </title>
    <!-- Bootstrap -->
    <link href="{{ URL::asset('asset/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('asset/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ URL::asset('asset/css/animate.css/animate.min.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('asset/css/custom/custom.min.css') }}" rel="stylesheet">
    <!-- Include SweetAlert CSS and JS from CDN -->
    <link rel="stylesheet" href="{{ URL::asset('asset/css/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ URL::asset('asset/js/sweetalert2/sweetalert2.min.js') }}"></script>
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action = "{{ route('sg-backendLogin') }}" method = "POST">
                        @csrf
                        <h1>Login Form</h1>
                        <div>
                            <input type="text" class="form-control" placeholder="Username" name = "username" value="{{old('username')}}" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" name = "password" />
                        </div>
                        <div>
                            <input type="checkbox" name="remember" id="remember" value="1" />
                            <label for="remember"> Remember me </label>
                            <button type="submit" class="btn btn-default submit">Log in</button>
                            <input type="hidden" name="form_sub" value="Submit" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="separator">
                            <div>
                                <p>©2024 All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>

@if ($errors->has('login_error'))
<script>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "{{ $errors->first('login_error') }}",
        footer: ''
    });
</script>
@endif

@if ($errors->has('username'))
<script>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "{{ $errors->first('username') }}",
        footer: ''
    });
</script>
@endif

@if ($errors->has('password'))
<script>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "{{ $errors->first('password') }}",
        footer: ''
    });
</script>
@endif

</html>

