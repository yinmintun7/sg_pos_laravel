<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/uistyle2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/swiper.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/bootstrap/css/fontawesomeall.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/sweetalert.css') }}">
    <link href="{{ asset('asset/css/style.css?v=20240109') }}" rel="stylesheet">
    <script src="{{ asset('asset/bootstrap/js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js/heightLine.js') }}"></script>
    <script src="{{ asset('asset/js/swiper.min.js') }}"></script>
    <script src="{{ asset('asset/js/sweetalert-dev.js') }}"></script>
    <script src="{{ asset('asset/js/angular/angular.min.js') }}"></script>
    <script src="{{ asset('asset/js/common.js') }}"></script>
    <style>
        .item-td {
            text-align: center !important;
        }
        .price-input {
            width: 100% !important;
            text-align: center;
        }

        .clediv {
            clear: both;
        }
        /* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
    </style>
</head>

<body>
    <div class="loading" style="display: none">Loading&#8230;</div>
    <div class="wrapper">
        <div class="header-sec">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-4 heightLine_01 head-lbox">
                        <div>
                            <a class="btn btn-large dash-btn"
                                href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dashboard</a>
                        </div>
                    </div>
                    <div class="col-md-2 col-2 heightLine_01">
                        <img src="{{ asset('asset/images/frontend/resturant_logo.png') }}" alt="ROS logo"
                            class="ros-logo">
                    </div>

                    <div class="col-md-3 col-3 heightLine_01 head-rbox">
                        <div>
                            <span class="staff-name">
                                Staff
                            </span>
                            <div class="dropdown show pull-right">
                                <button role="button" id="dropdownMenuLink" class="btn btn-primary user-btn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('asset/images/frontend/login_img.png') }}" alt="login image">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- header-sec -->
