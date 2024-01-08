<!doctype html>
<html>
<head>
    @include('includes.head')
    <script type="text/javascript" src="./js/plugins.js"></script>
    <script type="text/javascript" src="./js/utils.js"></script>
</head>
<body>
    <div id="page">
        <div id="tester">
        @include('includes.header')
        @yield('content')
        @include('includes.footer')
        </div>
    </div>
    <script type="text/javascript" src="./js/app.js" id="js-app"></script>
</body>
</html>
