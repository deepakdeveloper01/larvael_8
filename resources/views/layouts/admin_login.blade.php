<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LARAVEL APP</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page" style="background-image: url('{!!asset('images/background_image_3.jpg')!!}');  background-repeat:no-repeat; opacity: 1.0; background-size: cover; background-color: rgba(0,0,0, 0.4); background-position: center;
 
  background-size: cover;">
@yield('content')
<script src="{!! URL::asset('AdminLTE/plugins/jquery/jquery.min.js') !!}"></script>
<script src="{!! URL::asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
<script src="{!! URL::asset('AdminLTE/dist/js/adminlte.min.js') !!}"></script>
</body>
</html>
