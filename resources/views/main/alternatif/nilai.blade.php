@extends('layout')
@section('title', 'Nilai Alternatif');
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Nilai Alternatif</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="modal fade text-left" id="DelNilaiAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header bg-warning">
							<h5 class="modal-title white" id="myModalLabel160">
								Hapus Nilai Alternatif
							</h5>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<div class="modal-body">
							<p>
								<span id="del-desc">Anda akan menghapus sebuah nilai alternatif.</span>
							</p>
							<p>Lanjutkan?</p>
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
			<div class="modal fade text-left" id="AddNilaiAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="AddNilaiAlterLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="AddNilaiAlterLabel">
								Tambah Nilai Alternatif
							</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/alternatif/nilai/add') }}" method="post"
							enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="input-group mb-3">
									<label class="input-group-text" for="alternatif">
										Nama Alternatif
									</label>
									<select class="form-select" id="alternatif" name="alternatif_id"
										required>
										<option value="">Pilih</option>
										@foreach ($alternatif as $alt)
											<option value="{{ $alt->id }}">{{ $alt->name }}</option>
										@endforeach
									</select>
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
													<option value="{{ $subkr->id }}">{{ $subkr->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								@endforeach
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
			<div class="modal fade text-left" id="EditNilaiAlterModal" tabindex="-1"
				role="dialog" aria-labelledby="EditNilaiAlterLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
					role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="EditNilaiAlterLabel">
								Edit Nilai Alternatif
							</h4>
							<button type="button" class="close" data-bs-dismiss="modal"
								aria-label="Close">
								<i data-feather="x"></i>
							</button>
						</div>
						<form action="{{ url('/alternatif/nilai/update/:id') }}" method="post"
							enctype="multipart/form-data" name="editnilaialternatif">
							@csrf
							<input type="hidden" name="alternatif_id" id="alter">
							<div class="modal-body">
								<div class="input-group mb-3">
									<label class="input-group-text" for="alternatif-edit">
										Nama Alternatif
									</label>
									<select class="form-select" id="alternatif-edit" name="alternatif_id"
										disabled>
										<option value="">Pilih</option>
										@foreach ($alternatif as $alt)
											<option value="{{ $alt->id }}">{{ $alt->name }}</option>
										@endforeach
									</select>
								</div>
								@foreach ($kriteria as $kr)
									<input type="hidden" name="kriteria_id[]"
										value="{{ $kr->id }}">
									<div class="input-group mb-3">
										<label class="input-group-text"
											for="subkriteria-{{ $kr->id }}-edit"
											title="{{ $kr->desc }}">
											{{ $kr->name }}
										</label>
										<select class="form-select" name="subkriteria_id[]"
											id="subkriteria-{{ $kr->id }}-edit" required>
											<option value="">Pilih</option>
											@foreach ($subkriteria as $subkr)
												@if ($subkr->kriteria_id == $kr->id)
													<option value="{{ $subkr->id }}">{{ $subkr->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								@endforeach
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
				<div class="card-header">Daftar Nilai Alternatif</div>
				<div class="card-body">
					<div class="btn btn-group">
						<button type="button" class="btn btn-primary" data-bs-toggle="modal"
							data-bs-target="#AddNilaiAlterModal">
							<i class="bi bi-plus-lg"></i>
							Tambah Nilai Alternatif
						</button>
						<a href="{{ url('alternatif/hasil') }}"
							class="btn btn-success @if (count($nilaialt) == 0) disabled @endif ">
							Lihat hasil
						</a>
					</div>
					<table class="table table-hover" id="table-nilaialt">
						<thead>
							<tr>
								<th>Nama Alternatif</th>
								@foreach ($kriteria as $kr)
									<th title="{{ $kr->desc }}">{{ $kr->name }}</th>
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
												<button type="button" class="btn btn-primary"
													data-bs-toggle="modal" data-bs-target="#EditNilaiAlterModal"
													data-bs-id="{{ $alt->id }}"
													data-bs-name="{{ $alt->id }}"
													data-bs-score="{{ json_encode($subkr) }}">
													<i class="bi bi-pencil-square"></i> Edit
												</button>
												<button type="button" class="btn btn-danger"
													data-bs-toggle="modal" data-bs-target="#DelNilaiAlterModal"
													data-bs-id="{{ $alt->id }}"
													data-bs-name="{{ $alt->name }}">
													<i class="bi bi-trash3-fill"></i> Hapus
												</button>
											</div>
										</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
@endsection
@php($critcount = count($kriteria))
@section('js')
	<script type="text/javascript">
		var formkriteria;

		function test(item) {
			formkriteria = document.getElementById('subkriteria-' + item.kriteria +
				'-edit');
			formkriteria.value = item.subkriteria;
		}
		const addNilaiAlterModal = document.getElementById('AddNilaiAlterModal');
		addNilaiAlterModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-bs-id');
			const nameval = addNilaiAlterModal.querySelector('#alternatif');
			if (id) nameval.value = id;
			else nameval.value = '';
		});
		const editNilaiAlterModal = document.getElementById('EditNilaiAlterModal');
		editNilaiAlterModal.addEventListener('show.bs.modal', event => {
			// Button that triggered the modal
			const button = event.relatedTarget;
			// Extract info from data-bs-* attributes
			const nama = button.getAttribute('data-bs-name');
			const id = button.getAttribute('data-bs-id');
			const data = JSON.parse(button.getAttribute('data-bs-score'));
			data.forEach(test);
			const nameval = editNilaiAlterModal.querySelector(
				'#alternatif-edit');
			const hiddenval = editNilaiAlterModal.querySelector('#alter');
			nameval.value = nama;
			hiddenval.value = nama;
			var formurl = "{{ url('/alternatif/nilai/update/:id') }}";
			formurl = formurl.replace(':id', id);
			document.editnilaialternatif.action = formurl;
		});
		const delNilaiAlterModal = document.getElementById('DelNilaiAlterModal');
		delNilaiAlterModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-bs-id');
			const nama = button.getAttribute('data-bs-name');
			const link = delNilaiAlterModal.querySelector('#del-action');
			const desc = delNilaiAlterModal.querySelector('#del-desc');
			var formurl = "{{ url('/alternatif/nilai/del/:id') }}";
			formurl = formurl.replace(':id', id);
			desc.innerHTML = "Anda akan menghapus nilai alternatif <b>" +
				nama +
				"</b>.";
			link.href = formurl;
		});
		$(document).ready(function() {
			$('#table-nilaialt').DataTable({
				"stateSave": true,
				"lengthChange": false,
				"searching": false,
				columnDefs: [{
					orderable: false,
					targets: {{ $critcount + 1 }},
				}],
				language: {
					url: '{{ url('assets/DataTables-id.json') }}'
				}
			});
		});
	</script>
@endsection
