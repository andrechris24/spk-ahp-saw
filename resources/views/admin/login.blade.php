@extends('admin.auth')
@section('title', 'Login')
@section('auth-desc')
	<h1 class="auth-title">Login</h1>
	<p class="auth-subtitle mb-5">
		Login dengan data yang sudah Anda daftarkan
	</p>
@endsection

@section('content')
	<div class="alert alert-warning d-none" id="capslock">
		<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
	</div>
	<form action="{{ url('login') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="email" class="form-control form-control-xl" placeholder="Email"
				name="email" required />
			<div class="form-control-icon">
				<i class="bi bi-envelope"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" class="form-control form-control-xl" placeholder="Password"
				name="password" pattern=".{8,20}" maxlength="20" id="password" required />
			<div class="form-control-icon">
				<i class="bi bi-shield-lock"></i>
			</div>
		</div>
		<div class="form-check form-check-lg d-flex align-items-end">
			<input class="form-check-input me-2" type="checkbox" value="1" id="remember-me"
				name="remember" data-bs-toggle="tooltip" data-bs-placement="top"
				title="Berlaku selama 7 hari, jangan dicentang jika Anda menggunakannya di tempat umum" />
			<label class="form-check-label text-gray-600" for="remember-me">
				Biarkan saya login
			</label>
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
			Login
		</button>
	</form>
	<div class="text-center mt-5 text-lg fs-4">
		<p class="text-gray-600">
			Belum punya akun?
			<a href="{{ url('register') }}" class="font-bold">Daftar</a>
		</p>
		<p>
			<a class="font-bold" href="{{ url('forget-password') }}">
				Lupa Password
			</a>
		</p>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		const password = document.querySelector('#password');
		const message = document.querySelector('#capslock');
		password.addEventListener('keyup', function(e) {
			if (e.getModifierState('CapsLock')) {
				message.classList.remove('d-none');
				message.classList.add('d-block');
			} else {
				message.classList.remove('d-block');
				message.classList.add('d-none');
			}
		});
	</script>
@endsection
