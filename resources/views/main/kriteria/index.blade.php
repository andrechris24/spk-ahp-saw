@extends('layout')
@section('title', 'Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Kriteria</h3>
		</div>
		<section class="section">
			@include('components.message')
			<div class="modal fade text-left" id="CritModal" tabindex="-1" role="dialog"
				aria-labelledby="CritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="CritLabel">Tambah Kriteria</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" enctype="multipart/form-data" id="CritForm">
								@csrf
								<input type="hidden" name="id" id="kriteria-id">
								@if ($compkr > 0)
									<div class="alert alert-warning" id="kriteria-alert">
										Menambahkan kriteria akan mereset perbandingan kriteria.
									</div>
								@endif
								<label for="nama-krit">Nama Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama-krit"
										required />
									<div class="invalid-feedback" id="nama-error"></div>
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
									<div class="invalid-feedback" id="type-error"></div>
								</div>
								<label for="deskripsi">Keterangan</label>
								<div class="form-group">
									<input type="text" class="form-control" name="desc" id="deskripsi"
										required />
									<div class="invalid-feedback" id="desc-error"></div>
								</div>
							</form>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-secondary"
									data-bs-dismiss="modal">
									<i class="bx bx-x d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Batal</span>
								</button>
								<button type="submit" class="btn btn-primary ml-1 data-submit"
									form="CritForm">
									<i class="bx bx-check d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Simpan</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Kriteria</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
						data-bs-target="#CritModal" id="spare-button">
						<i class="bi bi-plus-lg me-0 me-sm-1"></i>
						Tambah Kriteria
					</button>
					<table class="table table-hover" id="table-crit" style="width: 100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Kriteria</th>
								<th>Atribut</th>
								<th>Keterangan</th>
								<th data-bs-toggle="tooltip"
									title="Bobot didapat setelah melakukan perbandingan">
									Bobot
								</th>
								<th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		var dt_kriteria;
		$(document).ready(function() {
			try {
				dt_kriteria = $('#table-crit').DataTable({
					"stateSave": true,
					"lengthChange": false,
					"searching": false,
					serverSide: true,
					processing: true,
					responsive: true,
					ajax: "{{ route('kriteria.data') }}",
					columns: [{
							data: 'id'
						},
						{
							data: 'name'
						},
						{
							data: 'type'
						},
						{
							data: 'desc'
						},
						{
							data: 'bobot'
						},
						{
							data: 'id'
						}
					],
					columnDefs: [{
							targets: 0,
							render: function(data, type, full,
								meta) {
								return meta.row + meta.settings
									._iDisplayStart + 1;
							}
						},
						{ //Aksi
							orderable: false,
							targets: -1,
							render: function(data, type, full) {
								return (
									'<div class="btn-group" role="group">' +
									`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#CritModal" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
									`<button class="btn btn-sm btn-danger delete-record" data-id="${data}" data-name="${full['name']}" title="Hapus"><i class="bi bi-trash3-fill"></i></button>` +
									'</div>'
								);
							}
						}
					],
					language: {
						url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
					},
					dom: 'Bfrtip',
					buttons: [{
							text: '<i class="bi bi-plus-lg me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Kriteria</span>',
							className: 'add-new btn btn-primary',
							attr: {
								'data-bs-toggle': 'modal',
								'data-bs-target': '#CritModal'
							}
						},
						{
							extend: 'collection',
							text: '<i class="bi bi-download me-0 me-sm-1"></i>Ekspor',
          		className: 'btn btn-primary dropdown-toggle',
							buttons: [{
									extend: 'print',
									title: 'Kriteria',
									text: '<i class="bi bi-printer me-2"></i>Print',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3, 4]
									}
								},
								{
									extend: 'csv',
									title: 'Kriteria',
									text: '<i class="bi bi-file-text me-2"></i>CSV',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3, 4]
									}
								},
								{
									extend: 'excel',
									title: 'Kriteria',
									text: '<i class="bi bi-file-spreadsheet me-2"></i>Excel',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3, 4]
									}
								},
								{
									extend: 'pdf',
									title: 'Kriteria',
									text: '<i class="bi bi-file-text me-2"></i>PDF',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3, 4]
									}
								},
								{
									extend: 'copy',
									title: 'Kriteria',
									text: '<i class="bi bi-clipboard me-2"></i>Copy',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3, 4]
									}
								}
							]
						}
					],
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					backgroundColor: "#dc3545"
				}).showToast();
				if (!$.fn.DataTable.isDataTable('#table-crit')) {
					$('#spare-button').removeClass('d-none');
				}
			}
			$.fn.dataTable.ext.errMode = 'none';

			dt_kriteria.on('error.dt', function(e, settings, techNote,
				message) {
				Toastify({
					text: message,
					backgroundColor: "#ffc107",
					duration: 10000
				}).showToast();
				console.warn(techNote);
			});
			dt_kriteria.on('draw', setTableColor);
		});
		// Delete Record
		$(document).on('click', '.delete-record', function() {
			var kr_id = $(this).data('id'),
				kr_name = $(this).data('name');

			Swal.fire({
				title: 'Hapus kriteria?',
				text: "Anda akan menghapus sub kriteria " + kr_name +
					". Jika sudah dilakukan perbandingan, perbandingan akan dihapus!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak',
				customClass: {
					confirmButton: 'btn btn-primary me-3',
					cancelButton: 'btn btn-label-secondary'
				},
				buttonsStyling: false
			}).then(function(result) {
				if (result.value) {
					// delete the data
					$.ajax({
						type: 'DELETE',
						url: '/kriteria/del/' + kr_id,
						data: {
							"_token": "{{ csrf_token() }}"
						},
						success: function(data) {
							dt_kriteria.draw();
							// success sweetalert
							Swal.fire({
								icon: 'success',
								title: 'Dihapus',
								text: data.message,
								customClass: {
									confirmButton: 'btn btn-success'
								}
							});
						},
						error: function(xhr, stat) {
							Swal.fire({
								icon: 'error',
								title: 'Gagal hapus',
								text: xhr
									.responseJSON
									.message ??
									stat,
								customClass: {
									confirmButton: 'btn btn-success'
								}
							});
						}
					});
				} else if (result.dismiss === Swal.DismissReason
					.cancel) {
					Swal.fire({
						title: 'Dibatalkan',
						text: 'Kriteria tidak dihapus.',
						icon: 'warning',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		});
		$('#CritForm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				data: $('#CritForm').serialize(),
				url: '/kriteria/store',
				type: 'POST',
				beforeSend: function() {
					$('#CritForm :input').prop('disabled', true);
					$('#CritForm :input').removeClass(
					'is-invalid');
					$('.data-submit').prop('disabled', true);
				},
				complete: function() {
					$('#CritForm :input').prop('disabled', false);
					$('.data-submit').prop('disabled', false);
				},
				success: function(status) {
					dt_kriteria.draw();
					$('#CritModal').modal('hide');

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
					if(xhr.responseJSON.name){
						$('#nama-krit').addClass('is-invalid');
						$('#nama-error').text(xhr.responseJSON.name);
					}
					if(xhr.responseJSON.type){
						$('#tipe-kriteria').addClass('is-invalid');
						$('#type-error').text(xhr.responseJSON.type);
					}
					if(xhr.responseJSON.desc){
						$('#deskripsi').addClass('is-invalid');
						$('#desc-error').text(xhr.responseJSON.desc);
					}
					Swal.fire({
						title: 'Gagal',
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
		// edit record
		$(document).on('click', '.edit-record', function() {
			var kr_id = $(this).data('id');
			$('#CritForm :input').prop('disabled', true);
			// changing the title of offcanvas
			$('#CritLabel').html('Edit Kriteria');
			if ($('#kriteria-alert').length)
				$('#kriteria-alert').addClass('d-none');
			// get data
			$.get('/kriteria/edit/' + kr_id, function(data) {
					$('#kriteria-id').val(data.id);
					$('#nama-krit').val(data.name);
					$('#tipe-kriteria').val(data.type);
					$('#deskripsi').val(data.desc);
				}).fail(function(xhr, status) {
					Swal.fire({
						icon: 'error',
						title: 'Kesalahan',
						text: xhr.responseJSON.message ?? status,
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				})
				.always(function() {
					$('#CritForm :input').prop('disabled', false);
				});
		});
		// clearing form data when modal hidden
		$('#CritModal').on('hidden.bs.modal', function() {
			$('#CritForm')[0].reset();
			$('#CritForm :input').removeClass('is-invalid');
			$('#CritLabel').html('Tambah Kriteria');
			if ($('#kriteria-alert').length)
				$('#kriteria-alert').removeClass('d-none');
		});
	</script>
@endsection
