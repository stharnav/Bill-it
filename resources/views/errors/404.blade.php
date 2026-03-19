<!DOCTYPE html>
<html>
<head>
    <title>Page Not Found</title>
    <style>
        body{
            font-family: Arial;
            text-align:center;
            margin-top:100px;
        }
    </style>
    @include('layouts.header')
</head>
<body>
<div class="container" style="height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container d-flex flex-column align-items-center justify-content-center">
            <h1>404</h1>
            <h2>Page Not Found</h2>
            <p>The page you are looking for does not exist.</p>
            <div class="btn btn-primary" onclick="window.location.href='{{ url('/') }}'">Go Home</div>
    </div>
</div>
</body>
<script>
    window.onload = function() {
        setTimeout(function() {
            window.location.href = '{{ url('/') }}';
        }, 2000);
    };
</script>
</html>