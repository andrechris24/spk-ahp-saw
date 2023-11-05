@extends('layout')
@section('title', 'Perhitungan SAW');
@section('subtitle', 'Nilai Alternatif')
@section('content')
	<div class="modal fade text-left" id="NilaiAlterModal" tabindex="-1" role="dialog"
		aria-labelledby="NilaiAlterLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
			role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="NilaiAlterLabel">
						Tambah Nilai Alternatif
					</h4>
					<button type="button" class="close" data-bs-dismiss="modal"
						aria-label="Close">
						<i data-feather="x"></i>
					</button>
				</div>
				<div class="modal-body">
					<form method="POST" enctype="multipart/form-data" id="NilaiAlterForm">
						<input type="hidden" name="alternatif_id" id="alternatif-hidden">
						<div class="input-group mb-3">
							<label class="input-group-text" for="alternatif-value">
								Nama Alternatif
							</label>
							<select class="form-select" id="alternatif-value" name="alternatif_id"
								required>
								<option value="">Pilih</option>
								@foreach ($data['alternatif'] as $alt)
									<option value="{{ $alt->id }}">{{ $alt->name }}</option>
								@endforeach
							</select>
							<div class="invalid-feedback" id="alternatif-error"></div>
						</div>
						@foreach ($data['kriteria'] as $kr)
							<input type="hidden" name="kriteria_id[]" value="{{ $kr->id }}">
							<div class="input-group mb-3">
								<label class="input-group-text" for="subkriteria-{{ $kr->id }}"
									title="{{ $kr->desc }}">
									{{ $kr->name }}
								</label>
								<select class="form-select" id="subkriteria-{{ $kr->id }}"
									name="subkriteria_id[]" required>
									<option value="">Pilih</option>
									@foreach ($data['subkriteria'] as $subkr)
										@if ($subkr->kriteria_id == $kr->id)
											<option value="{{ $subkr->id }}">
												{{ $subkr->name }}
											</option>
										@endif
									@endforeach
								</select>
								<div class="invalid-feedback" id="subkriteria-error-{{ $kr->id }}">
								</div>
							</div>
						@endforeach
					</form>
				</div>
				<div class="modal-footer">
					<div class="spinner-grow text-primary d-none" role="status">
						<span class="visually-hidden">Menyimpan...</span>
					</div>
					<button type="button" class="btn btn-light-secondary"
						data-bs-dismiss="modal">
						<i class="bi bi-x d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Batal</span>
					</button>
					<button type="submit" class="btn btn-primary ml-1 data-submit"
						form="NilaiAlterForm">
						<i class="bi bi-check d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Simpan</span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">Daftar Nilai Alternatif</div>
		<div class="card-body">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#NilaiAlterModal" id="spare-button">
				<i class="bi bi-plus-lg me-0 me-sm-1"></i> Tambah Nilai Alternatif
			</button>
			<table class="table table-hover table-striped" id="table-nilaialt"
				style="width: 100%">
				<thead>
					<tr>
						<th>Nama Alternatif</th>
						@foreach ($data['kriteria'] as $kr)
							<th data-bs-toggle="tooltip" data-bs-placement="bottom"
								title="{{ $kr->desc }}">
								{{ $kr->name }}
							</th>
						@endforeach
						<th>Aksi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		var nilaialtdt;
		$(document).ready(function() {
			try {
				$.fn.dataTable.ext.errMode = 'none';
				nilaialtdt = $('#table-nilaialt').DataTable({
					"lengthChange": false,
					"searching": false,
					responsive: true,
					serverSide: true,
					processing: true,
					ajax: {
						url: "{{ route('nilai.data') }}",
						type: 'POST'
					},
					columnDefs: [
						@foreach ($data['kriteria'] as $kr)
							{
								orderable: false,
								targets: 1 + {{ $loop->index }},
								render: function(data, type,
									full) {
									if (data === null ||
										data === "") {
										$('#alternatif-' +
											full['id'] +
											' .edit-record'
										).prop(
											'disabled',
											true);
										$('#alternatif-' +
											full['id'] +
											' .delete-record'
										).prop(
											'disabled',
											true);
									}
									return data;
								}
							},
						@endforeach {
							orderable: false,
							targets: -1,
							render: function(data, type, full) {
								return (
									'<div class="btn-group" role="group" id="alternatif-' +
									data + '">' +
									`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#NilaiAlterModal" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
									`<button class="btn btn-sm btn-danger delete-record" data-id="${data}" data-name="${full['name']}" title="Hapus"><i class="bi bi-trash3-fill"></i></button>` +
									'</div>'
								);
							}
						}
					],
					columns: [{
							data: "name"
						},
						@foreach ($data['kriteria'] as $kr)
							{
								title: "{{ $kr->name }}",
								data: "subkriteria.{{ Str::of($kr->name)->slug('-') }}"
							},
						@endforeach {
							data: "id"
						}
					],
					language: {
						url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
					},
					dom: 'Bfrtip',
					buttons: [{
						text: '<i class="bi bi-plus-lg me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Nilai Alternatif</span>',
						className: 'add-new btn btn-primary',
						attr: {
							'data-bs-toggle': 'modal',
							'data-bs-target': '#NilaiAlterModal'
						}
					}, {
						extend: 'collection',
						text: '<i class="bi bi-download me-0 me-sm-1"></i> Ekspor',
						className: 'btn btn-primary dropdown-toggle',
						buttons: [{
							extend: 'print',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-printer me-2"></i> Print',
							className: 'dropdown-item',
							exportOptions: {
								columns: 'th:not(:last-child)'
							}
						}, {
							extend: 'csv',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-text me-2"></i> CSV',
							className: 'dropdown-item',
							exportOptions: {
								columns: 'th:not(:last-child)'
							}
						}, {
							extend: 'excel',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-spreadsheet me-2"></i> Excel',
							className: 'dropdown-item',
							exportOptions: {
								columns: 'th:not(:last-child)'
							}
						}, {
							extend: 'pdf',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-text me-2"></i> PDF',
							className: 'dropdown-item',
							exportOptions: {
								columns: 'th:not(:last-child)'
							}
						}, {
							extend: 'copy',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-clipboard me-2"></i> Copy',
							className: 'dropdown-item',
							exportOptions: {
								columns: 'th:not(:last-child)'
							}
						}]
					}]
				}).on('draw', setTableColor).on('preInit.dt', function() {
					$('#spare-button').addClass('d-none');
				}).on('error.dt', function(e, settings, techNote,
					message) {
					Toastify({
						text: message,
						style: {
							background: "#ffc107"
						},
						duration: 10000
					}).showToast();
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 8000,
					style: {
						background: "#dc3545"
					}
				}).showToast();
			}
		});
		$(document).on('click', '.delete-record', function() {
			var score_id = $(this).data('id'),
				score_name = $(this).data('name');
			Swal.fire({
				title: 'Hapus nilai alternatif?',
				text: "Anda akan menghapus nilai alternatif " +
					score_name + ".",
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
						url: '/alternatif/nilai/del/' +
							score_id,
						success: function(data) {
							nilaialtdt.draw();
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
								nilaialtdt.draw();
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
						text: 'Nilai Alternatif tidak dihapus.',
						icon: 'warning',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		}).on('click', '.edit-record', function() {
			var nilai_id = $(this).data('id');

			// changing the title of offcanvas
			$('#NilaiAlterLabel').html('Edit Nilai Alternatif');
			$('#NilaiAlterForm :input').prop('disabled', true);
			$('.data-submit').prop('disabled', true);
			$('.spinner-grow').removeClass('d-none');

			// get data
			$.get('/alternatif/nilai/edit/' + nilai_id, function(data) {
				$('#alternatif-value').val(data.alternatif_id);
				$('#alternatif-hidden').val(data.alternatif_id);
				@foreach ($data['kriteria'] as $kr)
					$("#subkriteria-{{ $kr->id }}").val(data
						.subkriteria
						.{{ Str::of($kr->name)->slug('_') }});
				@endforeach
			}).fail(function(xhr, status) {
				if (xhr.status === 404) nilaialtdt.draw();
				Swal.fire({
					icon: 'error',
					title: 'Kesalahan',
					text: xhr.responseJSON.message ?? status,
					customClass: {
						confirmButton: 'btn btn-success'
					}
				});
			}).always(function() {
				$('#NilaiAlterForm :input').prop('disabled', false);
				$('.data-submit').prop('disabled', false);
				$('.spinner-grow').addClass('d-none');
				$('#alternatif-value').prop('disabled', true);
			});
		});
		$('#NilaiAlterForm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				data: $('#NilaiAlterForm').serialize(),
				url: $('#alternatif-hidden').val() == '' ?
					'/alternatif/nilai/store' :
					'/alternatif/nilai/update',
				type: 'POST',
				beforeSend: function() {
					$('#NilaiAlterForm :input').prop('disabled',
						true);
					$('#NilaiAlterForm :input').removeClass(
						'is-invalid');
					$('.data-submit').prop('disabled', true);
					$('.spinner-grow').removeClass('d-none');
				},
				complete: function() {
					$('#NilaiAlterForm :input').prop('disabled',
						false);
					$('.data-submit').prop('disabled', false);
					$('.spinner-grow').addClass('d-none');
				},
				success: function(status) {
					$('#NilaiAlterModal').modal('hide');
					nilaialtdt.draw();
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
		// clearing form data when modal hidden
		$('#NilaiAlterModal').on('hidden.bs.modal', function() {
			$('#alternatif-value').prop('disabled', false);
			$('#NilaiAlterForm')[0].reset();
			$('#alternatif-hidden').val("");
			$('#NilaiAlterLabel').html('Tambah Nilai Alternatif');
		});
	</script>
@endsection
