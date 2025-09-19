<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Zarbooks | @yield('title')</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('media/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('media/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('media/favicons/favicon-16x16.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('media/favicons/favicon.ico')}}">
    <!-- <link rel="manifest" href="{{asset('media/favicons/manifest.json')}}"> -->
    <meta name="msapplication-TileImage" content="{{asset('media/favicons/mstile-150x150.png')}}">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:100,200,300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{asset('lib/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('lib/prism/prism.css')}}" rel="stylesheet">
    <link href="{{asset('lib/fontawesome6/css/all.min.css')}}" rel="stylesheet">

    @stack('head')

    <link href="{{asset('css/theme.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/customStyle.css')}}" rel="stylesheet">
    @notifyCss
</head>

<body>
<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <div class="container" data-layout="container">
        <div class="content">
            <div class="row flex-center min-vh-100 py-6">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="d-flex flex-center mb-4">
                        <img class="mr-2" src="{{asset('media/illustrations/ZS.png')}}" alt="" width="40">
                        <span class="text-green-600 font-weight-extra-bold fs-5 d-inline-block ">ZARBOOKS</span>
                    </div>
                    <div class="card">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card-body p-4 p-sm-5">
                            <form action="{{ route('login.auth') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control" name="email" type="email" placeholder="Email address" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group pt-3">
                                    <input class="form-control" name="password" type="password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group pt-3">
                                    <label for="ddParentAccounts">Select Fiscal Year</label>
                                    <select class="form-control selectpicker fs--1" id="ddParentAccounts" name="fiscal_year">
                                        @forelse($fiscalYears as $fiscalYear)
                                            <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->fy_title }}</option>
                                        @empty
                                            <option value="">No Record</option>
                                        @endforelse
                                    </select>
{{--                                    @if ($errors->has('password'))--}}
{{--                                        <span class="text-danger">{{ $errors->first('password') }}</span>--}}
{{--                                    @endif--}}
                                </div>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" name="remember_me" type="checkbox" id="basic-checkbox" checked="checked">
                                            <label class="custom-control-label" for="basic-checkbox">Remember me</label></div>
                                    </div>
                                    <div class="col-auto">
                                        <a class="fs--1" href="../../authentication/basic/forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="form-group pt-3">
                                    <button class="btn btn-primary btn-block mt-3" type="submit" name="submit">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

<script>
    var isFluid = JSON.parse(localStorage.getItem('isFluid'));
    if (isFluid) {
        var container = document.querySelector('[data-layout]');
        container.classList.remove('container');
        container.classList.add('container-fluid');
    }
</script>

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/config.navbar-vertical.min.js')}}"></script>
{{--<script src="{{asset('lib/%40fortawesome/all.min.js')}}"></script>--}}
<script src="{{asset('lib/fontawesome6/js/all.min.js')}}"></script>
<script src="{{asset('lib/stickyfilljs/stickyfill.min.js')}}"></script>
<script src="{{asset('lib/sticky-kit/sticky-kit.min.js')}}"></script>
<script src="{{asset('lib/is_js/is.min.js')}}"></script>
<script src="{{asset('lib/lodash/lodash.min.js')}}"></script>
<script src="{{asset('lib/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
{{--<script src="{{asset('lib/echarts/echarts.min.js')}}"></script>--}}
{{--<script src="{{asset('lib/browser/browser-polyfill.min.js')}}"></script>--}}
{{--<script src="{{asset('lib/progressbar.js/progressbar.min.js')}}"></script>--}}
<script src="{{asset('lib/prism/prism.js')}}"></script>
<script src="{{asset('js/custom-file-input.min.js')}}"></script>
{{--<script src="{{asset('js/theme.min.js')}}"></script>--}}

@stack('footer')
<x:notify-messages/>
@notifyJs
</body>

</html>
