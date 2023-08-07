@extends('layout')
@section('title', 'Sub Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Sub Kriteria</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="modal fade text-left" id="DelSubCritModal" tabindex="-1"
				role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<h5 class="modal-title white" id="myModalLabel160">
								Hapus Sub Kriteria
							</h5>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<p id="del-desc">Anda akan menghapus sebuah sub kriteria.</p>
							<p>Lanjutkan?</p>
							@if ($compskr > 0)
								<div class="alert alert-warning">
									Menghapus sub kriteria akan mereset perbandingan sub kriteria
									<b><span id="warning-msg">...</span></b>
									jika perbandingan sudah dilakukan.
									@if ($ceknilai > 0)
										Penilaian alternatif yang berkaitan juga akan dihapus.
									@endif
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
			<div class="modal fade text-left" id="AddSubCritModal" tabindex="-1"
				role="dialog" aria-labelledby="AddCritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="AddSubCritLabel">Tambah Sub Kriteria</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/kriteria/sub/add') }}" method="post"
							enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<label for="nama">Nama Sub Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama"
										required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="kriteria">
										Kriteria
									</label>
									<select class="form-select" id="kriteria" name="kriteria_id" required>
										<option value="">Pilih</option>
										@foreach ($kriteria as $kr)
											<option value="{{ $kr->id }}">{{ $kr->name }}</option>
										@endforeach
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
			<div class="modal fade text-left" id="EditSubCritModal" tabindex="-1"
				role="dialog" aria-labelledby="EditSubCritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="EditSubCritLabel">Edit Sub Kriteria</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/kriteria/sub/update/:id') }}" method="post"
							enctype="multipart/form-data" name="editsubkriteria">@csrf
							<div class="modal-body">
								<label for="nama-edit">Nama Sub Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name"
										id="nama-edit" required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="kriteria-edit">
										Kriteria
									</label>
									<select class="form-select" id="kriteria-edit" name="kriteria_id"
										required>
										<option value="">Pilih</option>
										@foreach ($kriteria as $kr)
											<option value="{{ $kr->id }}">{{ $kr->name }}</option>
										@endforeach
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
									<span class="d-none d-sm-block">Edit</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Sub Kriteria</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary mb-3"
						data-bs-toggle="modal" data-bs-target="#AddSubCritModal">
						<i class="bi bi-plus-lg"></i>
						Tambah Sub Kriteria
					</button>
					<table class="table table-hover" id="table-subcrit">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Sub Kriteria</th>
								<th>Kriteria</th>
								<th data-bs-toggle="tooltip"
									data-bs-title="Bobot didapat setelah melakukan perbandingan">
									Bobot
								</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@php($count = 0)
							@foreach ($subkriteria as $sk)
								<tr>
									<td>{{ ++$count }}</td>
									<td>{{ $sk->name }}</td>
									<td title="{{ $sk->kriteria->desc }}">
										{{ $sk->kriteria->name }}
									</td>
									<td>{{ $sk->bobot }}</td>
									<td>
										<div class="btn-group" role="button">
											<button type="button" class="btn btn-primary"
												data-bs-toggle="modal" data-bs-target="#EditSubCritModal"
												data-bs-id="{{ $sk->id }}"
												data-bs-name="{{ $sk->name }}"
												data-bs-kr="{{ $sk->kriteria->id }}">
												<i class="bi bi-pencil-square"></i> Edit
											</button>
											<button type="button" class="btn btn-danger"
												data-bs-toggle="modal" data-bs-target="#DelSubCritModal"
												data-bs-id="{{ $sk->id }}"
												data-bs-name="{{ $sk->name }}"
												data-bs-kr="{{ $sk->kriteria->name }}">
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
		const editCriteriaModal = document.getElementById('EditSubCritModal');
		editCriteriaModal.addEventListener('show.bs.modal', event => {
			// Button that triggered the modal
			const button = event.relatedTarget;
			// Extract info from data-bs-* attributes
			const nama = button.getAttribute('data-bs-name');
			const idk = button.getAttribute('data-bs-kr');
			const id = button.getAttribute('data-bs-id');
			// If necessary, you could initiate an AJAX request here
			// and then do the updating in a callback.

			// Update the modal's content.
			const nameval = editCriteriaModal.querySelector('#nama-edit')
			const typeval = editCriteriaModal.querySelector('#kriteria-edit')
			nameval.value = nama;
			typeval.value = idk;
			var formurl = "{{ url('/kriteria/sub/update/:id') }}";
			formurl = formurl.replace(':id', id);
			document.editsubkriteria.action = formurl;
		});
		const delCriteriaModal = document.getElementById('DelSubCritModal');
		delCriteriaModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-bs-id');
			const nama = button.getAttribute('data-bs-name');
			const krnama = button.getAttribute('data-bs-kr');
			const link = delCriteriaModal.querySelector('#del-action');
			const desc = delCriteriaModal.querySelector('#del-desc');
			const warnmsg = delCriteriaModal.querySelector('#warning-msg');
			var formurl = "{{ url('/kriteria/sub/del/:id') }}";
			formurl = formurl.replace(':id', id);
			desc.innerHTML =
				"Anda akan menghapus sub kriteria <b>" +
				nama + "</b> (Kriteria " + krnama + ").";
			warnmsg.textContent = krnama;
			link.href = formurl;
		});
		$(document).ready(function() {
			try{
				$('#table-subcrit').DataTable({
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
			}catch(dterr){
				Toastify({
			    text: "DataTables Error: "+dterr.message,
			    duration: 4000,
			    backgroundColor: "#dc3545",
			  }).showToast();
			}
		});
	</script>
@endsection
