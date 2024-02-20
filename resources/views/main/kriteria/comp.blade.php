@extends('layout')
@section('title', 'Perbandingan Kriteria')
@section('subtitle', 'Perbandingan Kriteria')
@section('content')
@php($numindex = 0)
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Masukkan Perbandingan</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<div class="accordion mb-3" id="accordionTabelPerbandingan">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
							Tabel Nilai Perbandingan
						</button>
					</h2>
					<div id="flush-collapseOne" class="accordion-collapse collapse"
						data-bs-parent="#accordionTabelPerbandingan">
						<div class="accordion-body">
							<x-ahp-table />
						</div>
					</div>
				</div>
			</div>
			@if ($cek > $jmlcrit)
			<div class="btn-group">
				<a href="{{ route('bobotkriteria.result') }}" class="btn btn-success">
					<i class="bi bi-arrow-right"></i> Lihat Hasil
				</a>
				<a href="{{ route('bobotkriteria.reset') }}" class="btn btn-warning" id="reset-button">
					<i class="bi bi-arrow-counterclockwise"></i> Reset
				</a>
			</div>
			<form action="{{ route('bobotkriteria.reset') }}" method="POST" id="reset-kriteria">
				@csrf
				@method('DELETE')
			</form>
			@endif
			@if ($jmlcrit >= 2)
			<div class="table-responsive">
				<form method="POST" enctype="multipart/form-data" action="{{ route('bobotkriteria.store') }}">
					<table class="table table-lg table-hover table-striped text-center">
						<thead>
							<tr>
								<th>No</th>
								<th>Kriteria</th>
								<th>Perbandingan</th>
								<th>Kriteria</th>
							</tr>
						</thead>
						<tbody>@csrf
							@foreach ($array as $krit)
							@if ($krit['idbaris'] !== $krit['idkolom'])
							<tr>
								<td>{{ ++$numindex }}</td>
								<th>
									<input type="radio" class="btn-check" name="kriteria[{{ $loop->index }}]"
										id="left-{{ $loop->index }}" value="left" autocomplete="off"
										{{ $value[$loop->index]['nilai'] > 0 || old("kriteria.$loop->index") == 'left' ? 'checked' : '' }} required>
									<label class="btn btn-outline-info" for="left-{{ $loop->index }}">
										C{{ $krit['idbaris'] }}
										<small>{{ $krit['namabaris'] }}</small>
									</label>
								</th>
								<td>
									<div class="input-group mb-3">
										<input type="number" name="skala[{{ $loop->index }}]" min="1" max="9" class="form-control text-center @error('skala.' . $loop->index) is-invalid @enderror " value="{{ old('skala.' . $loop->index) ?? 
												(abs($value[$loop->index]['nilai']) ?? '') }}" required>
										@error('skala.' . $loop->index)
										<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</td>
								<th>
									<input type="radio" name="kriteria[{{ $loop->index }}]" class="btn-check" value="right" id="right-{{ $loop->index }}" autocomplete="off"
										{{ $value[$loop->index]['nilai'] < 0 || old("kriteria.$loop->index") == 'right' ? 'checked' : '' }}>
										<label class="btn btn-outline-warning" for="right-{{ $loop->index }}">
											C{{ $krit['idkolom'] }}
											<small>{{ $krit['namakolom'] }}</small>
										</label>
								</th>
							</tr>
							@endif
							@endforeach
						</tbody>
					</table>
					<div class="col-12 d-flex justify-content-end">
						<button type="submit" class="btn btn-primary">
							<i class="bi bi-save-fill"></i> Simpan
						</button>
					</div>
				</form>
			</div>
			@else
			<div class="alert alert-danger mt-3">
				<i class="bi bi-sign-stop"></i>
				Masukkan data <a href="{{ route('kriteria.index') }}">Kriteria</a>
				dulu (Minimal 2) untuk melakukan perbandingan.
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$(document).on('click', '#reset-button', function (e) {
		e.preventDefault();
		confirm.fire({
			title: 'Reset perbandingan?',
			text: "Anda akan mereset perbandingan Kriteria.\nBobot Kriteria akan direset!"
		}).then(function (result) {
			if (result.isConfirmed) document.getElementById('reset-kriteria').submit();
		});
	});
</script>
@endsection