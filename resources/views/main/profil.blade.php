@extends('layout')
@section('title','Edit Akun')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Edit Akun</h3>
			<p class="text-subtitle text-muted">
				Untuk melakukan perubahan, masukkan password Anda.
				Jika Anda tidak ingin ganti password, biarkan kolom password baru kosong.
			</p>
		</div>
		@if (Session::has('error') || $errors->any())
						<div class="alert alert-danger alert-dismissible" role="alert">
							<i class="bi bi-x-circle-fill"></i>
							@if (Session::has('error'))
								{{ ucfirst(Session::get('error')) }}
							@elseif($errors->any())
								Gagal update akun:
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ ucfirst($error) }}</li>
									@endforeach
								</ul>
							@endif
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
						</div>
					@endif
					@if (Session::has('warning'))
						<div class="alert alert-warning alert-dismissible" role="alert">
							<i class="bi bi-exclamation-triangle-fill"></i>
							{{ Session::get('warning') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
						</div>
					@endif
					@if (Session::has('success'))
						<div class="alert alert-success alert-dismissible" role="alert">
							<i class="bi bi-check-circle-fill"></i>
							{{ Session::get('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert"
								aria-label="Close"></button>
						</div>
					@endif
		<div class="card">
			<div class="card-content">
				<div class="card-body">
					<div class="alert alert-warning d-none" id="capslock">
						<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
					</div>
					<form class="form form-horizontal" method="post" action="{{ url('/akun') }}">
						@csrf
						<div class="form-body">
							<div class="row">
								<div class="col-md-4"><label>Nama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="text" class="form-control" name="name" placeholder="Name"
												id="nama-user" value="{{ auth()->user()->name }}" required />
											<div class="form-control-icon">
												<i class="bi bi-person"></i>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Email</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="email" class="form-control" name="email" placeholder="Email"
												id="email-user" value="{{ auth()->user()->email }}" required />
											<div class="form-control-icon">
												<i class="bi bi-envelope"></i>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Password Lama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="current_password" class="form-control"
												placeholder="Password Anda" maxlength="
												20" required />
											<div class="form-control-icon"><i class="bi bi-lock"></i></div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Password Baru</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="password" class="form-control"
												placeholder="Kosongkan jika tidak ganti password" id="newpassword" oninput="checkpassword()" title="8-20 karakter" pattern=".{8,20}" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimal 8 karakter (Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol)" />
											<div class="form-control-icon"><i class="bi bi-lock"></i></div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Konfirmasi Password</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="password_confirmation" class="form-control"
												placeholder="Ketik ulang Password baru" id="conf-password" oninput="checkpassword()" />
											<div class="form-control-icon"><i class="bi bi-lock"></i></div>
										</div>
									</div>
								</div>
								<div class="col-12 d-flex justify-content-end">
									<button type="submit" class="btn btn-primary me-1 mb-1">
										Simpan
									</button>
									<button type="reset" class="btn btn-light-secondary me-1 mb-1">
										Reset
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
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
		var newpassform = document.getElementById("newpassword");
		var passcekform = document.getElementById("conf-password");

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
