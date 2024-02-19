@extends('auth.layout')
@section('title', 'Login')
@section('auth-title', 'Login')
@section('auth-subtitle', 'Login untuk menggunakan Aplikasi Sistem Pendukung Keputusan')
@section('content')
<form action="{{ route('login.perform') }}" method="POST" enctype="multipart/form-data"
	class="needs-validation">@csrf
	<div class="form-group position-relative has-icon-left mb-4">
		<input type="email" placeholder="Email" name="email" value="{{ old('email') }}"
			class="form-control form-control-xl @error('email') is-invalid @enderror " required />
		<div class="form-control-icon"><i class="bi bi-envelope"></i></div>
		<div class="invalid-feedback">
			@error('email') {{ $message }}
			@else Masukkan Email
			@enderror
		</div>
	</div>
	<div class="form-group position-relative has-icon-left mb-4">
		<input type="password" placeholder="Password" name="password"
		title="8-20 karakter" minlength="8" maxlength="20" id="password"
			class="form-control form-control-xl @error('password') is-invalid @enderror " required />
		<div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
		<div class="invalid-feedback">
			@error('password') {{ $message }}
			@else Masukkan Password
			@enderror
		</div>
	</div>
	<div class="form-check form-check-lg d-flex align-items-end">
		<input class="form-check-input me-2" type="checkbox" id="remember-me" name="remember"
			data-bs-toggle="tooltip" data-bs-placement="top" value="1"
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
	<p class="text-gray-600">Belum punya akun?
		<a href="{{ route('register') }}" class="font-bold">Daftar</a>
	</p>
	<p>
		<a class="font-bold" href="{{ route('password.request') }}">
			Lupa Password
		</a>
	</p>
</div>
@endsection