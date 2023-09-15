@extends('layout')
@section('title', 'Sub Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Sub Kriteria</h3>
		</div>
		<section class="section">
			@include('components.error-multi')
			@include('components.warning')
			@include('components.success')
			@include('components.noscript')
			<div class="modal fade text-left" id="SubCritModal" tabindex="-1" role="dialog"
				aria-labelledby="SubCritLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="SubCritLabel">
								Tambah Sub Kriteria
							</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<form action="{{ url('/kriteria/sub/store') }}" method="post"
								enctype="multipart/form-data" id="SubCritForm">@csrf
								<input type="hidden" name="id" id="subkriteria-id">
								@if ($compskr > 0)
									<div class="alert alert-warning" id="subkriteria-alert">
										Menambahkan sub kriteria akan mereset perbandingan sub kriteria terkait.
									</div>
								@endif
								<label for="nama">Nama Sub Kriteria</label>
								<div class="form-group">
									<input type="text" class="form-control" name="name" id="nama-sub"
										required />
								</div>
								<div class="input-group mb-3">
									<label class="input-group-text" for="kriteria">
										Kriteria
									</label>
									<select class="form-select" id="kriteria-select" name="kriteria_id"
										required>
										<option value="">Pilih</option>
										@foreach ($kriteria as $kr)
											<option value="{{ $kr->id }}">{{ $kr->name }}</option>
										@endforeach
									</select>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light-secondary"
								data-bs-dismiss="modal">
								<i class="bx bx-x d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Batal</span>
							</button>
							<button type="submit" class="btn btn-primary ml-1 data-submit"
								form="SubCritForm">
								<i class="bx bx-check d-block d-sm-none"></i>
								<span class="d-none d-sm-block">Tambah</span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">Daftar Sub Kriteria</div>
				<div class="card-body">
					<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
						data-bs-target="#SubCritModal" id="spare-button">
						<i class="bi bi-plus-lg me-0 me-sm-1"></i>
						Tambah Sub Kriteria
					</button>
					<table class="table table-hover" id="table-subcrit" style="width: 100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Sub Kriteria</th>
								<th>Kriteria</th>
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
		var dt_subkriteria;
		$(document).ready(function() {
			try {
				dt_subkriteria = $('#table-subcrit').DataTable({
					"stateSave": true,
					"lengthChange": false,
					"searching": false,
					serverSide: true,
					processing: true,
					responsive: true,
					ajax: "{{ route('subkriteria.data') }}",
					columns: [{
							data: 'id'
						},
						{
							data: 'name'
						},
						{
							data: 'kriteria_id'
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
									`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#SubCritModal"><i class="bi bi-pencil-square"></i></button>` +
									`<button class="btn btn-sm btn-danger delete-record" data-id="${data}" data-name="${full['name']}"><i class="bi bi-trash3-fill"></i></button>` +
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
							text: '<i class="bi bi-plus-lg me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Sub Kriteria</span>',
							className: 'add-new btn btn-primary',
							attr: {
								'data-bs-toggle': 'modal',
								'data-bs-target': '#SubCritModal'
							}
						},
						{
							extend: 'collection',
							text: 'Ekspor',
							buttons: [{
									extend: 'print',
									title: 'Sub Kriteria',
									text: '<i class="bi bi-printer me-2"></i>Print',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3]
									}
								},
								{
									extend: 'csv',
									title: 'Sub Kriteria',
									text: '<i class="bi bi-file-text me-2"></i>CSV',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3]
									}
								},
								{
									extend: 'excel',
									title: 'Sub Kriteria',
									text: '<i class="bi bi-file-spreadsheet me-2"></i>Excel',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3]
									}
								},
								{
									extend: 'pdf',
									title: 'Sub Kriteria',
									text: '<i class="bi bi-file-text me-2"></i>PDF',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3]
									}
								},
								{
									extend: 'copy',
									title: 'Sub Kriteria',
									text: '<i class="bi bi-clipboard me-2"></i>Copy',
									className: 'dropdown-item',
									exportOptions: {
										columns: [1, 2, 3]
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
				if (!$.fn.DataTable.isDataTable('#table-subcrit')) {
					$('#spare-button').removeClass('d-none');
				}
			}
			$.fn.dataTable.ext.errMode = 'none';
 
			dt_subkriteria.on( 'error.dt', function ( e, settings, techNote, message ) {
				Toastify({
					text: message,
					backgroundColor: "#ffc107",
					duration: 10000
				}).showToast();
				console.warn(techNote);
	    });
	    dt_subkriteria.on('draw',setTableColor);
		});
		// Delete Record
		$(document).on('click', '.delete-record', function() {
			var sub_id = $(this).data('id'),
				sub_name = $(this).data('name');

			Swal.fire({
				title: 'Hapus sub kriteria?',
				text: "Anda akan menghapus sub kriteria " + sub_name +
					". Jika sudah dilakukan perbandingan, perbandingan terkait akan dihapus!",
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
						url: '/kriteria/sub/del/' + sub_id,
						data: {
							"_token": "{{ csrf_token() }}"
						},
						success: function(data) {
							dt_subkriteria.draw();
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
						text: 'Sub Kriteria tidak dihapus.',
						icon: 'warning',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		});
		$('#SubCritForm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				data: $('#SubCritForm').serialize(),
				url: '/kriteria/sub/store',
				type: 'POST',
				beforeSend: function() {
					$('.data-submit').prop('disabled', true);
				},
				complete: function() {
					$('.data-submit').prop('disabled', false);
				},
				success: function(status) {
					dt_subkriteria.draw();
					$('#SubCritModal').modal('hide');

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
			var sub_id = $(this).data('id');
			$('#SubCritForm :input').prop('disabled', true);
			// changing the title of offcanvas
			$('#SubCritLabel').html('Edit Sub Kriteria');
			if($('#subkriteria-alert').length)
				$('#subkriteria-alert').addClass('d-none');

			// get data
			$.get('/kriteria/sub/edit/' + sub_id, function(data) {
					$('#subkriteria-id').val(data.id);
					$('#nama-sub').val(data.name);
					$('#kriteria-select').val(data.kriteria_id);
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
					$('#SubCritForm :input').prop('disabled', false);
				});
		});
		// clearing form data when modal hidden
		$('#SubCritModal').on('hidden.bs.modal', function() {
			$('#SubCritForm')[0].reset();
			$('#SubCritLabel').html('Tambah Sub Kriteria');
			if($('#subkriteria-alert').length)
				$('#subkriteria-alert').removeClass('d-none');
		});
	</script>
@endsection
