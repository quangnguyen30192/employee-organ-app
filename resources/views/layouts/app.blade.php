<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>Employee Hierarchy App</title>

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    @yield('style')

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="{{route('index')}}">
                        Employee Organizer
                    </a>
                </li>
                <li>
                    <a href="{{route('index')}}">Upload Json File</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>




<!-- jQuery -->
<script src="{{asset('js/app.js')}}"></script>
<script>
        $("#wrapper").toggleClass("toggled");
</script>

@yield('script')

</body>

</html>
