<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>
		Registrasi Administrator | Sistem Pendukung Keputusan metode AHP dan SAW
		</title>
		<link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}" />
		<link rel="stylesheet" href="{{ url('assets/css/pages/auth.css') }}" />
		<link
			rel="shortcut icon"
			href="{{ url('assets/images/logo/favicon.svg') }}"
			type="image/x-icon"
		/>
		<link
			rel="shortcut icon"
			href="{{ url('assets/images/logo/favicon.png') }}"
			type="image/png"
		/>
	</head>

	<body>
		<div id="auth">
			<div class="row h-100">
				<div class="col-lg-5 col-12">
					<div id="auth-left">
						<div class="auth-logo">
							<img src="{{ url('assets/images/logo/logo.svg') }}" alt="Logo"/>
						</div>
						<h1 class="auth-title">Registrasi</h1>
						<p class="auth-subtitle mb-5">
							Masukkan data Anda 
						</p>
						<form action="{{ url('register') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="form-group position-relative has-icon-left mb-4">
								<input
									type="text"
									class="form-control form-control-xl"
									placeholder="Email"
									name="email"
									required
								/>
								<div class="form-control-icon">
									<i class="bi bi-envelope"></i>
								</div>
							</div>
							<div class="form-group position-relative has-icon-left mb-4">
								<input
									type="text"
									class="form-control form-control-xl"
									placeholder="Nama lengkap"
									name="name"
									maxlength="99"
									pattern="[A-z.,' ]{5,99}"
									required
								/>
								<div class="form-control-icon">
									<i class="bi bi-person"></i>
								</div>
							</div>
							<div class="form-group position-relative has-icon-left mb-4">
								<input
									type="password"
									class="form-control form-control-xl"
									placeholder="Password"
									name="password"
									pattern=".{8,20}"
									maxlength="20"
									required
								/>
								<div class="form-control-icon">
									<i class="bi bi-shield-lock"></i>
								</div>
							</div>
							<div class="form-group position-relative has-icon-left mb-4">
								<input
									type="password"
									class="form-control form-control-xl"
									placeholder="Confirm Password"
									name="password_confirmation"
									pattern=".{8,20}"
									maxlength="20"
									required
								/>
								<div class="form-control-icon">
									<i class="bi bi-shield-lock"></i>
								</div>
							</div>
							<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
								Daftar
							</button>
						</form>
						<div class="text-center mt-5 text-lg fs-4">
							<p class="text-gray-600">
								Sudah punya akun?
								<a href="{{ url('login') }}" class="font-bold">Masuk</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-lg-7 d-none d-lg-block">
					<div id="auth-right"></div>
				</div>
			</div>
		</div>
	</body>
</html>
