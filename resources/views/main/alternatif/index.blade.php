@extends('layout')
@section('title', 'Alternatif')
@section('subtitle', 'Alternatif')
@section('content')
<div class="modal fade text-left" id="AlterModal" tabindex="-1" role="dialog" aria-labelledby="AlterLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="AlterLabel">Tambah Alternatif</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i data-feather="x"></i>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="AlterForm" class="needs-validation">
					<input type="hidden" name="id" id="alter-id">
					<label for="alter-name">Nama Alternatif</label>
					<div class="form-group">
						<input type="text" class="form-control" name="name" id="alter-name" required />
						<div class="invalid-feedback" id="alter-error">
							Masukkan Nama Alternatif
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
					<span class="d-none d-sm-inline-block">Batal</span>
				</button>
				<button type="submit" class="btn btn-primary ml-1 data-submit" form="AlterForm">
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
						<i class="fas fa-file-alt"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between" data-bs-toggle="tooltip"
					title="Klik kolom Nama Alternatif untuk mencari Alternatif duplikat">
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
	<div class="card-header">Daftar Alternatif</div>
	<div class="card-body">
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AlterModal">
			<i class="bi bi-plus-lg"></i> Tambah Alternatif
		</button>
		<div class="spinner-grow text-danger d-none" role="status">
			<span class="visually-hidden">Menghapus...</span>
		</div>
		<table class="table table-hover table-striped" id="table-alter" style="width: 100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode</th>
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
	var dt_alternatif,error;
	$(document).ready(function() {
		try {
			$.fn.dataTable.ext.errMode = "none";
			dt_alternatif = $("#table-alter").DataTable({
				stateSave: true,
				lengthChange: false,
				searching: false,
				responsive: true,
				serverSide: true,
				processing: true,
				ajax: {
					url: "{{ route('alternatif.data') }}",
					type: "POST"
				},
				columns: [{
					data: "id"
				}, {
					data: "id"
				}, {
					data: "name"
				}, {
					data: "id"
				}],
				columnDefs: [{
					targets: 0,
					orderable: false,
					render: function(data, type, full, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					targets: 1,
					render: function(data) {
						return 'A' + data;
					}
				}, { //Aksi
					orderable: false,
					targets: -1,
					render: function(data, type, full) {
						return ('<div class="btn-group" role="group">' +
							`<button class="btn btn-sm btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#AlterModal" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
							`<button class="btn btn-sm btn-danger delete-record" data-id="${data}" data-name="${full["name"]}" title="Hapus"><i class="bi bi-trash3-fill"></i></button>` +
							"</div>");
					}
				}],
				language: {
					url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
				}
				// dom: "Bfrtip",
					// buttons: [{
					// 	text: '<i class="bi bi-plus-lg me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah Alternatif</span>',
					// 	className: "add-new btn",
					// 	attr: {
					// 		"data-bs-toggle": "modal",
					// 		"data-bs-target": "#AlterModal"
					// 	}
					// }, {
					// 	extend: "collection",
					// 	text: '<i class="bi bi-download me-0 me-sm-1"></i> Ekspor',
					// 	className: "btn dropdown-toggle",
					// 	buttons: [{
					// 		extend: "print",
					// 		title: "Alternatif",
					// 		text: '<i class="bi bi-printer me-2"></i> Print',
					// 		className: "dropdown-item",
					// 		exportOptions: {
					// 			columns: [1, 2]
					// 		}
					// 	}, {
					// 		extend: "excel",
					// 		title: "Alternatif",
					// 		text: '<i class="bi bi-file-spreadsheet me-2"></i> Excel',
					// 		className: "dropdown-item",
					// 		exportOptions: {
					// 			columns: [1, 2]
					// 		}
					// 	}, {
					// 		extend: "pdf",
					// 		title: "Alternatif",
					// 		text: '<i class="bi bi-file-text me-2"></i> PDF',
					// 		className: "dropdown-item",
					// 		exportOptions: {
					// 			columns: [1, 2],
					// 		}
					// 	}],
				// }]
			}).on("error.dt", function(e, settings, techNote, message) {
				errorDT(message, techNote);
			}).on("preXhr", function() {
				$.get("{{ route('alternatif.count') }}", function(data) {
					$("#total-duplicate").text(data.duplicates);
					$("#total-counter").text(data.total);
				}).fail(function(xhr, st, err) {
					Toastify({
						text: "Gagal memuat jumlah: Kesalahan HTTP " +
							xhr.status + '. ' + (xhr.statusText ?? err),
						style: {
							background: "#dc3545"
						},
						duration: 9000
					}).showToast();
				});
			}).on("draw", setTableColor);
		} catch (dterr) {
			initError(dterr.message);
		}
	}).on("click", ".delete-record", function() {
		var alt_id = $(this).data("id"), alt_name = $(this).data("name");
		Swal.fire({
			title: "Hapus alternatif?",
			text: "Anda akan menghapus alternatif " + alt_name +
				". Jika sudah dilakukan penilaian, penilaian terkait akan dihapus!",
			icon: "question",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
			customClass: {
				confirmButton: "btn btn-primary me-3",
				cancelButton: "btn btn-secondary"
			},
			buttonsStyling: false,
		}).then(function(result) {
			if (result.value) { // delete the data
				$.ajax({
					type: "DELETE",
					url: "/alternatif/del/" + alt_id,
					beforeSend: function() {
						$('.spinner-grow.text-danger').removeClass('d-none');
					},
					complete: function() {
						$('.spinner-grow.text-danger').addClass('d-none');
					},
					success: function() {
						dt_alternatif.draw();
						Swal.fire({
							icon: "success",
							title: "Dihapus",
							text: "Alternatif " + alt_name + " sudah dihapus.",
							customClass: {
								confirmButton: "btn btn-success"
							}
						});
					},
					error: function(xhr, stat, err) {
						if (xhr.status === 404) {
							dt_alternatif.draw();
							error="Alternatif " + alt_name + " tidak ditemukan.";
						}else{
							error="Kesalahan HTTP " + xhr.status + "." + 
								(xhr.responseJSON.message ?? err);
						}
						Swal.fire({
							icon: "error",
							title: "Gagal hapus",
							text: error,
							customClass: {
								confirmButton: "btn btn-success",
							},
						});
					},
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal.fire({
					title: "Dibatalkan",
					text: "Alternatif "+alt_name+" tidak dihapus.",
					icon: "warning",
					customClass: {
						confirmButton: "btn btn-success"
					}
				});
			}
		});
	}).on("click", ".edit-record", function() {
		var alt_id = $(this).data("id");

			// changing the title of offcanvas
		$("#AlterLabel").html("Edit Alternatif");
		$("#AlterForm :input").prop("disabled", true);
		$(".data-submit").prop("disabled", true);
		$(".spinner-grow.text-primary").removeClass("d-none");
			// get data
		$.get("/alternatif/edit/" + alt_id, function(data) {
			$("#alter-id").val(data.id);
			$("#alter-name").val(data.name);
		}).fail(function(xhr, st, err) {
			if (xhr.status === 404) {
				$("#AlterModal").modal("hide");
				dt_alternatif.draw();
				error="Alternatif tidak ditemukan.";
			}else{
				error="Kesalahan HTTP " + xhr.status + ". " +
					(xhr.responseJSON.message ?? err);
			}
			Swal.fire({
				icon: "error",
				title: "Gagal memuat data",
				text: error,
				customClass: {
					confirmButton: "btn btn-success"
				}
			});
		}).always(function() {
			$("#AlterForm :input").prop("disabled", false);
			$(".data-submit").prop("disabled", false);
			$(".spinner-grow.text-primary").addClass("d-none");
		});
	});

	function submitform(event) {
		var errmsg, actionurl = $("#alter-id").val() == "" ?
			"{{ route('alternatif.store') }}" : "{{ route('alternatif.update') }}";
		event.preventDefault();
		$.ajax({
			data: $("#AlterForm").serialize(),
			url: actionurl,
			type: "POST",
			beforeSend: function() {
				$("#AlterForm :input").prop("disabled", true).removeClass("is-invalid");
				$(".data-submit").prop("disabled", true);
				$(".spinner-grow.text-primary").removeClass("d-none");
			},
			complete: function() {
				$("#AlterForm :input").prop("disabled", false);
				$(".data-submit").prop("disabled", false);
				$(".spinner-grow.text-primary").addClass("d-none");
			},
			success: function(status) {
				if ($.fn.DataTable.isDataTable("#table-alter")) dt_alternatif.draw();
				$("#AlterModal").modal("hide");
				Swal.fire({
					icon: "success",
					title: "Sukses",
					text: status.message,
					customClass: {
						confirmButton: "btn btn-success"
					}
				});
			},
			error: function(xhr, stat, err) {
				if (xhr.status === 422) {
					resetvalidation();
					if (typeof xhr.responseJSON.errors.name !== "undefined") {
						$("#alter-name").addClass("is-invalid");
						$("#alter-error").text(xhr.responseJSON.errors.name);
					}
					errmsg = xhr.responseJSON.message;
				} else {
					errmsg = "Kesalahan HTTP " + xhr.status + ". " +
						(xhr.responseJSON.message ?? err);
				}
				Swal.fire({
					title: "Gagal",
					text: errmsg,
					icon: "error",
					customClass: {
						confirmButton: "btn btn-success"
					}
				});
			}
		});
	};
	// clearing form data when modal hidden
	$("#AlterModal").on("hidden.bs.modal", function() {
		resetvalidation();
		$("#AlterForm")[0].reset();
		$("#alter-id").val("");
		$("#AlterLabel").html("Tambah Alternatif");
	});
</script>
@endsection