@extends('layout')
@section('title', 'Kriteria')
@section('subtitle', 'Kriteria')
@section('content')
	<div class="modal fade text-left" id="CritModal" tabindex="-1" role="dialog"
		aria-labelledby="CritLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="CritLabel">Tambah Kriteria</h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<i data-feather="x"></i>
					</button>
				</div>
				<div class="modal-body">
					<form method="POST" enctype="multipart/form-data" id="CritForm"
						class="needs-validation">
						<input type="hidden" name="id" id="kriteria-id">
						@if ($compkr > 0)
							<div class="alert alert-warning" id="kriteria-alert">
								Menambahkan kriteria akan mereset perbandingan kriteria.
							</div>
						@endif
						<label for="nama-krit">Nama Kriteria</label>
						<div class="form-group">
							<input type="text" class="form-control" name="name" id="nama-krit" required />
							<div class="invalid-feedback" id="nama-error">
								Masukkan Nama Kriteria
							</div>
						</div>
						<div class="input-group has-validation mb-3">
							<label class="input-group-text" for="tipe-kriteria">
								Atribut
							</label>
							<select class="form-select" id="tipe-kriteria" name="type" required>
								<option value="">Pilih</option>
								<option value="cost">Cost</option>
								<option value="benefit">Benefit</option>
							</select>
							<div class="invalid-feedback" id="type-error">
								Pilih salah satu Atribut
							</div>
						</div>
						<label for="deskripsi">Keterangan</label>
						<div class="form-group">
							<input type="text" class="form-control" name="desc" id="deskripsi" required />
							<div class="invalid-feedback" id="desc-error">
								Masukkan keterangan
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div class="spinner-grow text-primary d-none" role="status">
						<span class="visually-hidden">Menyimpan...</span>
					</div>
					<button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Batal</span>
					</button>
					<button type="submit" class="btn btn-primary ml-1 data-submit" form="CritForm">
						<i class="bi bi-check d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Simpan</span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-start justify-content-between">
						<div class="content-left">
							<span>Jumlah</span>
							<div class="d-flex align-items-end mt-2">
								<h3 class="mb-0 me-2"><span id="total-counter">-</span></h3>
							</div>
						</div>
						<span class="badge bg-primary rounded p-2">
							<i class="fas fa-list"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-start justify-content-between">
						<div class="content-left">
							<span>Duplikat</span>
							<div class="d-flex align-items-end mt-2">
								<h3 class="mb-0 me-2"><span id="total-duplicate">-</span></h3>
							</div>
						</div>
						<span class="badge bg-warning rounded p-2">
							<i class="bi bi-exclamation-circle-fill"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">Daftar Kriteria</div>
		<div class="card-body">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#CritModal" id="spare-button">
				<i class="bi bi-plus-lg"></i> Tambah Kriteria
			</button>
			<div class="spinner-grow text-danger d-none" role="status">
				<span class="visually-hidden">Menghapus...</span>
			</div>
			<table class="table table-hover table-striped" id="table-crit" style="width: 100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Kriteria</th>
						<th>Atribut</th>
						<th>Keterangan</th>
						<th data-bs-toggle="tooltip" title="Bobot didapat melalui perhitungan AHP">
							Bobot
						</th>
						<th>Aksi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		var dt_kriteria;
		$(document).ready(function() {
			try {
				$.fn.dataTable.ext.errMode = 'none';
				dt_kriteria = $('#table-crit').DataTable({
					stateSave: true,
					lengthChange: false,
					searching: false,
					serverSide: true,
					processing: true,
					responsive: true,
					ajax: {
						url: "{{ route('kriteria.data') }}",
						type: 'POST'
					},
					columns: [{
						data: 'id'
					}, {
						data: 'name'
					}, {
						data: 'type'
					}, {
						data: 'desc'
					}, {
						data: 'bobot'
					}, {
						data: 'id'
					}],
					columnDefs: [{
						targets: 0,
						render: function(data, type, full, meta) {
							return meta.row + meta.settings
								._iDisplayStart + 1;
						}
					}, { //Keterangan
						targets: 3,
						render: function(data, type) {
							return type === 'display' && data
								.length > 60 ?
								'<span title="' + data + '">' + data
								.substr(0, 58) + '...</span>' :
								data;
						}
					}, { //Aksi
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
					}],
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
					}, {
						extend: 'collection',
						text: '<i class="bi bi-download me-0 me-sm-1"></i> Ekspor',
						className: 'btn btn-primary dropdown-toggle',
						buttons: [{
							extend: 'print',
							title: 'Kriteria',
							text: '<i class="bi bi-printer me-2"></i> Print',
							className: 'dropdown-item',
							exportOptions: {
								columns: [1, 2, 3, 4]
							}
						}, {
							extend: 'csv',
							title: 'Kriteria',
							text: '<i class="bi bi-file-text me-2"></i> CSV',
							className: 'dropdown-item',
							exportOptions: {
								columns: [1, 2, 3, 4]
							}
						}, {
							extend: 'excel',
							title: 'Kriteria',
							text: '<i class="bi bi-file-spreadsheet me-2"></i> Excel',
							className: 'dropdown-item',
							exportOptions: {
								columns: [1, 2, 3, 4]
							}
						}, {
							extend: 'pdf',
							title: 'Kriteria',
							text: '<i class="bi bi-file-text me-2"></i> PDF',
							className: 'dropdown-item',
							exportOptions: {
								columns: [1, 2, 3, 4]
							}
						}, {
							extend: 'copy',
							title: 'Kriteria',
							text: '<i class="bi bi-clipboard me-2"></i> Copy',
							className: 'dropdown-item',
							exportOptions: {
								columns: [1, 2, 3, 4]
							}
						}]
					}],
				}).on('error.dt', function(e, settings, techNote, message) {
					errorDT(message, techNote);
				}).on('draw', setTableColor).on('preDraw', function() {
					$.get("{{ route('kriteria.count') }}", function(data) {
						$("#total-duplicate").text(data.duplicates);
						$("#total-counter").text(data.total);
					}).fail(function(xhr, status) {
						Toastify({
							text: "Gagal memuat jumlah: Kesalahan HTTP " +
								xhr.status + '. ' + (xhr
									.statusText ?? status),
							style: {
								background: "#dc3545"
							},
							duration: 9000
						}).showToast();
					});
				}).on('preInit.dt', removeBtn());
			} catch (dterr) {
				initError(dterr.message);
			}
		}).on('click', '.delete-record', function() {
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
					cancelButton: 'btn btn-secondary'
				},
				buttonsStyling: false
			}).then(function(result) {
				if (result.value) { // delete the data
					$.ajax({
						type: 'DELETE',
						url: '/kriteria/del/' + kr_id,
						beforeSend: function() {
							$('.spinner-grow.text-danger')
								.removeClass('d-none');
						},
						complete: function() {
							$('.spinner-grow.text-danger')
								.addClass('d-none');
						},
						success: function(data) {
							dt_kriteria.draw();
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
							if (xhr.status === 404)
								dt_kriteria.draw();
							Swal.fire({
								icon: 'error',
								title: 'Gagal hapus',
								text: 'Kesalahan HTTP ' + xhr
									.status + '. ' + (xhr
										.responseJSON
										.message ?? stat),
								customClass: {
									confirmButton: 'btn btn-success'
								}
							});
						}
					});
				} else if (result.dismiss === Swal.DismissReason.cancel) {
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
		}).on('click', '.edit-record', function() {
			var kr_id = $(this).data('id');

			// changing the title of offcanvas
			$('#CritForm :input').prop('disabled', true);
			$('#CritLabel').html('Edit Kriteria');
			$('.data-submit').prop('disabled', true);
			$('.spinner-grow.text-primary').removeClass('d-none');
			if ($('#kriteria-alert').length)
				$('#kriteria-alert').addClass('d-none');
			// get data
			$.get('/kriteria/edit/' + kr_id, function(data) {
				$('#kriteria-id').val(data.id);
				$('#nama-krit').val(data.name);
				$('#tipe-kriteria').val(data.type);
				$('#deskripsi').val(data.desc);
			}).fail(function(xhr, status) {
				if (xhr.status === 404)
					dt_kriteria.draw();
				Swal.fire({
					icon: 'error',
					title: 'Kesalahan',
					text: 'Kesalahan HTTP ' + xhr.status + '. ' +
						(xhr.responseJSON.message ?? status),
					customClass: {
						confirmButton: 'btn btn-success'
					}
				});
			}).always(function() {
				$('#CritForm :input').prop('disabled', false);
				$('.data-submit').prop('disabled', false);
				$('.spinner-grow.text-primary').addClass('d-none');
			});
		});

		function submitform(event) {
			var errmsg, actionurl = $('#kriteria-id').val() == '' ?
				"{{ route('kriteria.store') }}" : "{{ route('kriteria.update') }}";
			event.preventDefault();
			$.ajax({
				data: $('#CritForm').serialize(),
				url: actionurl,
				type: 'POST',
				beforeSend: function() {
					$('#CritForm :input').prop('disabled', true)
						.removeClass('is-invalid');
					$('.data-submit').prop('disabled', true);
					$('.spinner-grow.text-primary').removeClass('d-none');
				},
				complete: function() {
					$('#CritForm :input').prop('disabled', false);
					$('.data-submit').prop('disabled', false);
					$('.spinner-grow.text-primary').addClass('d-none');
				},
				success: function(status) {
					if ($.fn.DataTable.isDataTable("#table-crit"))
						dt_kriteria.draw();
					$('#CritModal').modal('hide');
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
							$('#nama-krit').addClass('is-invalid');
							$('#nama-error')
								.text(xhr.responseJSON.errors.name);
						}
						if (typeof xhr.responseJSON.errors.type !==
							"undefined") {
							$('#tipe-kriteria').addClass('is-invalid');
							$('#type-error')
								.text(xhr.responseJSON.errors.type);
						}
						if (typeof xhr.responseJSON.errors.desc !==
							"undefined") {
							$('#deskripsi').addClass('is-invalid');
							$('#desc-error')
								.text(xhr.responseJSON.errors.desc);
						}
						errmsg = xhr.responseJSON.message ?? code;
					} else {
						errmsg = 'Kesalahan HTTP ' + xhr.status + '. ' +
							(xhr.responseJSON.message ?? code);
					}
					Swal.fire({
						title: 'Gagal',
						text: errmsg,
						icon: 'error',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		};
		// clearing form data when modal hidden
		$('#CritModal').on('hidden.bs.modal', function() {
			resetvalidation();
			$('#kriteria-id').val('');
			$('#CritForm')[0].reset();
			$('#CritLabel').html('Tambah Kriteria');
			if ($('#kriteria-alert').length)
				$('#kriteria-alert').removeClass('d-none');
		});
	</script>
@endsection
