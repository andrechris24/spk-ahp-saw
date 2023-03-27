<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>404 Not Found | Sistem Pendukung Keputusan metode AHP & SAW</title>
	<link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}" />
	<link rel="stylesheet" href="{{ url('assets/css/pages/error.css') }}" />
	<link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.svg') }}"
		type="image/x-icon" />
	<link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png') }}"
		type="image/png" />
</head>

<body>
	<div id="error">
		<div class="error-page container">
			<div class="col-md-8 col-12 offset-md-2">
				<div class="text-center">
					<h1 class="error-title">404 Not found</h1>
					<p class="fs-5 text-gray-600">
						Halaman yang Anda cari tidak ditemukan.
					</p>
					<a href="{{ url('/') }}" class="btn btn-lg btn-outline-primary mt-3">
						Kembali
					</a>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
