@extends('layout')
@php
use App\Http\Controllers\SubKriteriaController;
$subkriteriacomp = new SubKriteriaController();
$title = $subkriteriacomp->nama_kriteria($kriteria_id);
$numindex = 0;
@endphp
@section('title', 'Perbandingan Sub Kriteria ' . $title)
@section('subtitle', 'Perbandingan Sub Kriteria ' . $title)
@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Masukkan Perbandingan Sub Kriteria {{ $title }}</h4>
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
			@if ($jmlsubkriteria >= 2)
			<div class="table-responsive">
				<form method="POST" enctype="multipart/form-data"
					action="{{ route('bobotsubkriteria.store', $kriteria_id) }}">
					<table class="table table-lg table-hover table-striped text-center">
						<thead>
							<tr>
								<th>No</th>
								<th>Sub Kriteria</th>
								<th>Perbandingan</th>
								<th>Sub Kriteria</th>
							</tr>
						</thead>
						<tbody>@csrf
							@foreach ($array as $krit)
							@if ($krit['baris'] !== $krit['kolom'])
							<tr>
								<td>{{ ++$numindex }}</td>
								<th>
									<input type="radio" class="btn-check" name="subkriteria[{{ $loop->index }}]"
										id="left-{{ $loop->index }}" value="left" autocomplete="off" required
										{{$value[$loop->index]['nilai'] > 0 ||
									old("subkriteria.$loop->index") == 'left' ? 'checked' : '' }}>
									<label class="btn btn-outline-info" for="left-{{ $loop->index }}">
										{{ $krit['baris'] }}
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
									<input type="radio" name="subkriteria[{{ $loop->index }}]" class="btn-check" value="right" id="right-{{ $loop->index }}" autocomplete="off"
									{{ $value[$loop->index]['nilai'] < 0 ||
										old("subkriteria.$loop->index") == 'right' ? 'checked' : '' }}>
										<label class="btn btn-outline-warning" for="right-{{ $loop->index }}">
											{{ $krit['kolom'] }}
										</label>
								</th>
							</tr>
							@endif
							@endforeach
						</tbody>
					</table>
					<div class="col-12 d-flex justify-content-end">
						<div class="btn-group">
							<a href="{{ route('bobotsubkriteria.pick') }}" class="btn btn-secondary">
								<i class="bi bi-arrow-left"></i> Kembali
							</a>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-save-fill"></i> Simpan
							</button>
						</div>
					</div>
				</form>
			</div>
			@else
			<div class="alert alert-danger mt-3">
				<i class="bi bi-sign-stop"></i>
				Masukkan data <a href="{{ route('subkriteria.index') }}">Sub
					Kriteria</a> {{ $title }} dulu (Minimal 2) untuk melakukan
				perbandingan.
			</div>
			@endif
		</div>
	</div>
</div>
@endsection