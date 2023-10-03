@extends('layout')
@section('title', 'Alternatif')
@section('subtitle','Alternatif')
@section('content')
			<div class="modal fade text-left" id="AlterModal" tabindex="-1" role="dialog"
				aria-labelledby="AlterLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="AlterLabel">Tambah Alternatif</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" enctype="multipart/form-data" id="AlterForm">
								@csrf
								<input type="hidden" name="id" id="alter-id">
								<label for="alter-name">Nama Alternatif</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name"
										id="alter-name" required />
									<div class="invalid-feedback" id="alter-error"></div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<div class="spinner-grow text-primary d-none" role="status">
								<span class="visually-hidden">Menyimpan...</span>
							</div>
							<button type="button" class="btn btn-light-secondary"
								data-bs-dismiss="modal">
								<i class="bi bi-x d-block d-sm-none"></i>
								<span class="d-none d-sm-inline-block">Batal</span>
							</button>
							<button type="submit" class="btn btn-primary ml-1 data-submit"
								form="AlterForm">
								<i class="bi bi-check d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Simpan</span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Alternatif</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
						data-bs-target="#AlterModal" id="spare-button">
						<i class="bi bi-plus-lg me-0 me-sm-1"></i>
						Tambah Alternatif
					</button>
					<table class="table table-hover" id="table-alter" style="width: 100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Alternatif</th>
								<th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
@endsection
@section('js')
	<script type="text/javascript">
		var dt_alternatif;
		$(document).ready(function() {
			try {
				dt_alternatif = $('#table-alter').DataTable({
					"stateSave": true,
					"lengthChange": false,
					"searching": false,
					responsive: true,
					serverSide: true,
					processing: true,
					ajax: "{{ route('alternatif.data') }}",
					columns: [{
							data: 'id'
						},
						{
							data: 'name'
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
									`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#AlterModal" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
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
							text: '<i class="bi bi-plus-lg me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Alternatif</span>',
							className: 'add-new btn btn-primary',
							attr: {
								'data-bs-toggle': 'modal',
								'data-bs-target': '#AlterModal'
							}
						},
						{
							extend: 'collection',
							text: '<i class="bi bi-download me-0 me-sm-1"></i>Ekspor',
							className: 'btn btn-primary dropdown-toggle',
							buttons: [{
									extend: 'print',
									title: 'Alternatif',
									text: '<i class="bi bi-printer me-2"></i>Print',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1]
									}
								},
								{
									extend: 'csv',
									title: 'Alternatif',
									text: '<i class="bi bi-file-text me-2"></i>CSV',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1]
									}
								},
								{
									extend: 'excel',
									title: 'Alternatif',
									text: '<i class="bi bi-file-spreadsheet me-2"></i>Excel',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1]
									}
								},
								{
									extend: 'pdf',
									title: 'Alternatif',
									text: '<i class="bi bi-file-text me-2"></i>PDF',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1]
									}
								},
								{
									extend: 'copy',
									title: 'Alternatif',
									text: '<i class="bi bi-clipboard me-2"></i>Copy',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1]
									}
								}
							]
						}
					],
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 7000,
					backgroundColor: "#dc3545"
				}).showToast();
				if (!$.fn.DataTable.isDataTable('#table-alter'))
					$('#spare-button').removeClass('d-none');
			}
			$.fn.dataTable.ext.errMode = 'none';

			dt_alternatif.on('error.dt', function(e, settings, techNote,
				message) {
				Toastify({
					text: message,
					backgroundColor: "#ffc107",
					duration: 10000
				}).showToast();
				console.warn(techNote);
			});
			dt_alternatif.on('draw', setTableColor);
		});
		// Delete Record
		$(document).on('click', '.delete-record', function() {
			var alt_id = $(this).data('id'),
				alt_name = $(this).data('name');

			Swal.fire({
				title: 'Hapus alternatif?',
				text: "Anda akan menghapus alternatif " + alt_name +
					". Jika sudah dilakukan penilaian, penilaian terkait akan dihapus!",
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
						url: '/alternatif/del/' + alt_id,
						data: {
							"_token": '{{ csrf_token() }}'
						},
						success: function() {
							dt_alternatif.draw();
							Swal.fire({
								icon: 'success',
								title: 'Dihapus',
								text: 'Alternatif ' +
									alt_name +
									' sudah dihapus.',
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
						text: 'Alternatif tidak dihapus.',
						icon: 'warning',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		});
		$('#AlterForm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				data: $('#AlterForm').serialize(),
				url: $('#alter-id').val() == '' ?
					'/alternatif/update' : '/alternatif/store',
				type: 'POST',
				beforeSend: function() {
					$('#AlterForm :input').prop('disabled', true);
					$('#AlterForm :input').removeClass(
						'is-invalid');
					$('.data-submit').prop('disabled', true);
					$('.spinner-grow').removeClass('d-none');
				},
				complete: function() {
					$('#AlterForm :input').prop('disabled', false);
					$('.data-submit').prop('disabled', false);
					$('.spinner-grow').addClass('d-none');
				},
				success: function(status) {
					dt_alternatif.draw();
					$('#AlterModal').modal('hide');
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
						$('#alter-name').addClass('is-invalid');
						$('#alter-error').text(xhr.responseJSON
							.name);
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
			var alt_id = $(this).data('id');

			// changing the title of offcanvas
			$('#AlterLabel').html('Edit Alternatif');
			$('#AlterForm :input').prop('disabled', true);
			$('.data-submit').prop('disabled', true);
			$('.spinner-grow').removeClass('d-none');

			// get data
			$.get('/alternatif/edit/' + alt_id, function(data) {
				$('#alter-id').val(data.id);
				$('#alter-name').val(data.name);
			}).fail(function(xhr, status) {
				Swal.fire({
					icon: 'error',
					title: 'Kesalahan',
					text: xhr.responseJSON.message ?? status,
					customClass: {
						confirmButton: 'btn btn-success'
					}
				});
			}).always(function() {
				$('#AlterForm :input').prop('disabled', false);
				$('.data-submit').prop('disabled', false);
				$('.spinner-grow').addClass('d-none');
			});
		});
		// clearing form data when modal hidden
		$('#AlterModal').on('hidden.bs.modal', function() {
			$('#AlterForm')[0].reset();
			$('#AlterForm :input').removeClass('is-invalid');
			$('#AlterLabel').html('Tambah Alternatif');
		});
	</script>
@endsection
