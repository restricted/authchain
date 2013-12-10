<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        body.loginpage {
            background: #0866c6;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, sans-serif;
        }

        a, a:hover, a:link, a:active, a:focus {
            outline: none;
            color: #0866c6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        input, select,
        textarea, button {
            outline: none;
            font-size: 13px;
            font-family: 'RobotoRegular', 'Helvetica Neue', Helvetica, sans-serif;
        }

        strong {
            font-weight: normal;
        }

        label, input, textarea, select, button {
            font-size: 13px;
        }

        h1, h2, h3, h4, h5 {
            font-weight: normal;
            line-height: normal;
        }

        .loginpanel {
            position: absolute;
            top: 50%;
            left: 50%;
            height: 300px;
        }

        .loginpanelinner {
            position: relative;
            top: -150px;
            left: -50%;
        }

        .loginpanelinner .logo {
            text-align: center;
            padding: 20px 0;
        }

        .loginpanelinner .logo h2 {
            color: #ffffff;
        }

        .loginpanel .pull-right a {
            color: #ddd;
        }

        .inputwrapper {
            margin-bottom: 10px;
        }

        .inputwrapper input {
            border: 0;
            padding: 10px;
            background: #fff;
            width: 250px;
        }

        .inputwrapper input:active, .inputwrapper input:focus {
            background: #fff;
            border: 0;
        }

        .inputwrapper button {
            display: block;
            border: 1px solid #0c57a3;
            padding: 10px;
            background: #0972dd;
            width: 100%;
            color: #fff;
            text-transform: uppercase;
        }

        .inputwrapper button:focus, .inputwrapper button:active, .inputwrapper button:hover {
            background: #1e82e8;
        }

        .inputwrapper label {
            display: inline-block;
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 11px;
            vertical-align: middle;
            font-weight: normal;
        }

        .inputwrapper label input {
            width: auto;
            margin: -3px 5px 0 0;
            vertical-align: middle;
        }

        .inputwrapper .remember {
            padding: 0;
            background: none;
        }

        .login-alert {
            display: none;
        }

        .login-alert .alert {
            font-size: 11px;
            text-align: center;
            padding: 5px 0;
            border: 0;
        }

        .loginfooter {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            position: absolute;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-family: arial, sans-serif !important;
            padding: 5px 0;
        }


    </style>
    <title>@yield('title')</title>
</head>
<body class="loginpage">
<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo"><h2>authentication</h2></div>
        {{ Form::open(['login']) }}
        @if(Session::get('error'))
        <div class="inputwrapper login-alert">
            <div class="alert alert-error">{{ Session::get('error') }}</div>
        </div>
        @endif
        <div class="inputwrapper">
            {{ Form::input('text', 'username', 'username', array('id' => 'username', 'placeholder' => 'Username') ) }}
            {{ Form::input('hidden', '_token', csrf_token()) }}
        </div>
        <div class="inputwrapper">
            {{ Form::password('password', array('id' => 'password', 'placeholder' => 'Password')) }}
        </div>
        <div class="inputwrapper">
            <button name="submit">Sign In</button>
        </div>
        <div class="inputwrapper">
            <label>
                {{ Form::input('checkbox', 'rememberme') }}
                Keep me sign in
            </label>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="loginfooter">
    <p>&copy; 2013. Restricted. All Rights Reserved.</p>
</div>
</body>
</html>
