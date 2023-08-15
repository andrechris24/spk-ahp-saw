@extends('layout')
@section('title', 'Alternatif')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Alternatif</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="modal fade text-left" id="DelAlterModal" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel160" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<h5 class="modal-title white" id="myModalLabel160">
								Hapus Alternatif
							</h5>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<p>
								<span id="del-desc">Anda akan menghapus sebuah alternatif.</span>
							</p>
							<p>Lanjutkan?</p>
							@if ($ceknilai > 0)
								<div class="alert alert-warning">
									Menghapus alternatif akan menghapus Penilaian yang bersangkutan.
								</div>
							@endif
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light-secondary"
								data-bs-dismiss="modal">
								<i class="bx bx-x d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Tidak</span>
							</button>
							<a href="" class="btn btn-warning ml-1" id="del-action">
								<i class="bx bx-check d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Ya</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade text-left" id="AddAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="AddAlterLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="AddAlterLabel">Tambah Alternatif</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/alternatif/add') }}" method="post"
							enctype="multipart/form-data">@csrf
							<div class="modal-body">
								<label for="name-add">Nama Alternatif</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="name-add"
										required />
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
			<div class="modal fade text-left" id="EditAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="EditAlterLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="EditAlterLabel">Edit Alternatif</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/alternatif/update/:id') }}" method="post"
							enctype="multipart/form-data" name="editalternatif">@csrf
							<div class="modal-body">
								<label for="nama-edit">Nama Alternatif</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama-edit"
										required />
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
									<span class="d-none d-sm-block">Edit</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Alternatif</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary mb-3"
						data-bs-toggle="modal" data-bs-target="#AddAlterModal">
						<i class="bi bi-plus-lg"></i>
						Tambah Alternatif
					</button>
					<table class="table table-hover" id="table-alter">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Alternatif</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@php($count = 0)
							@foreach ($alt as $alternatif)
								<tr>
									<td>{{ ++$count }}</td>
									<td>{{ $alternatif->name }}</td>
									<td>
										<div class="btn-group" role="button">
											<button type="button" class="btn btn-primary"
												data-bs-toggle="modal" data-bs-target="#EditAlterModal"
												data-bs-id="{{ $alternatif->id }}"
												data-bs-name="{{ $alternatif->name }}">
												<i class="bi bi-pencil-square"></i> Edit
											</button>
											<button type="button" class="btn btn-danger"
												data-bs-toggle="modal" data-bs-target="#DelAlterModal"
												data-bs-id="{{ $alternatif->id }}"
												data-bs-name="{{ $alternatif->name }}">
												<i class="bi bi-trash3-fill"></i> Hapus
											</button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		const editAlterModal = document.getElementById('EditAlterModal');
		editAlterModal.addEventListener('show.bs.modal', event => {
			// Button that triggered the modal
			const button = event.relatedTarget;
			// Extract info from data-bs-* attributes
			const nama = button.getAttribute('data-bs-name');
			const id = button.getAttribute('data-bs-id');
			// If necessary, you could initiate an AJAX request here
			// and then do the updating in a callback.

			// Update the modal's content.
			const nameval = editAlterModal.querySelector('#nama-edit')
			nameval.value = nama;
			var formurl = "{{ url('/alternatif/update/:id') }}";
			formurl = formurl.replace(':id', id);
			document.editalternatif.action = formurl;
		});
		const delAlterModal = document.getElementById('DelAlterModal');
		delAlterModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-bs-id');
			const nama = button.getAttribute('data-bs-name');
			const link = delAlterModal.querySelector('#del-action');
			const desc = delAlterModal.querySelector('#del-desc');
			var formurl = "{{ url('/alternatif/del/:id') }}";
			formurl = formurl.replace(':id', id);
			desc.innerHTML = "Anda akan menghapus alternatif <b>" + nama +
				"</b>.";
			link.href = formurl;
		});
		$(document).ready(function() {
			try {
				$('#table-alter').DataTable({
					"stateSave": true,
					"lengthChange": false,
					"searching": false,
					columnDefs: [{
						orderable: false,
						targets: 2,
					}],
					language: {
						url: "{{ url('assets/DataTables-id.json') }}"
					}
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 4000,
					backgroundColor: "#dc3545",
				}).showToast();
			}
		});
	</script>
@endsection
