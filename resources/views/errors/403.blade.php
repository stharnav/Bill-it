<!DOCTYPE html>
<html>
<head>
    <title>Unauthorized</title>
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
        <div class="h1">403</div>
            <h2>Unauthorized</h2>
            <p>You are not authorized to access this page.</p>
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