@extends('layout')
@section('title', 'Penilaian Alternatif');
@section('subtitle', 'Nilai Alternatif')
@section('content')
<div class="modal fade text-left" id="NilaiAlterModal" tabindex="-1" role="dialog"
	aria-labelledby="NilaiAlterLabel" aria-hidden="true" data-bs-focus="false">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="NilaiAlterLabel">Isi Nilai Alternatif</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i data-feather="x"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="NilaiAlterForm" class="needs-validation">
					<input type="hidden" name="alternatif_id" id="alternatif-hidden">@csrf
					<div class="input-group has-validation mb-3">
						<label class="input-group-text" for="alternatif-value">
							Nama Alternatif
						</label>
						<input type="text" class="form-control" id="alternatif-value" readonly>
						<div class="invalid-feedback" id="alternatif-error">
							Alternatif tidak valid
						</div>
					</div>
					@foreach ($data['kriteria'] as $kr)
					<input type="hidden" name="kriteria_id[]" value="{{ $kr->id }}">
					<div class="input-group has-validation mb-3" data-bs-toggle="tooltip" data-bs-placement="right"
						title="{{ $kr->name }}">
						<label class="input-group-text" for="subkriteria-{{ Str::slug($kr->name,'-') }}">
							C{{ $kr->id }}
						</label>
						<select class="form-select" id="subkriteria-{{ Str::slug($kr->name,'-') }}" name="subkriteria_id[]"
							required>
							<option value="">Pilih</option>
							@foreach ($data['subkriteria'] as $subkr)
							@if ($subkr->kriteria_id == $kr->id)
							<option value="{{ $subkr->id }}">{{ $subkr->name }}</option>
							@endif
							@endforeach
						</select>
						<div class="invalid-feedback" id="subkriteria-{{ Str::slug($kr->name,'-') }}-error">
							Pilih salah satu sub kriteria {{ $kr->name }}
						</div>
					</div>
					@endforeach
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
				<button type="submit" class="btn btn-primary ml-1 data-submit" form="NilaiAlterForm">
					<i class="bi bi-check d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Simpan</span>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Jumlah Alternatif</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2"><span id="total-alts">-</span></h3>
						</div>
					</div>
					<span class="badge bg-primary rounded p-2">
						<i class="fas fa-file-alt"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Jumlah Kriteria</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2">{{ count($data['kriteria']) }}</h3>
						</div>
					</div>
					<span class="badge bg-success rounded p-2">
						<i class="fas fa-list"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Belum dinilai</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2"><span id="total-noscore">-</span></h3>
						</div>
					</div>
					<span class="badge bg-warning rounded p-2">
						<i class="bi bi-question-circle-fill"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-header">Daftar Nilai Alternatif</div>
	<div class="card-body">
		<table class="table table-hover table-striped" id="table-nilaialt" style="width: 100%">
			<thead>
				<tr>
					<th>Alternatif</th>
					@foreach ($data['kriteria'] as $kr)
					<th data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $kr->name }}">
						C{{ $kr->id }}
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
	let nilaialtdt = $('#table-nilaialt'), errmsg;
	function setName(id, name) {
		$('#alternatif-hidden').val(id);
		$("#alternatif-value").val(name);
	}
	$(document).ready(function () {
		try {
			$.fn.dataTable.ext.errMode = 'none';
			nilaialtdt = nilaialtdt.DataTable({
				lengthChange: false,
				searching: false,
				responsive: true,
				serverSide: true,
				processing: true,
				ajax: {
					url: "{{ route('nilai.data') }}"
				},
				columnDefs: [{
					targets: 0,
					render: function (data, type, full) {
						return `A${data}<br><small>${full['name']}</small>`
					}
				},
				@foreach($data['kriteria'] as $kr)
				{
					orderable: false,
					targets: 1 + {{ $loop->index }}
				},
				@endforeach
				{
					orderable: false,
					targets: -1,
					render: function (data, type, full) {
						if (full['subkriteria'] === null) {
							return (
								`<button class="btn btn-sm btn-info" onclick="setName(${data}, '${full['name']}')" data-bs-toggle="modal" data-bs-target="#NilaiAlterModal" title="Tambah">` +
								'<i class="bi bi-plus-lg"></i>' +
								'</button>');
						}
						return ('<div class="btn-group" role="group">' +
							`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-nama="${full['name']}" data-bs-toggle="modal" data-bs-target="#NilaiAlterModal" title="Edit">` +
							'<i class="bi bi-pencil-square"></i>' +
							'</button>' +
							`<button class="btn btn-sm btn-danger delete-record" data-id="${data}" data-name="${full['name']}" title="Hapus">` +
							'<i class="bi bi-trash3-fill"></i>' +
							'</button>' +
							'</div>');
					}
				}],
				columns: [
					{ data: "id" },
					@foreach($data['kriteria'] as $kr)
						{ data: "subkriteria.{{ Str::of($kr->name)->slug('_') }}" },
					@endforeach 
					{ data: "id" }
				],
				language: {
					url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
				}
			}).on('error.dt', function (e, settings, techNote, message) {
				errorDT(message, techNote);
			}).on("preXhr", function () {
				$.get("{{ route('nilai.count') }}", function (data) {
					$('#total-noscore').text(data.unused);
					$('#total-alts').text(data.alternatif);
				}).fail(function (xhr, st) {
					console.warn(xhr.responseJSON.message ?? st);
					swal.fire({
						icon: 'error',
						title: 'Gagal memuat jumlah',
						text: `Kesalahan HTTP ${xhr.status}. ${xhr.statusText}`
					});
				});
			}).on('draw', setTableColor);
		} catch (dterr) {
			initError(dterr.message);
		}
	}).on('click', '.delete-record', function () {
		let score_id = $(this).data('id'), score_name = $(this).data('name');
		confirm.fire({
			title: 'Hapus nilai alternatif?',
			text: 'Anda akan menghapus nilai alternatif ' + score_name,
			preConfirm: async () => {
				try {
					await $.ajax({
						type: 'DELETE',
						headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
						url: '/nilai/' + score_id,
						success: function () {
							nilaialtdt.draw();
							return "Dihapus";
						},
						error: function (xhr, st) {
							if (xhr.status === 404) {
								nilaialtdt.draw();
								errmsg = `Nilai Alternatif ${score_name} tidak ditemukan`;
							} else {
								console.warn(xhr.responseJSON.message ?? st);
								errmsg = `Kesalahan HTTP ${xhr.status}. ${xhr.statusText}`;
							}
							Swal.showValidationMessage("Gagal hapus: " + errmsg);
						}
					});
				} catch (error) {
					console.error(error);
					Swal.showValidationMessage('Gagal hapus: ' + error);
				}
			}
		}).then(function (result) {
			if (result.isConfirmed) {
				swal.fire({
					icon: 'success',
					title: 'Berhasil dihapus'
				});
			}
		});
	}).on('click', '.edit-record', function () {
		let nilai_id = $(this).data('id'), nilai_name = $(this).data('nama');
		$('#NilaiAlterLabel').html('Edit Nilai Alternatif');
		$("#alternatif-value").val(nilai_name);
		$('#NilaiAlterForm :input').prop('disabled', true);
		$('.data-submit').prop('disabled', true);
		$('.spinner-grow').removeClass('d-none');
		$.get(`/nilai/${nilai_id}/edit`, function (data) {
			$('#alternatif-hidden').val(data.alternatif_id);
			@foreach($data['kriteria'] as $kr)
			$("#subkriteria-{{ Str::slug($kr->name,'-') }}").val(
				data.subkriteria.{{ Str::slug($kr->name,'_')}});
			@endforeach
		}).fail(function (xhr, st) {
			if (xhr.status === 404) {
				nilaialtdt.draw();
				$('#NilaiAlterModal').modal('hide');
				errmsg = xhr.responseJSON.message;
			} else {
				errmsg = `Kesalahan HTTP ${xhr.status}. ${xhr.statusText}`;
				console.warn(xhr.responseJSON.message ?? st);
			}
			swal.fire({
				icon: 'error',
				title: 'Gagal memuat data',
				text: errmsg
			});
		}).always(function () {
			$('#NilaiAlterForm :input').prop('disabled', false);
			$('.data-submit').prop('disabled', false);
			$('.spinner-grow').addClass('d-none');
		});
	});
	function submitform(event) {
		event.preventDefault();
		$.ajax({
			data: $('#NilaiAlterForm').serialize(),
			url: "{{ route('nilai.store') }}",
			type: 'POST',
			beforeSend: function () {
				$('#NilaiAlterForm :input').prop('disabled', true)
					.removeClass('is-invalid');
				$('.data-submit').prop('disabled', true);
				$('.spinner-grow').removeClass('d-none');
			},
			complete: function () {
				$('#NilaiAlterForm :input').prop('disabled', false);
				$('.data-submit').prop('disabled', false);
				$('.spinner-grow').addClass('d-none');
			},
			success: function (status) {
				if ($.fn.DataTable.isDataTable("#table-nilaialt")) nilaialtdt.draw();
				$('#NilaiAlterModal').modal('hide');
				swal.fire({
					icon: "success",
					title: "Berhasil dinilai"
				});
			},
			error: function (xhr, st) {
				if (xhr.status === 422) {
					resetvalidation();
					console.warn(xhr.responseJSON.errors);
					errmsg = xhr.responseJSON.message;
				} else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status}. ${xhr.statusText}`;
				}
				swal.fire({
					title: 'Gagal',
					text: errmsg,
					icon: 'error'
				});
			}
		});
	};
	// clearing form data when modal hidden
	$('#NilaiAlterModal').on('hidden.bs.modal', function () {
		resetvalidation();
		$('#NilaiAlterLabel').html('Isi Nilai Alternatif');
		$('#NilaiAlterForm')[0].reset();
		$('#alternatif-hidden').val("");
	});
</script>
@endsection