<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title') | Sistem Pendukung Keputusan metode AHP & SAW</title>
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/auth.css" />
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css" />
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css" />
	<link rel="shortcut icon"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/images/logo/favicon.svg"
		type="image/x-icon" />
	<link rel="shortcut icon"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/images/logo/favicon.png"
		type="image/png" />
	<script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
</head>

<body onload="switchvalidation()">
	<div id="auth">
		<div class="row h-100">
			<div class="col-lg-7 col-12">
				<div id="auth-left">
					<div class="auth-logo">
						<div class="d-flex justify-content-between">
							<img src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/images/logo/logo.svg"
								alt="Logo" />
							<x-theme />
						</div>
					</div>
					<x-no-script />
					<h1 class="auth-title">@yield('auth-title')</h1>
					<p class="auth-subtitle mb-5">@yield('auth-subtitle')</p>
					<x-alert type="error" icon="bi bi-x-circle-fill" />
					<x-alert type="warning" icon="bi bi-exclamation-circle-fill" />
					<x-alert type="success" icon="bi bi-check-circle-fill" />
					<x-caps-lock />
					@yield('content')
				</div>
			</div>
			<div class="col-lg-5 d-none d-lg-block">
				<div id="auth-right"></div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js">
	</script>
	<script type="text/javascript" src="{{ asset('js/tooltip.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/password.js') }}"></script>
	<script type="text/javascript">
		function submitform(e) {
			const inputs = document.getElementsByTagName('input');
			for (let x = 0; x < inputs.length; x++) {
				inputs[x].classList.remove('is-invalid');
			}
		}
	</script>
</body>

</html>