@extends('admin.auth')
@section('title', 'Daftar')
@section('auth-title', 'Daftar')
@section('auth-subtitle', 'Selamat datang! Silahkan masukkan data Anda.')
@section('auth-css', asset('assets/compiled/css/auth.css'))
@section('content')
	<form action="{{ route('register.perform') }}" method="post"
		enctype="multipart/form-data">
		@csrf
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="text" name="email" placeholder="Email"
				value="{{ old('email') }}" required
				class="form-control form-control-xl @error('email') is-invalid @enderror " />
			<div class="form-control-icon"><i class="bi bi-envelope"></i></div>
			@error('email')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="text" name="name" maxlength="99" placeholder="Nama lengkap"
				class="form-control form-control-xl @error('name') is-invalid @enderror "
				pattern="[A-z.,' ]{5,99}" value="{{ old('name') }}" required />
			<div class="form-control-icon"><i class="bi bi-person"></i></div>
			@error('name')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" placeholder="Password" name="password"
				class="form-control form-control-xl @error('password') is-invalid @enderror "
				pattern=".{8,20}" maxlength="20" id="password" oninput="checkpassword()"
				data-bs-toggle="tooltip" data-bs-placement="top" required
				title="8-20 karakter (Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol)" />
			<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
			@error('password')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" placeholder="Konfirmasi Password" maxlength="20"
				name="password_confirmation" id="confirm-password"
				class="form-control form-control-xl @error('password_confirmation') is-invalid @enderror "
				oninput="checkpassword()" required />
			<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
			@error('password_confirmation')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
			Daftar
		</button>
	</form>
	<div class="text-center mt-5 text-lg fs-4">
		<p class="text-gray-600">
			Sudah punya akun? <a href="{{ route('login') }}" class="font-bold">Login</a>
		</p>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		const accpassword = document.querySelectorAll('input[type="password"]');
		const message = document.querySelector('#capslock');
		for (a = 0; a < accpassword.length; a++) {
			accpassword[a].addEventListener('keyup', function(e) {
				if (e.getModifierState('CapsLock')) {
					message.classList.remove('d-none');
					message.classList.add('d-block');
				} else {
					message.classList.remove('d-block');
					message.classList.add('d-none');
				}
			});
		}
		var newpassform = document.getElementById("password");
		var passcekform = document.getElementById("confirm-password");

		function checkpassword() {
			var pass1 = newpassform.value;
			var pass2 = passcekform.value;
			if (pass1 !== pass2)
				passcekform.setCustomValidity("Password konfirmasi salah");
			else passcekform.setCustomValidity("");
		}
	</script>
@endsection
