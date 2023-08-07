<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		@yield('title') | Sistem Pendukung Keputusan metode AHP & SAW
	</title>
	<link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}" />
	<link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}"
		type="image/x-icon" />
	<link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.png') }}"
		type="image/png" />
	<script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
</head>

<body>
	<div id="auth">
		<div class="row h-100">
			<div class="col-lg-7 col-12">
				<div id="auth-left">
					<div class="auth-logo">
						<img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo" />
					</div>
					@yield('auth-desc')
					@include('main.message')
					<div class="alert alert-warning d-none" id="capslock">
						<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
					</div>
					@yield('content')
				</div>
			</div>
			<div class="col-lg-5 d-none d-lg-block">
				<div id="auth-right"></div>
			</div>
		</div>
	</div>
	<script src="{{ asset('assets/compiled/js/app.js') }}"></script>
	@yield('js')
	<script type="text/javascript">
		// If you want to use tooltips in your project, we suggest initializing them globally
		// instead of a "per-page" level.
		document.addEventListener(
			"DOMContentLoaded",
			function() {
				var tooltipTriggerList = [].slice.call(
					document.querySelectorAll('[data-bs-toggle="tooltip"]')
				);
				tooltipTriggerList.map(function(tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl);
				});
			},
			false
		);
	</script>
</body>

</html>
