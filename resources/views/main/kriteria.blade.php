@extends('layout')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Kriteria</h3>
			</div>
		</div>
		<section class="section">
			@if (Session::has('error') || $errors->any())
						<div class="alert alert-danger alert-dismissible" role="alert">
							<i class="bi bi-x-circle-fill"></i>
							@if (Session::has('error'))
								{{ ucfirst(Session::get('error')) }}
							@elseif($errors->any())
								Gagal:
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
			<div class="modal fade text-left" id="AddCritModal" tabindex="-1"
				role="dialog" aria-labelledby="AddCritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="AddCritLabel">Tambah Kriteria</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/kriteria/add') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<label for="nama">Nama Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama" required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="tipe-kriteria">
										Atribut
									</label>
									<select class="form-select" id="tipe-kriteria" name="type" required>
										<option selected>Pilih</option>
										<option value="cost">Cost</option>
										<option value="benefit">Benefit</option>
									</select>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-secondary"
									data-bs-dismiss="modal">
									<i class="bx bx-x d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Batal</span>
								</button>
								<button type="submit" class="btn btn-primary ml-1">
									<i class="bx bx-check d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Tambah</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade text-left" id="EditCritModal" tabindex="-1"
				role="dialog" aria-labelledby="EditCritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="EditCritLabel">Edit Kriteria</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/kriteria/update/:id') }}" method="post" enctype="multipart/form-data" name="editkriteria">
							@csrf
							<div class="modal-body">
								<label for="nama-edit">Nama Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama-edit" required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="tipe-kriteria-edit">
										Atribut
									</label>
									<select class="form-select" id="tipe-kriteria-edit" name="type" required>
										<option selected>Pilih</option>
										<option value="cost">Cost</option>
										<option value="benefit">Benefit</option>
									</select>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-secondary"
									data-bs-dismiss="modal">
									<i class="bx bx-x d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Batal</span>
								</button>
								<button type="submit" class="btn btn-primary ml-1">
									<i class="bx bx-check d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Tambah</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Kriteria</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal"
						data-bs-target="#AddCritModal">
						<i class="bi bi-plus-lg"></i>
						Tambah Kriteria
					</button>
					<table class="table table-striped" id="table-crit">
						<thead>
							<tr>
								{{-- <th>No</th> --}}
								<th>Nama Kriteria</th>
								<th>Atribut</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@if(count($krit)>0)
								@foreach($krit as $kriteria)
									<tr>
										<td>{{ $kriteria->name }}</td>
										<td>{{ $kriteria->type }}</td>
										<td>
											<div class="btn-group" role="button">
												<button type="button" class="btn btn-primary" data-bs-toggle="modal"
													data-bs-target="#EditCritModal" data-bs-id="{{ $kriteria->id }}"
													data-bs-name="{{ $kriteria->name }}" data-bs-type="{{ $kriteria->type }}">
													Edit
												</button>
												<button type="button" class="btn btn-danger" data-bs-toggle="modal"
													data-bs-target="#DelCritModal" data-bs-id="{{ $kriteria->id }}">
												Hapus
												</button>
											</div>
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
<script type="text/javascript">
const editCriteriaModal = document.getElementById('EditCritModal');
editCriteriaModal.addEventListener('show.bs.modal', event => {
	// Button that triggered the modal
	const button = event.relatedTarget;
	// Extract info from data-bs-* attributes
	const nama = button.getAttribute('data-bs-name');
	const tipe=button.getAttribute('data-bs-type');
	const id=button.getAttribute('data-bs-id');
	// If necessary, you could initiate an AJAX request here
	// and then do the updating in a callback.

	// Update the modal's content.
	const nameval = editCriteriaModal.querySelector('#nama-edit')
	const typeval = editCriteriaModal.querySelector('#tipe-kriteria-edit')
	nameval.value = nama;
	typeval.value=tipe;
	var formurl="{{ url('/kriteria/update/:id') }}";
	formurl=formurl.replace(':id',id);
	document.editkriteria.action=formurl;
});
</script>
@endsection
