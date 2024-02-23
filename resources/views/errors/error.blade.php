<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>@yield('title') | Sistem Pendukung Keputusan metode AHP & SAW</title>
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css" />
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/error.css" />
	<link rel="shortcut icon"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/images/logo/favicon.svg"
		type="image/x-icon" />
	<link rel="shortcut icon"
		href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/images/logo/favicon.png"
		type="image/png" />
	<script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
</head>

<body>
	<div id="error">
		<div class="error-page container">
			<div class="col-md-8 col-12 offset-md-2">
				<div class="text-center">
					<h1 class="error-title">@yield('error-title')</h1>
					<p class="fs-5 text-gray-600">@yield('error-text')</p>
					@yield('error-action')
				</div>
			</div>
		</div>
	</div>
</body>

</html>