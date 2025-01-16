<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.faculty-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dashboard</title>
    <style>
        .icon {
            font-size: 30px;
            color: #800000;
        }
    </style>
</head>

<body>
    @include('partials.faculty-sidebar')
</body>

</html>
