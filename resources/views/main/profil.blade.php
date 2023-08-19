@extends('layout')
@section('title', 'Edit Akun')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Edit Akun</h3>
			<p class="text-subtitle text-muted">
				Untuk melakukan perubahan, masukkan password Anda.
				Jika Anda tidak ingin ganti password, biarkan kolom password baru kosong.
			</p>
		</div>
		@include('components.error-single')
		@include('components.warning')
		@include('components.success')
		@include('components.noscript')
		<div class="modal fade text-left" id="DelAccountModal" tabindex="-1"
			role="dialog" aria-labelledby="DelAccountLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
				role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="DelAccountLabel">Hapus Akun</h4>
						<button type="button" class="close" data-bs-dismiss="modal"
							aria-label="Close">
							<i data-feather="x"></i>
						</button>
					</div>
					<form action="{{ url('/akun/del') }}" method="post"
						enctype="multipart/form-data">
						@csrf
						<div class="modal-body">
							<div class="alert alert-warning d-none" id="capslock2">
								<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
							</div>
							<p>Apakah Anda yakin ingin menghapus akun?</p>
							<p>
								Jika yakin, masukkan password Anda.
								Anda akan keluar secara otomatis setelah menghapus akun.
							</p>
							<div class="form-group">
								<input type="password" name="del_password" id="pass-del"
									class="form-control @error('del_password') is-invalid @enderror "
									pattern=".{8,20}" maxlength="20" placeholder="Password" required />
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light-secondary"
								data-bs-dismiss="modal">
								<i class="bx bx-x d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Batal</span>
							</button>
							<button type="submit" class="btn btn-danger ml-1">
								<i class="bx bx-check d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Hapus</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-content">
				<div class="card-body">
					<div class="alert alert-warning d-none" id="capslock">
						<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
					</div>
					<form class="form form-horizontal" method="post"
						action="{{ url('/akun') }}">@csrf
						<div class="form-body">
							<div class="row">
								<div class="col-md-4"><label for="nama-user">Nama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="text" name="name" placeholder="Name" id="nama-user"
												class="form-control @error('name') is-invalid @enderror "
												value="{{ auth()->user()->name }}" pattern="[A-z.,' ]{5,99}"
												maxlength="99" required />
											<div class="form-control-icon">
												<i class="bi bi-person"></i>
											</div>
											@error('name')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-4"><label for="email-user">Email</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="email" name="email" placeholder="Email"
												id="email-user" value="{{ auth()->user()->email }}"
												class="form-control @error('email') is-invalid @enderror "
												required />
											<div class="form-control-icon">
												<i class="bi bi-envelope"></i>
											</div>
											@error('email')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Password Lama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="current_password"
												class="form-control @error('current_password') is-invalid @enderror "
												placeholder="Password Anda" maxlength="20" required />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											@error('current_password')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="newpassword">Password Baru</label>
								</div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="password"
												class="form-control @error('password') is-invalid @enderror "
												placeholder="Kosongkan jika tidak ganti password"
												oninput="checkpassword()" pattern=".{8,20}" id="newpassword"
												data-bs-toggle="tooltip" maxlength="20" data-bs-placement="top"
												title="8-20 karakter" />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											@error('password')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<p>
											<small class="text-muted">
												Saran: terdiri dari huruf besar, huruf kecil, angka, dan simbol
											</small>
										</p>
									</div>
								</div>
								<div class="col-md-4">
									<label for="conf-password">Konfirmasi Password</label>
								</div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="password_confirmation" maxlength="20"
												id="conf-password" oninput="checkpassword()"
												class="form-control
												@error('password_confirmation') is-invalid @enderror "
												placeholder="Ketik ulang Password baru" />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											@error('password_confirmation')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-12 d-flex justify-content-end">
									<div class="btn-group">
										<button type="submit" class="btn btn-primary">
											<i class="bi bi-save-fill"></i> Simpan
										</button>
										<button type="reset" class="btn btn-secondary">
											<i class="bi bi-arrow-counterclockwise"></i> Reset
										</button>
										<button type="button" class="btn btn-danger" data-bs-toggle="modal"
											data-bs-target="#DelAccountModal">
											<i class="bi bi-trash3-fill"></i> Hapus Akun Ini
										</button>
									</div>
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
		const popupmsg = document.querySelector('#capslock2');
		for (let a = 0; a < accpassword.length; a++) {
			accpassword[a].addEventListener('keydown', function(e) {
				if (e.getModifierState('CapsLock')) {
					message.classList.remove('d-none');
					popupmsg.classList.remove('d-none');
				} else {
					message.classList.add('d-none');
					popupmsg.classList.add('d-none');
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
