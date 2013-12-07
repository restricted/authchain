<h1>Login page</h1>
{{ $error = Session::get('error') }}
@if($error)
<h2>{{ $error }}</h2>
@endif
{{ Form::open(['login']) }}
{{ Form::input('text', 'username', 'username') }}
{{ Form::password('password') }}
{{ Form::submit('Submit') }}
{{ Form::close() }}