<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<div class="d-flex">

    @include('layouts.sidebar')

    <div class="flex-grow-1 p-3">
        @include('layouts.navbar')

        @yield('content')
    </div>

</div>

</body>
</html>
