<!doctype html>
<html>
<head>
    @include('includes.head')
    <script type="text/javascript" src="./js/plugins.js"></script>
    <script type="text/javascript" src="./js/utils.js"></script>
</head>
<body>
    @include('includes.admin_header')
    @yield('content')
    @include('includes.admin_footer')
    <script type="text/javascript" src="./js/app.js" id="js-app"></script>
</body>
</html>
