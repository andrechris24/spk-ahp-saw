@extends('admin.auth')
@section('title', 'Reset Password')
@section('auth-title', 'Reset Password')
@section('auth-subtitle',
	'Selamat datang kembali! Silahkan masukkan password
	baru untuk melanjutkan.')
@section('auth-css', asset('assets/compiled/css/auth-forgot-password.css'))
@section('content')
	<form action="{{ route('password.update') }}" method="post">@csrf
		<input type="hidden" name="token" value="{{ $token }}">
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="email" placeholder="Email" name="email"
				value="{{ $email }}" readonly required
				class="form-control form-control-xl @error('email') is-invalid @enderror " />
			<div class="form-control-icon"><i class="bi bi-envelope"></i></div>
			@error('email')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="password" name="password" placeholder="Password"
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
			<input type="password" name="password_confirmation" id="confirm-password"
				placeholder="Konfirmasi Password" maxlength="20" required
				oninput="checkpassword()"
				class="form-control
				form-control-xl @error('password_confirmation') is-invalid @enderror " />
			<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
			@error('password_confirmation')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
			Reset
		</button>
	</form>
	<div class="text-center mt-5 text-lg fs-4">
		<p class="text-gray-600">
			Ingat akun Anda? <a href="{{ route('login') }}" class="font-bold">Login</a>
		</p>
	</div>
@endsection
@section('js')
	<script type="text/javascript" src="{{ asset('js/password.js') }}"></script>
@endsection
