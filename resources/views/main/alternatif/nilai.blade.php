@extends('layout')
@section('title', 'Nilai Alternatif');
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Nilai Alternatif</h3>
		</div>
		<section class="section">
			@include('components.message')
			<div class="modal fade text-left" id="NilaiAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="NilaiAlterLabel" aria-hidden="true">
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
								@csrf
								<input type="hidden" name="alternatif_id" id="alternatif-hidden">
								<input type="hidden" name="_method" value="POST" id="form-method">
								<input type="hidden" name="datatables_idx" id="edit-index">
								<div class="input-group mb-3">
									<label class="input-group-text" for="alternatif-value">
										Nama Alternatif
									</label>
									<select class="form-select" id="alternatif-value" name="alternatif_id"
										required>
										<option value="">Pilih</option>
										@foreach ($alternatif as $alt)
											<option value="{{ $alt->id }}">{{ $alt->name }}</option>
										@endforeach
									</select>
									<div class="invalid-feedback" id="alternatif-error"></div>
								</div>
								@foreach ($kriteria as $kr)
									<input type="hidden" name="kriteria_id[]" value="{{ $kr->id }}">
									<div class="input-group mb-3">
										<label class="input-group-text" for="subkriteria-{{ $kr->id }}"
											title="{{ $kr->desc }}">
											{{ $kr->name }}
										</label>
										<select class="form-select" id="subkriteria-{{ $kr->id }}"
											name="subkriteria_id[]" required>
											<option value="">Pilih</option>
											@foreach ($subkriteria as $subkr)
												@if ($subkr->kriteria_id == $kr->id)
													<option value="{{ $subkr->id }}">
														{{ $subkr->name }}
													</option>
												@endif
											@endforeach
										</select>
										<div class="invalid-feedback"
											id="subkriteria-error-{{ $kr->id }}"></div>
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
					<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
						data-bs-target="#NilaiAlterModal" id="spare-button">
						<i class="bi bi-plus-lg me-0 me-sm-1"></i>
						Tambah Nilai Alternatif
					</button>
					<table class="table table-hover" id="table-nilaialt" style="width: 100%">
						<thead>
							<tr>
								<th>Nama Alternatif</th>
								@foreach ($kriteria as $kr)
									<th data-bs-toggle="tooltip"
										data-bs-placement="bottom" title="{{ $kr->desc }}">
										{{ $kr->name }}
									</th>
								@endforeach
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($alternatif as $alt)
								@php
									$subcount = 0;
									$subkr = [];
									$skor = $nilaialt->where('alternatif_id', '=', $alt->id)->all();
								@endphp
								@if (count($skor) > 0)
									<tr>
										<td>{{ $alt->name }}</td>
										@foreach ($skor as $skoralt)
											@php
												$subkr[$subcount]['subkriteria'] = $skoralt->subkriteria->id;
												$subkr[$subcount]['kriteria'] = $skoralt->kriteria->id;
											@endphp
											<td>{{ $skoralt->subkriteria->name }}</td>
											@php($subcount++)
										@endforeach
										<td>
											<div class="btn-group" role="button">
												<button type="button" class="btn btn-primary edit-record"
													data-bs-toggle="modal" data-bs-target="#NilaiAlterModal"
													data-bs-name="{{ $alt->id }}" title="Edit"
													data-bs-score="{{ json_encode($subkr) }}">
													<i class="bi bi-pencil-square"></i>
												</button>
												<button type="button" class="btn btn-danger delete-record"
													data-bs-id="{{ $alt->id }}" title="Hapus"
													data-bs-name="{{ $alt->name }}">
													<i class="bi bi-trash3-fill"></i>
												</button>
											</div>
										</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
					@if (count($nilaialt) > 0)
						<a href="{{ route('nilai.show') }}" class="btn btn-success">
							Lihat hasil
						</a>
					@endif
				</div>
			</div>
		</section>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		var formkriteria;

		function split(item) {
			formkriteria = document.getElementById('subkriteria-' + item.kriteria);
			formkriteria.value = item.subkriteria;
		}
		var nilaialtdt;
		$(document).ready(function() {
			try {
				nilaialtdt = $('#table-nilaialt').DataTable({
					"stateSave": true,
					"lengthChange": false,
					"searching": false,
					responsive: true,
					columnDefs: [{
						orderable: false,
						targets: -1
					}],
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
						},
						{
							extend: 'collection',
							text: '<i class="bi bi-download me-0 me-sm-1"></i>Ekspor',
							className: 'btn btn-primary dropdown-toggle',
							buttons: [{
									extend: 'print',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-printer me-2"></i>Print',
									className: 'dropdown-item',
									exportOptions: {
										columns: 'th:not(:last-child)'
									}
								},
								{
									extend: 'csv',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-text me-2"></i>CSV',
									className: 'dropdown-item',
									exportOptions: {
										columns: 'th:not(:last-child)'
									}
								},
								{
									extend: 'excel',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-spreadsheet me-2"></i>Excel',
									className: 'dropdown-item',
									exportOptions: {
										columns: 'th:not(:last-child)'
									}
								},
								{
									extend: 'pdf',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-text me-2"></i>PDF',
									className: 'dropdown-item',
									exportOptions: {
										columns: 'th:not(:last-child)'
									}
								},
								{
									extend: 'copy',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-clipboard me-2"></i>Copy',
									className: 'dropdown-item',
									exportOptions: {
										columns: 'th:not(:last-child)'
									}
								}
							]
						}
					]
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 7000,
					backgroundColor: "#dc3545"
				}).showToast();
				if (!$.fn.DataTable.isDataTable('#table-nilaialt'))
					$('#spare-button').removeClass('d-none');
			}
			nilaialtdt.on('draw', setTableColor);
		});
		$(document).on('click', '.delete-record', function() {
			var score_id = $(this).data('bs-id'),
				score_name = $(this).data('bs-name'),
				rowParent = $(this).parents('tr');
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
						data: {
							"_token": "{{ csrf_token() }}"
						},
						success: function(data) {
							nilaialtdt.row(rowParent)
								.remove().draw();
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
						text: 'Nilai Alternatif tidak dihapus.',
						icon: 'warning',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				}
			});
		});
		$('#NilaiAlterForm').on('submit', function(event) {
			formmethod = $('#form-method').val() ?? 'POST';
			event.preventDefault();
			$.ajax({
				data: $('#NilaiAlterForm').serialize(),
				url: '/alternatif/nilai/store',
				type: formmethod,
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
					if (formmethod == 'POST')
						nilaialtdt.row.add(status).draw(false);
					else
						nilaialtdt.row($('#edit-index').val())
						.data(status).draw();
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
			var dtparent = $(this).parents('tr'),
				rownum = nilaialtdt.row(dtparent).index();

			// changing the title of offcanvas
			$('#NilaiAlterLabel').html('Edit Nilai Alternatif');
			$('#form-method').val('PUT');
			$('#alternatif-hidden').val($(this).data('bsName'));
			$('#alternatif-value').val($(this).data('bsName'));
			$('#alternatif-value').prop('disabled', true);
			$('#edit-index').val(rownum);
			const data = $(this).data('bsScore');
			data.forEach(split);
		});
		// clearing form data when modal hidden
		$('#NilaiAlterModal').on('hidden.bs.modal', function() {
			$('#alternatif-value').prop('disabled', false);
			$('#NilaiAlterForm')[0].reset();
			$('#NilaiAlterLabel').html('Tambah Nilai Alternatif');
		});
	</script>
@endsection
