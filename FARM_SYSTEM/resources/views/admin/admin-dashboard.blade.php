<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <style>
        .icon {
            font-size: 30px;
            color: #800000;
        }

        .small-chart {
            width: 50vw;
            height: 50vw;
            max-width: 400px;
            max-height: 400px;
            display: block;
        }
    </style>
</head>

<body>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <style>
        .icon {
            font-size: 30px;
            color: #800000;
        }

        .small-chart {
            width: 50vw;
            height: 50vw;
            max-width: 400px;
            max-height: 400px;
            display: block;
        }
    </style>
</head>

<body>
	@include('partials.admin-sidebar')	
</body>
</html>