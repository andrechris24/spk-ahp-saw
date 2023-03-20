@extends('admin.auth')
@section('title', 'Registrasi')
@section('auth-desc')
	<h1 class="auth-title">Registrasi</h1>
	<p class="auth-subtitle mb-5">
		Masukkan data Anda
	</p>
@endsection

@section('content')
	<form action="{{ url('register') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="text" name="email" placeholder="Email" required
				class="form-control form-control-xl @error('email') is-invalid @enderror " />
			<div class="form-control-icon">
				<i class="bi bi-envelope"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="text" name="name" maxlength="99" placeholder="Nama lengkap"
				class="form-control form-control-xl @error('name') is-invalid @enderror "
				pattern="[A-z.,' ]{5,99}" required />
			<div class="form-control-icon">
				<i class="bi bi-person"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" placeholder="Password" name="password"
				class="form-control form-control-xl @error('password') is-invalid @enderror "
				pattern=".{8,20}" maxlength="20" id="password" oninput="checkpassword()"
				data-bs-toggle="tooltip" data-bs-placement="top" required
				title="8-20 karakter (Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol)" />
			<div class="form-control-icon">
				<i class="bi bi-shield-lock"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" placeholder="Confirm Password" name="password_confirmation"
				class="form-control form-control-xl @error('password_confirmation') is-invalid @enderror "
				maxlength="20" oninput="checkpassword()" id="confirm-password" required />
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
			if (pass1 !== pass2) passcekform.setCustomValidity(
				"Password konfirmasi salah"
			);
			else passcekform.setCustomValidity("");
		}
	</script>
@endsection
