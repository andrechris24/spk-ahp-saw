@extends('admin.auth')
@section('title', 'Lupa Password')
@section('auth-desc')
	<h1 class="auth-title">Lupa Password</h1>
	<p class="auth-subtitle mb-5">
		Masukkan email Anda untuk mendapatkan link reset password</p>
@endsection

@section('content')
	<form action="{{ route('password.email') }}" method="post">@csrf
		<div class="form-group position-relative has-icon-left mb-4">
			<input type="email" placeholder="Email" name="email"
				value="{{ old('email') }}" required
				class="form-control form-control-xl @error('email') is-invalid @enderror " />
			<div class="form-control-icon"><i class="bi bi-envelope"></i></div>
			@error('email')
				<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>
		<button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5"
			id="submitBtn">
			<i class="bi bi-send-fill"></i> Kirim
		</button>
	</form>
	<div class="text-center mt-5 text-lg fs-4">
		<p class="text-gray-600">
			Ingat akun Anda?
			<a href="{{ route('login') }}" class="font-bold">Login</a>
		</p>
	</div>
@endsection
