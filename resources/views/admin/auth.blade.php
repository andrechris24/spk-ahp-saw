<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		@yield('title') | Sistem Pendukung Keputusan metode AHP & SAW
	</title>
	<link rel="stylesheet" href="@yield('auth-css')" />
	<link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
	<link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}"
		type="image/x-icon" />
	<link rel="shortcut icon"
		href="{{ asset('assets/static/images/logo/favicon.png') }}"
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
					<h1 class="auth-title">@yield('auth-title')</h1>
					<p class="auth-subtitle mb-5">@yield('auth-subtitle')</p>
					<x-no-script />
					@include('components.message')
					<x-caps-lock id="capslock" />
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
