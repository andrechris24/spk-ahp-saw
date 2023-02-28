<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>403 Forbidden | Sistem Pendukung Keputusan metode AHP & SAW</title>
		<link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}" />
		<link rel="stylesheet" href="{{url('assets/css/pages/error.css')}}" />
	</head>

	<body>
		<div id="error">
			<div class="error-page container">
				<div class="col-md-8 col-12 offset-md-2">
					<div class="text-center">
						<img
							class="img-error"
							src="assets/images/samples/error-403.svg"
							alt="Forbidden"
						/>
						<h1 class="error-title">403 Forbidden</h1>
						<p class="fs-5 text-gray-600">
							Anda tidak boleh mengakses file atau folder secara sembarangan!
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
