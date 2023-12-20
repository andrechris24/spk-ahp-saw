@extends('layout')
@section('title', 'Perbandingan Sub Kriteria')
@section('subtitle', 'Perbandingan Sub Kriteria')
@section('content')
<div class="card">
	<div class="card-header">Daftar Perbandingan Sub Kriteria</div>
	<div class="card-body">
		<div class="spinner-grow text-danger d-none" role="status">
			<span class="visually-hidden">Menghapus...</span>
		</div>
		<table class="table table-hover table-striped" id="table-subcritcomp" style="width: 100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Kriteria</th>
					<th>Status</th>
					<th>Aksi</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	var dt_subkriteriacomp;
	$(document).ready(function() {
		try {
			$.fn.dataTable.ext.errMode = 'none';
			dt_subkriteriacomp = $('#table-subcritcomp').DataTable({
				stateSave: true,
				lengthChange: false,
				searching: false,
				serverSide: true,
				processing: true,
				responsive: true,
				ajax: {
					url: "{{ route('bobotsubkriteria.data') }}",
					type: 'POST'
				},
				columns: [{
					data: 'id'
				}, {
					data: 'name'
				}, {
					data: 'result'
				}, {
					data: 'id'
				}],
				columnDefs: [{
					targets: 0,
					orderable: false,
					render: function(data, type, full, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					targets: 2,
					render: function(data) {
						if (data) {
							return '<small><span class="badge bg-success">Perbandingan sudah dilakukan</span></small>';
						}
						return '<small><span class="badge bg-danger">Perbandingan belum dilakukan atau tidak lengkap</span></small>';
					}
				}, { //Aksi
					orderable: false,
					targets: -1,
					render: function(data, type, full) {
						if (full['result']) {
							return ('<div class="btn-group" role="group">' +
								`<a class="btn btn-sm btn-primary" href="/bobot/sub/${data}" title="Edit"><i class="bi bi-pencil-square"></i></a>` +
								`<a class="btn btn-sm btn-success" href="/bobot/sub/${data}/hasil" title="Lihat hasil"><i class="bi bi-eye-fill"></i></a>` +
								`<button class="btn btn-sm btn-danger delete-comp" data-id="${data}" data-name="${full['name']}" title="Reset perbandingan"><i class="bi bi-arrow-counterclockwise"></i></button>` +
								'</div>');
						}
						return (
							`<a class="btn btn-sm btn-primary" href="/bobot/sub/${data}" title="Input perbandingan"><i class="bi bi-plus-lg"></i></a>`
						);
					}
				}],
				language: {
					url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
				}
			}).on('error.dt', function(e, settings, techNote, message) {
				errorDT(message, techNote);
			}).on('draw', setTableColor);
		} catch (dterr) {
			initError(dterr.message);
		}
	}).on('click', '.delete-comp', function() {
		var kriteria = $(this).data('name'), id = $(this).data('id');
		Swal.fire({
			title: 'Reset perbandingan?',
			text: "Anda akan mereset perbandingan Sub Kriteria " + kriteria +
				". Bobot Sub Kriteria " + kriteria + " akan direset!",
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
			if (result.value) {
				$.ajax({
					type: 'DELETE',
					url: '/bobot/sub/' + id + '/del',
					beforeSend: function() {
						$('.spinner-grow').removeClass('d-none');
					},
					complete: function() {
						$('.spinner-grow').addClass('d-none');
					},
					success: function(data) {
						dt_subkriteriacomp.draw();
						Swal.fire({
							icon: 'success',
							title: 'Direset',
							text: data.message,
							customClass: {
								confirmButton: 'btn btn-success'
							}
						});
					},
					error: function(xhr, stat, err) {
						if (xhr.status === 404) dt_subkriteriacomp.draw();
						Swal.fire({
							icon: 'error',
							title: 'Gagal reset',
							text: 'Kesalahan HTTP ' + xhr.status + '. ' + 
								(xhr.responseJSON.message ?? err),
							customClass: {
								confirmButton: 'btn btn-success'
							}
						});
					}
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal.fire({
					title: 'Dibatalkan',
					text: "Perbandingan Sub Kriteria " + kriteria + " tidak direset.",
					icon: 'warning',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				});
			}
		});
	});
</script>
@endsection