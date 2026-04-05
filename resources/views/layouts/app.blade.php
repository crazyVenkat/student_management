<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- Font-awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 10px;
}

.sidebar-menu li a i {
    width: 20px;
    margin-right: 10px;
}
</style>
</head>

<body>

<div class="d-flex">

    @include('layouts.sidebar')

    <div class="flex-grow-1 p-3">
        @yield('content')
    </div>

</div>
{{-- Scipts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@yield('scripts')
</body>
</html>
