@extends('layout')
@section('title', 'Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Kriteria</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="modal fade text-left" id="DelCritModal" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel160" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<h5 class="modal-title white" id="myModalLabel160">
								Hapus Kriteria
							</h5>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<p id="del-desc">
								Anda akan menghapus sebuah kriteria.
							</p>
							<p>Lanjutkan?</p>
							<div class="alert alert-warning">
								@if ($compkr > 0 && $ceknilai > 0)
									Data sub kriteria yang berkaitan juga akan dihapus,
									begitu juga dengan penilaian alternatif.
									Perbandingan kriteria juga akan direset.
								@elseif ($compkr > 0)
									Data sub kriteria yang berkaitan juga akan dihapus,
									serta mereset perbandingan kriteria.
								@else
									Data sub kriteria yang berkaitan juga akan dihapus.
								@endif
							</div>
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
						<form action="{{ url('/kriteria/add') }}" method="post"
							enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								@if ($compkr > 0)
									<div class="alert alert-warning">
										Menambahkan kriteria akan mereset perbandingan kriteria.
									</div>
								@endif
								<label for="nama">Nama Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama"
										required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="tipe-kriteria">
										Atribut
									</label>
									<select class="form-select" id="tipe-kriteria" name="type" required>
										<option value="">Pilih</option>
										<option value="cost">Cost</option>
										<option value="benefit">Benefit</option>
									</select>
								</div>
								<label for="deskripsi">Keterangan</label>
								<div class="form-group">
									<input type="text" class="form-control" name="desc" id="deskripsi"
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
						<form action="{{ url('/kriteria/update/:id') }}" method="post"
							enctype="multipart/form-data" name="editkriteria">
							@csrf
							<div class="modal-body">
								<label for="nama-edit">Nama Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name"
										id="nama-edit" required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="tipe-kriteria-edit">
										Atribut
									</label>
									<select class="form-select" id="tipe-kriteria-edit" name="type"
										required>
										<option value="">Pilih</option>
										<option value="cost">Cost</option>
										<option value="benefit">Benefit</option>
									</select>
								</div>
								<label for="deskripsi-edit">Keterangan</label>
								<div class="form-group">
									<input type="text" class="form-control" name="desc"
										id="deskripsi-edit" required />
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
				<div class="card-header">Daftar Kriteria</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary mb-3"
						data-bs-toggle="modal" data-bs-target="#AddCritModal">
						<i class="bi bi-plus-lg"></i>
						Tambah Kriteria
					</button>
					<table class="table table-hover" id="table-crit">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Kriteria</th>
								<th>Atribut</th>
								<th>Keterangan</th>
								<th data-bs-toggle="tooltip"
									data-bs-title="Bobot didapat setelah melakukan perbandingan">
									Bobot
								</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@php($count = 0)
							@foreach ($krit as $kriteria)
								<tr>
									<td>{{ ++$count }}</td>
									<td>{{ $kriteria->name }}</td>
									<td>{{ $kriteria->type }}</td>
									<td>{{ $kriteria->desc }}</td>
									<td>{{ $kriteria->bobot }}</td>
									<td>
										<div class="btn-group" role="button">
											<button type="button" class="btn btn-primary"
												data-bs-toggle="modal" data-bs-target="#EditCritModal"
												data-bs-id="{{ $kriteria->id }}"
												data-bs-desc="{{ $kriteria->desc }}"
												data-bs-name="{{ $kriteria->name }}"
												data-bs-type="{{ $kriteria->type }}">
												<i class="bi bi-pencil-square"></i> Edit
											</button>
											<button type="button" class="btn btn-danger"
												data-bs-toggle="modal" data-bs-target="#DelCritModal"
												data-bs-id="{{ $kriteria->id }}"
												data-bs-name="{{ $kriteria->name }}">
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
		const editCriteriaModal = document.getElementById('EditCritModal');
		editCriteriaModal.addEventListener('show.bs.modal', event => {
			// Button that triggered the modal
			const button = event.relatedTarget;
			// Extract info from data-bs-* attributes
			const nama = button.getAttribute('data-bs-name');
			const tipe = button.getAttribute('data-bs-type');
			const desc = button.getAttribute('data-bs-desc');
			const id = button.getAttribute('data-bs-id');
			// If necessary, you could initiate an AJAX request here
			// and then do the updating in a callback.

			// Update the modal's content.
			const nameval = editCriteriaModal.querySelector('#nama-edit')
			const descval = editCriteriaModal.querySelector('#deskripsi-edit')
			const typeval = editCriteriaModal.querySelector(
				'#tipe-kriteria-edit')
			nameval.value = nama;
			typeval.value = tipe;
			descval.value = desc
			var formurl = "{{ url('/kriteria/update/:id') }}";
			formurl = formurl.replace(':id', id);
			document.editkriteria.action = formurl;
		});
		const delCriteriaModal = document.getElementById('DelCritModal');
		delCriteriaModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-bs-id');
			const nama = button.getAttribute('data-bs-name');
			const link = delCriteriaModal.querySelector('#del-action');
			const desc = delCriteriaModal.querySelector('#del-desc');
			var formurl = "{{ url('/kriteria/del/:id') }}";
			formurl = formurl.replace(':id', id);
			desc.innerHTML = "Anda akan menghapus kriteria <b>" + nama +
				"</b>.";
			link.href = formurl;
		});
		$(document).ready(function() {
			$('#table-crit').DataTable({
				"stateSave": true,
				"lengthChange": false,
				"searching": false,
				columnDefs: [{
					orderable: false,
					targets: 4,
				}],
				language: {
					url: "{{ url('assets/DataTables-id.json') }}"
				}
			});
		});
	</script>
@endsection
