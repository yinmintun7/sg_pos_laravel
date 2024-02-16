<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Frontend Login</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/bootstrap/css/bootstrap.css') }}" />
    <link rel = "stylesheet" href = "{{ asset('asset/css/login1.css') }}" />
    <script src="{{ asset('asset/bootstrap/js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/angular/angular.min.js') }}"></script>
    <script src="{{ asset('asset/js/angular/angular.min.js') }}"></script>
    <script src="{{ asset('asset/js/common.js') }}"></script>
</head>

<body>
    <section class="intro" ng-app="myApp" ng-controller="myCtrl">
        <div class="inner">
            <div class="content">
                <form class="login-form" method="POST" id='submit' action="{{ route('frontendLogin') }}">
                    @csrf
                    <table style="margin:0 auto;width: 18vw;">
                        <tr>
                            <td colspan="3">
                                <input type="text" placeholder="Enter Username" class="userInput" name = "username"
                                    ng-model = 'username' ng-focus="usernameFocus()" />
                                @if ($errors->has('username'))
                                    <span>{{ $errors->first('username') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="password" placeholder="Enter Password" class="userInput"
                                    name = "password" ng-model = 'password' ng-focus="passwordFocus()">
                            </td>
                            @if ($errors->has('password'))
                                <span>{{ $errors->first('password') }}</span>
                            @endif
                        </tr>
                        <tr>
                            <td><button type="button" class="number-btn fl-left num-btn"
                                    ng-click = "numberClick(0)">0</button></td>
                            <td><button type="button" class="number-btn num-btn" ng-click = "numberClick(1)">1</button>
                            </td>
                            <td><button type="button" class="number-btn fl-right num-btn"
                                    ng-click = "numberClick(2)">2</button></td>
                        </tr>

                        <tr>
                            <td><button type="button" class="number-btn fl-left num-btn"
                                    ng-click = "numberClick(3)">3</button></td>
                            <td><button type="button" class="number-btn num-btn" ng-click = "numberClick(4)">4</button>
                            </td>
                            <td><button type="button" class="number-btn fl-right num-btn"
                                    ng-click = "numberClick(5)">5</button></td>
                        </tr>

                        <tr>
                            <td><button type="button" class="number-btn fl-left num-btn"
                                    ng-click = "numberClick(6)">6</button></td>
                            <td><button type="button" class="number-btn num-btn" ng-click = "numberClick(7)">7</button>
                            </td>
                            <td><button type="button" class="number-btn fl-right num-btn"
                                    ng-click = "numberClick(8)">8</button></td>
                        </tr>

                        <tr>
                            <td><button type="button" class="number-btn fl-left num-btn"
                                    ng-click = "numberClick(9)">9</button></td>
                            <td><button type="button" class="number-btn clear-btn" ng-click = "delete()">X</button>
                            </td>
                            <td><button type="button" class="number-btn fl-right enter-btn"
                                    ng-click="Login()">Enter</button></td>
                        </tr>
                    </table>
                    <input type="hidden" name ="form_sub" value = "1" />
                </form>
            </div>
        </div>
    </section>
</body>
<script src="{{ asset('asset/js/page/login1.js') }}"></script>

</html>
