@extends('admin.auth')
@section('title', 'Login')
@section('auth-desc')
	<h1 class="auth-title">Login</h1>
	<p class="auth-subtitle mb-5">
		Login untuk menggunakan fasilitas Sistem Pendukung Keputusan
	</p>
@endsection

@section('content')
	<form action="{{ url('login') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="email" placeholder="Email" name="email" required
				class="form-control form-control-xl @error('email') is-invalid @enderror " />
			<div class="form-control-icon">
				<i class="bi bi-envelope"></i>
			</div>
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" placeholder="Password" name="password"
				pattern=".{8,20}" id="password" maxlength="20" class="form-control
				form-control-xl @error('password') is-invalid @enderror "
				title="8-20 karakter" required />
			<div class="form-control-icon">
				<i class="bi bi-shield-lock"></i>
			</div>
		</div>
		<div class="form-check form-check-lg d-flex align-items-end">
			<input class="form-check-input me-2" type="checkbox"
				id="remember-me" name="remember" data-bs-toggle="tooltip"
				data-bs-placement="top" value="1"
				title="Berlaku selama 30 hari, jangan dicentang jika bukan perangkat Anda." />
			<label class="form-check-label text-gray-600" for="remember-me">
				Biarkan saya login
			</label>
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
			<i class="bi bi-box-arrow-in-right me-2"></i> Login
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
		password.addEventListener('keydown', function(e) {
			if (e.getModifierState('CapsLock')) message.classList.remove('d-none');
			else message.classList.add('d-none');
		});
	</script>
@endsection
