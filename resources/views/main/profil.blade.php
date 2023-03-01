@extends('layout')
@section('content')
<div class="page-heading">
	<div class="page-title">
		<h3>Edit Akun</h3>
		<p class="text-subtitle text-muted">
			Multiple form layouts, you can use.
		</p>
	</div>
	<div class="card">
		<div class="card-content">
			<div class="card-body">
				<form class="form form-horizontal">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4"><label>Nama</label></div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="text" class="form-control" name="name" 
										placeholder="Name" id="first-name-icon" value="{{  }}" required />
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
										<input type="email" class="form-control" name="email" 
											placeholder="Email" id="first-name-icon" value="{{  }}" required />
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
										<input type="password" name="oldpassword" class="form-control" placeholder="Password Anda" required />
										<div class="form-control-icon"><i class="bi bi-lock"></i></div>
									</div>
								</div>
							</div>
							<div class="col-md-4"><label>Password Baru</label></div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="password" name="newpassword" class="form-control" placeholder="8-20 karakter" required />
										<div class="form-control-icon"><i class="bi bi-lock"></i></div>
									</div>
								</div>
							</div>
							<div class="col-md-4"><label>Konfirmasi Password</label></div>
							<div class="col-md-8">
								<div class="form-group has-icon-left">
									<div class="position-relative">
										<input type="password" name="newpassword_confirmation" class="form-control" placeholder="Ketik ulang Password baru" required/>
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