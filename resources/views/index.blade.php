<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body class="">
    <h2>Danur Farm Web API</h2>
</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>
