@extends('layout')
@section('title', 'Edit Akun')
@section('subtitle', 'Edit Akun')
@section('page-desc')
	Untuk melakukan perubahan, masukkan password Anda.
	Jika Anda tidak ingin ganti password, biarkan kolom password baru kosong.
@endsection
@section('content')
	<div class="card">
		<div class="card-content">
			<div class="card-body">
				<x-caps-lock />
				<form class="form form-horizontal needs-validation" method="post"
					action="{{ route('akun.perform') }}" id="form-edit-account">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4"><label for="nama-user">Nama</label></div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="text" name="name" placeholder="Nama" id="nama-user"
											class="form-control" value="{{ auth()->user()->name }}"
											pattern="[A-z.,' ]{5,99}" maxlength="99" required />
										<div class="form-control-icon">
											<i class="bi bi-person"></i>
										</div>
										<div class="invalid-feedback" id="name-error">
											Masukkan Nama (Tanpa simbol dan angka)
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4"><label for="email-user">Email</label></div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="email" name="email" placeholder="mail@example.com"
											id="email-user" value="{{ auth()->user()->email }}" class="form-control"
											required />
										<div class="form-control-icon">
											<i class="bi bi-envelope"></i>
										</div>
										<div class="invalid-feedback" id="email-error">
											Masukkan Email (email@example.com)
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<label for="password-current">Password Lama</label>
							</div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="password" name="current_password" id="password-current"
											class="form-control" placeholder="Password Anda" minlength="8" maxlength="20"
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
										<input type="password" name="password" class="form-control" id="newpassword"
											placeholder="Kosongkan jika tidak ganti password" oninput="checkpassword()"
											minlength="8" maxlength="20" data-bs-toggle="tooltip" data-bs-placement="top"
											title="8-20 karakter" />
										<div class="form-control-icon">
											<i class="bi bi-lock"></i>
										</div>
										<div class="invalid-feedback" id="newpassword-error">
											Password baru harus terdiri dari 8-20 karakter
										</div>
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
										<input type="password" name="password_confirmation" minlength="8" maxlength="20"
											id="conf-password" oninput="checkpassword()" class="form-control"
											placeholder="Ketik ulang Password baru" />
										<div class="form-control-icon">
											<i class="bi bi-lock"></i>
										</div>
										<div class="invalid-feedback" id="confirm-password-error">
											Password Konfirmasi salah
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 d-flex justify-content-end">
								<div class="spinner-grow text-info me-3 d-none" role="status">
									<span class="visually-hidden">Menyimpan...</span>
								</div>
								<div class="btn-group">
									<button type="submit" class="btn btn-primary data-submit">
										<i class="bi bi-save-fill"></i> Simpan
									</button>
									<button type="button" class="btn btn-danger" id="DelAccountBtn">
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
@endsection
@section('js')
	<script type="text/javascript" src="{{ asset('js/password.js') }}"></script>
	<script type="text/javascript">
		function submitform(e) {
			var errmsg;
			e.preventDefault();
			$.ajax({
				data: $('#form-edit-account').serialize(),
				url: "{{ route('akun.perform') }}",
				type: 'POST',
				beforeSend: function() {
					$('#form-edit-account :input').removeClass('is-invalid')
						.prop('disabled', true);
					$('.data-submit').prop('disabled', true);
					$('#DelAccountBtn').prop('disabled', true);
					$('.spinner-grow').removeClass('d-none');
				},
				complete: function() {
					$('#form-edit-account :input').prop('disabled', false);
					$('.data-submit').prop('disabled', false);
					$('#DelAccountBtn').prop('disabled', false);
					$('.spinner-grow').addClass('d-none');
				},
				success: function(status) {
					$('input[type=password]').val("");
					resetvalidation();
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
					if (xhr.status === 422) {
						if (typeof xhr.responseJSON.errors.name !==
							"undefined") {
							$('#nama-user').addClass('is-invalid');
							$('#name-error')
								.text(xhr.responseJSON.errors.name);
						}
						if (typeof xhr.responseJSON.errors.email !==
							"undefined") {
							$('#email-user').addClass('is-invalid');
							$('#email-error')
								.text(xhr.responseJSON.errors.email);
						}
						if (typeof xhr.responseJSON.errors
							.current_password !== "undefined") {
							$('#password-current').addClass('is-invalid');
							$('#current-password-error').text(xhr
								.responseJSON.errors.current_password);
						}
						if (typeof xhr.responseJSON.errors.password !==
							"undefined") {
							$('#newpassword').addClass('is-invalid');
							$('#newpassword-error')
								.text(xhr.responseJSON.errors.password);
						}
						if (typeof xhr.responseJSON.errors
							.password_confirmation !== "undefined") {
							$('#conf-password').addClass('is-invalid');
							$('#confirm-password-error').text(xhr
								.responseJSON.errors
								.password_confirmation);
						}
						errmsg = xhr.responseJSON.message ?? code;
					} else {
						errmsg = 'Kesalahan HTTP ' + xhr.status + '. ' +
							(xhr.responseJSON.message ?? code)
					}
					Swal.fire({
						title: 'Gagal update akun',
						text: errmsg,
						icon: 'error',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		};
		$(document).on('click', "#DelAccountBtn", async function() {
			const {
				value: password
			} = await Swal.fire({
				title: 'Hapus Akun?',
				text: 'Jika Anda sudah yakin ingin menghapus akun, ' +
					'masukkan password Anda untuk melanjutkan.' +
					'Anda akan log out setelah menghapus akun.',
				input: 'password',
				inputLabel: 'Password',
				inputPlaceholder: "Password Anda",
				inputAttributes: {
					maxlength: 20,
					autocapitalize: 'off',
					autocorrect: 'off'
				},
				inputValidator: (value) => {
					if (!value) return "Masukkan Password Anda";
					else if (value.length < 8 || value.length > 20)
						return "Panjang Password harus 8-20 karakter"
				},
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: "Ya",
				cancelButtonText: "Tidak",
				customClass: {
					confirmButton: 'btn btn-primary me-3',
					cancelButton: 'btn btn-secondary'
				},
				buttonsStyling: false
			});
			if (password) {
				$.ajax({
					url: "{{ route('akun.delete') }}",
					type: 'DELETE',
					data: {
						del_password: password
					},
					headers: {
						'X-CSRF-TOKEN': "{{ csrf_token() }}"
					},
					beforeSend: function() {
						$('#form-edit-account :input').removeClass(
							'is-invalid').prop('disabled', true);
						$('#DelAccountBtn').prop('disabled', true);
						$('.data-submit').prop('disabled', true);
						$('.spinner-grow').removeClass('d-none');
					},
					success: function(status) {
						Swal.fire({
							icon: 'success',
							title: "Akun sudah dihapus",
							text: status.message,
							customClass: {
								confirmButton: 'btn btn-success'
							}
						});
						location.href = "{{ route('login') }}";
					},
					error: function(xhr, code) {
						$('#form-edit-account :input')
							.prop('disabled', false);
						$('#DelAccountBtn').prop('disabled', false);
						$('.data-submit').prop('disabled', false);
						$('.spinner-grow').addClass('d-none');
						Swal.fire({
							title: "Gagal hapus akun",
							text: 'Kesalahan HTTP ' + xhr
								.status + '. ' + (xhr
									.responseJSON.message ?? code
								),
							icon: 'error',
							customClass: {
								confirmButton: 'btn btn-success'
							}
						});
					}
				});
			}
		});
	</script>
@endsection
