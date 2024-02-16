@extends('auth.layout')
@section('title', 'Reset Password')
@section('auth-title', 'Reset Password')
@section('auth-subtitle',
'Selamat datang kembali! Silahkan masukkan password baru untuk melanjutkan.')
@section('content')
<form action="{{ route('password.update') }}" method="post" class="needs-validation">
	@csrf
	<input type="hidden" name="token" value="{{ $token }}">
	<div class="form-group position-relative has-icon-left mb-4">
		<input type="email" placeholder="Email" name="email" value="{{ $email }}"
			class="form-control form-control-xl @error('email') is-invalid @enderror " readonly required />
		<div class="form-control-icon"><i class="bi bi-envelope"></i></div>
		<div class="invalid-feedback">
			@error('email') {{ $message }}
			@else Email tidak valid
			@enderror
		</div>
	</div>
	<div class="form-group position-relative has-icon-left mb-4">
		<input type="password" name="password" placeholder="Password" minlength="8" maxlength="20"
			class="form-control form-control-xl @error('password') is-invalid @enderror "
			oninput="checkpassword()" data-bs-toggle="tooltip" id="password"
			title="8-20 karakter (Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol)"
			data-bs-placement="top" required />
		<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
		<div class="invalid-feedback">
			@error('password') {{ $message }}
			@else Masukkan Password baru (8-20 karakter)
			@enderror
		</div>
	</div>
	<div class="form-group position-relative has-icon-left mb-4">
		<input type="password" name="password_confirmation" id="confirm-password" maxlength="20" minlength="8"
			placeholder="Konfirmasi Password" oninput="checkpassword()" class="form-control form-control-xl @error('password_confirmation') is-invalid @enderror " required />
		<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
		<div class="invalid-feedback">
			@error('password_confirmation') {{ $message }}
			@else Password Konfirmasi salah
			@enderror
		</div>
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