<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Asme') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-1.9.1.min.js') }}"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{asset('js/custom.js')}}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}', basePath: '{{ url("/") }}/' }
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/chapters') }}">
                    <img src="{{asset('images/asmeLogo.jpg')}}">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (Auth::check())
                            <!-- <li>
                                <a class="nav-link" href="{{route('chapter-list')}}">Home</a>
                            </li> -->
                            <!-- <li>
                                <a class="nav-link" href="{{route('chapter-list')}}">Chapter's</a>
                            </li> -->
                            @if (Auth::user()->hasRole('editor'))
                                <li>
                                    <a class="nav-link" href="{{route('contributor-list')}}">Contributors</a>
                                </li>
                                <!-- <li>
                                    <a class="nav-link" href="{{route('upload-xml')}}">Upload XML</a>
                                </li> -->
                                <li>
                                    <a class="nav-link" href="{{route('download-xml')}}">Downloads</a>
                                </li>
                            @endif

                            @if (Auth::user()->hasRole('super-admin'))
                                <li>
                                    <a class="nav-link" href="{{route('upload-xml')}}">Upload XML</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{route('download-xml')}}">Downloads</a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </li> -->
                        @else


                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}<span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        {{--<div class="container">--}}
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @elseif($message = Session::get('success'))
         <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @yield('content')
        {{--</div>--}}
    </main>
</div>
<!-- tinyMce js -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/lib/rangy/rangy-core.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/polyfills.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/ice.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/dom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/icePlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/icePluginManager.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/bookmark.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/selection.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/plugins/IceAddTitlePlugin/IceAddTitlePlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/plugins/IceCopyPastePlugin/IceCopyPastePlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/plugins/IceEmdashPlugin/IceEmdashPlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/src/plugins/IceSmartQuotesPlugin/IceSmartQuotesPlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinyMce/lib/tinymce/jscripts/tiny_mce/tiny_mce.js') }}"></script>
</body>
</html>
