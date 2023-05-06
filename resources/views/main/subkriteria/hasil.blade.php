@extends('layout')
@php
	use App\Http\Controllers\SubKriteriaCompController;
	$subkriteriacomp = new SubKriteriaCompController();
	$title = $subkriteriacomp->nama_kriteria($kriteria_id);
@endphp
@section('title', 'Hasil Perbandingan Subkriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Perbandingan Sub Kriteria {{ $title }}</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Perbandingan Awal</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th>Sub Kriteria</th>
									@foreach ($data['subkriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['subkriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_awal'] as $ma)
											@if ($ma['kode_kriteria'] == $kr->idsubkriteria)
												<td>{{ $ma['nilai'] }}</td>
											@endif
										@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Nilai Perbandingan</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th>Sub Kriteria</th>
									@foreach ($data['subkriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['subkriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_perbandingan'] as $mp)
											@if ($mp['kode_kriteria'] == $kr->idsubkriteria)
												<td>{{ $mp['nilai'] }}</td>
											@endif
										@endforeach
									</tr>
								@endforeach
								<tr>
									<th>Jumlah</th>
									@foreach ($data['jumlah'] as $nilai)
										<td>{{ $nilai['jumlah'] }}</td>
									@endforeach
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Normalisasi dan Eigen</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th>Sub Kriteria</th>
									@foreach ($data['subkriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
									<th>Jumlah Baris</th>
									<th>Eigen</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data['subkriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_normalisasi'] as $mn)
											@if ($mn['kode_kriteria'] == $kr->idsubkriteria)
												<td>{{ $mn['nilai'] }}</td>
											@endif
										@endforeach
										@foreach ($data['bobot_prioritas'] as $bp)
											@if ($bp['kode_kriteria'] == $kr->idsubkriteria)
												<td>{{ $bp['jumlah_baris'] }}</td>
												<td>{{ $bp['bobot'] }}
											@endif
										@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<div class="card-title">Nilai Konsistensi</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<tr>
								<td>Consistency Measure</td>
								<td>
									@foreach ($data['cm'] as $cm)
										[{{ $cm['cm'] }}]
									@endforeach
								</td>
							</tr>
							<tr>
								<td>Rata-rata Consistency Measure</td>
								<td>{{ $data['average_cm'] }}</td>
							</tr>
							<tr>
								<td>Consistency Index (CI)</td>
								<td>{{ $data['ci'] }}</td>
							</tr>
							<tr>
								<td>Consistency Ratio (CR)</td>
								<td>
									{{ $data['result'] }}
									@if (is_numeric($data['result']))
										({{ $data['result'] * 100 }}%)
									@endif
								</td>
							</tr>
							<tr>
								<td>Hasil Konsistensi</td>
								<td>
									@if (!is_numeric($data['result']))
										<span class="text-warning" data-bs-toggle="tooltip" data-bs-title="Minimal 3 sub kriteria untuk melakukan pengecekan hasil Konsistensi">
											<b>Tidak bisa dievaluasi</b>
										</span>
									@elseif ($data['result'] <= 0.1)
										<span class="text-success"><b>Konsisten</b></span>
									@else
										<span class="text-danger" data-bs-toggle="tooltip" data-bs-title="Hasil konsistensi maksimal 10%">
											<b>Tidak Konsisten</b>, mohon untuk menginput ulang perbandingan!
										</span>
									@endif
								</td>
							</tr>
						</table>
						<div class="col-12 d-flex justify-content-end">
							<div class="btn-group">
								<a href="{{ url('bobot/sub') }}" class="btn btn-secondary">
									<i class="bi bi-arrow-left"></i> Kembali
								</a>
								<a href="{{ url('/bobot/sub/reset/' . $kriteria_id) }}"
									class="btn btn-warning">
									<i class="bi bi-arrow-counterclockwise"></i> Reset
								</a>
								@if (!is_numeric($data['result']) || $data['result'] <= 0.1)
									<a href="{{ url('/alternatif/nilai') }}" class="btn btn-primary">
										<i class="bi bi-arrow-right"></i> Lanjut
									</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
