
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Smart Construction And Interiors</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/notify.css') }}">


    @stack('style')
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('images\logo\logo-2.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images\logo\logo-2.png') }}">
</head>

<body class="body">
    
    <!-- Preloader -->
    <!-- <div class="preloader">
      <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div> -->

    
    @yield('content')

    <!-- Javascript -->
     <!--  Import Js Files -->
     <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
     <script src="{{ asset('libs/simplebar/dist/simplebar.min.js') }}"></script>
     <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!--  core files -->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/app.init.js') }}"></script>
    <script src="{{ asset('js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/notify.js') }}"></script>
    @stack('script')

    


     <!-- Display success message -->
    @if (session('message'))
        <script>
            new Notify({
                status: "success",
                title: "{{ session('message') }}",
                text: "",
                autoclose: false,
                effect: "slide",
                speed: 300,
                position: "right bottom"
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            new Notify({
                status: "success",
                title: "{{ session('message') }}",
                text: "",
                autoclose: false,
                effect: "slide",
                speed: 300,
                position: "right bottom"
            });
        </script>
    @endif

    <!-- Display error message -->
    @if (session('error'))
        <script>
            new Notify({
                status: "error",
                title: "{{ session('error') }}",
                text: "",
                autoclose: false,
                effect: "slide",
                speed: 300,
                position: "right bottom"
            });
        </script>
    @endif
</body>
</html>