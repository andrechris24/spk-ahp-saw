<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		@yield('title') | Sistem Pendukung Keputusan metode AHP & SAW
	</title>
	<link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}" />
	<link rel="stylesheet" href="{{ url('assets/css/pages/auth.css') }}" />
	<link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.svg') }}"
		type="image/x-icon" />
	<link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png') }}"
		type="image/png" />
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
	<script src="{{ url('assets/js/bootstrap.js') }}"></script>
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
