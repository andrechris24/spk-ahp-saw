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
		@include('components.message')
		<div class="modal fade text-left" id="DelAccountModal" tabindex="-1"
			role="dialog" aria-labelledby="DelAccountLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
				role="document">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h4 class="modal-title" id="DelAccountLabel">Hapus Akun</h4>
						<button type="button" class="close" data-bs-dismiss="modal"
							aria-label="Close">
							<i data-feather="x"></i>
						</button>
					</div>
					<div class="modal-body">
						<form action="{{ url('/akun/del') }}" method="post"
							enctype="multipart/form-data" id="form-delete-account"
							onsubmit="$('#DelAccountAnim').removeClass('d-none');">@csrf
							@method('DELETE')
							<div class="alert alert-warning d-none" id="capslock2">
								<i class="bi bi-capslock-fill"></i> CAPS LOCK nyala
							</div>
							<p>Apakah Anda yakin ingin menghapus akun?</p>
							<p>Jika yakin, masukkan password Anda.
								Anda akan keluar secara otomatis setelah menghapus akun.</p>
							<div class="form-group has-icon-left">
								<div class="position-relative">
									<input type="password" name="del_password" id="pass-del"
										class="form-control" pattern=".{8,20}" maxlength="20"
										placeholder="Password" required />
									<div class="form-control-icon">
										<i class="bi bi-lock"></i>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<div class="spinner-grow text-danger d-none" role="status" id="DelAccountAnim">
							<span class="visually-hidden">Menghapus...</span>
						</div>
						<button type="button" class="btn btn-light-secondary"
							data-bs-dismiss="modal">
							<i class="bi bi-x d-block d-sm-none"></i>
							<span class="d-none d-sm-block">Batal</span>
						</button>
						<button type="submit" class="btn btn-danger ml-1"
							form="form-delete-account">
							<i class="bi bi-check d-block d-sm-none"></i>
							<span class="d-none d-sm-block">Hapus</span>
						</button>
					</div>
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
						action="{{ url('/akun') }}" id="form-edit-account">@csrf
						<div class="form-body">
							<div class="row">
								<div class="col-md-4"><label for="nama-user">Nama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="text" name="name" placeholder="Name" id="nama-user"
												class="form-control" value="{{ auth()->user()->name }}"
												pattern="[A-z.,' ]{5,99}" maxlength="99" required />
											<div class="form-control-icon">
												<i class="bi bi-person"></i>
											</div>
											<div class="invalid-feedback" id="name-error">
												Masukkan Nama
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label for="email-user">Email</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="email" name="email" placeholder="Email"
												id="email-user" value="{{ auth()->user()->email }}"
												class="form-control" required />
											<div class="form-control-icon">
												<i class="bi bi-envelope"></i>
											</div>
											<div class="invalid-feedback" id="email-error">
												Masukkan Email
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4"><label>Password Lama</label></div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="current_password" id="password-current"
												class="form-control" placeholder="Password Anda" maxlength="20"
												required />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											<div class="invalid-feedback" id="current-password-error">
												Masukkan Password Anda
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label for="newpassword">Password Baru</label>
								</div>
								<div class="col-md-8">
									<div class="form-group has-icon-left">
										<div class="position-relative">
											<input type="password" name="password" class="form-control"
												placeholder="Kosongkan jika tidak ganti password"
												oninput="checkpassword()" pattern=".{8,20}" id="newpassword"
												data-bs-toggle="tooltip" maxlength="20" data-bs-placement="top"
												title="8-20 karakter" />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											<div class="invalid-feedback" id="newpassword-error"></div>
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
												id="conf-password" oninput="checkpassword()" class="form-control"
												placeholder="Ketik ulang Password baru" />
											<div class="form-control-icon">
												<i class="bi bi-lock"></i>
											</div>
											<div class="invalid-feedback" id="confirm-password-error">
												Password konfirmasi salah
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 d-flex justify-content-end">
									<div class="spinner-grow text-primary me-3 d-none" role="status">
										<span class="visually-hidden">Menyimpan...</span>
									</div>
									<div class="btn-group">
										<button type="submit" class="btn btn-primary data-submit">
											<i class="bi bi-save-fill"></i> Simpan
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
		$('#form-edit-account').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				data: $('#form-edit-account').serialize(),
				url: '{{ route('akun.perform') }}',
				type: 'POST',
				beforeSend: function() {
					$('#form-edit-account :input').removeClass(
						'is-invalid');
					$('#form-edit-account :input').prop('disabled',
						true);
					$('.data-submit').prop('disabled', true);
					$('.spinner-grow').removeClass('d-none');
				},
				complete: function() {
					$('#form-edit-account :input').prop('disabled',
						false);
					$('.data-submit').prop('disabled', false);
					$('.spinner-grow').addClass('d-none');
				},
				success: function(status) {
					$('input[type=password]').val("");

					// sweetalert
					Swal.fire({
						icon: 'success',
						title: 'Sukses',
						text: status.message,
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				},
				error: function(xhr, code) {
					if (xhr.responseJSON.name) {
						$('#nama-user').addClass('is-invalid');
						$('#name-error').text(xhr.responseJSON
							.name);
					}
					if (xhr.responseJSON.email) {
						$('#email-user').addClass('is-invalid');
						$('#email-error').text(xhr.responseJSON
							.email);
					}
					if (xhr.responseJSON.current_password) {
						$('#password-current').addClass(
							'is-invalid');
						$('#current-password-error').text(xhr
							.responseJSON.current_password);
					}
					if (xhr.responseJSON.password) {
						$('#newpassword').addClass('is-invalid');
						$('#newpassword-error').text(xhr
							.responseJSON.password);
					}
					if (xhr.responseJSON.password_confirmation) {
						$('#conf-password').addClass('is-invalid');
						$('#confirm-password-error').text(xhr
							.responseJSON.password_confirmation
						);
					}
					Swal.fire({
						title: 'Gagal update akun',
						text: xhr.responseJSON.message ??
							code,
						icon: 'error',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		});
	</script>
@endsection
