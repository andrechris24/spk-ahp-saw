<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	@yield('title')
	<link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}" />
	<link rel="stylesheet" href="{{ url('assets/css/pages/auth.css') }}" />
</head>
<body>
	<div id="auth">
		<div class="row h-100">
			<div class="col-lg-7 col-12">
				<div id="auth-left">
					<div class="auth-logo">
						<img src="{{ url('assets/images/logo/logo.svg') }}" alt="Logo" />
					</div>
					@yield('auth-desc')
					@if($errors->any())
						<div class="alert alert-danger">
							<i class="bi bi-x-circle-fill"></i> Kesalahan:
							<ul>
								@foreach($errors->all() as $error)
									<li>{{$error}}</li>
								@endforeach
							</ul>
						</div>
					@endif
					@if(Session::has('status'))
						<div class="alert alert-warning" role="alert">
							<i class="bi bi-exclamation-triangle-fill"></i>
							{{Session::get('status')}}
						</div>
					@endif
					@if(Session::has('success'))
						<div class="alert alert-warning" role="alert">
							<i class="bi bi-check-circle-fill"></i>
							{{Session::get('success')}}
						</div>
					@endif
					@yield('content')
				</div>
			</div>
			<div class="col-lg-5 d-none d-lg-block">
				<div id="auth-right"></div>
			</div>
		</div>
	</div>
	@yield('js')
</body>
</html>