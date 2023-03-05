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
					@if (Session::has('error') || $errors->any())
						<div class="alert alert-danger alert-dismissible" role="alert">
							<i class="bi bi-x-circle-fill"></i>
							@if (Session::has('error'))
								{{ ucfirst(Session::get('error')) }}
							@elseif($errors->any())
								Gagal:
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ ucfirst($error) }}</li>
									@endforeach
								</ul>
							@endif
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
						</div>
					@endif
					@if (Session::has('warning'))
						<div class="alert alert-warning alert-dismissible" role="alert">
							<i class="bi bi-exclamation-triangle-fill"></i>
							{{ Session::get('warning') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
						</div>
					@endif
					@if (Session::has('success'))
						<div class="alert alert-success alert-dismissible" role="alert">
							<i class="bi bi-check-circle-fill"></i>
							{{ Session::get('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
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
	<script src="assets/js/bootstrap.js"></script>
	@yield('js')
	<script type="text/javascript">
		// If you want to use tooltips in your project, we suggest initializing them globally
		// instead of a "per-page" level.
		document.addEventListener(
			"DOMContentLoaded",
			function () {
				var tooltipTriggerList = [].slice.call(
					document.querySelectorAll('[data-bs-toggle="tooltip"]')
				);
				var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl);
				});
			},
			false
		);
	</script>
</body>

</html>
