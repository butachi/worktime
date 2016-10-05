<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ Config::get('app.name') }} - @yield('title')</title>
        <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">
        <link href="{{ asset('themes/eshopper/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/font-awesome.min.css')}}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/prettyPhoto.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/price-range.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/eshopper/css/responsive.css') }}" rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="{{ asset('themes/eshopper/js/html5shiv.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/respond.min.js') }}"></script>
        <![endif]-->         
    </head>

    <body>
        @include('partials.header')        
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        @include('partials.sidebar')
                    </div>
                    <div class="col-sm-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
        @include('partials.footer')        

        <script src="{{ asset('themes/eshopper/js/jquery.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/jquery.scrollUp.min.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/price-range.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/jquery.prettyPhoto.js') }}"></script>
        <script src="{{ asset('themes/eshopper/js/main.js') }}"></script>
        <script>
            var loc = "{{ Config::get('hwo.weather_zc') }}";
            var u = "{{ Config::get('hwo.weather_u') }}";
        </script>
        <script src="{{ asset('themes/front/js/get_weather.js') }}"></script>
        
        <!--<script src="https://query.yahooapis.com/v1/public/yql?q=select * from weather.forecast where woeid in (select woeid from geo.places(1) where text='Honolulu, HI')and u='c'&format=json&callback=callbackFunction"></script>-->
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    </body>
</html>
