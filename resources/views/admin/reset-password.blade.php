@extends('admin.auth')
@section('title', 'Reset Password')
@section('auth-desc')
	<h1 class="auth-title">Reset Password</h1>
	<p class="auth-subtitle mb-5">
		Selamat datang kembali! Untuk melanjutkan, masukkan password baru.
	</p>
@endsection

@section('content')
	<div class="alert alert-warning d-none">
		<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
	</div>
	<form action="{{ url('reset-password') }}" method="post">
		@csrf
		<input type="hidden" name="token" value="{{ $token }}">
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" class="form-control form-control-xl" placeholder="Password"
				name="password" pattern=".{8,20}" maxlength="20" id="password"
				data-bs-toggle="tooltip" data-bs-placement="top" data-bs-toggle="tooltip"
				data-bs-placement="top"
				title="Minimal 8 karakter (Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol)"
				oninput="checkpassword()" required />
			<div class="form-control-icon">
				<i class="bi bi-shield-lock"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" class="form-control form-control-xl" id="confirm-password"
				placeholder="Confirm Password" name="password_confirmation" maxlength="20"
				oninput="checkpassword()" required />
			<div class="form-control-icon">
				<i class="bi bi-shield-lock"></i>
			</div>
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
			Reset
		</button>
	</form>
	<div class="text-center mt-5 text-lg fs-4">
		<p class="text-gray-600">
			Ingat akun Anda?
			<a href="{{ url('login') }}" class="font-bold">Login</a>
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
