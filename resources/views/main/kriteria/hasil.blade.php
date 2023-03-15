@extends('layout')
@section('title', 'Hasil Perbandingan Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Perbandingan Kriteria</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Perbandingan Awal</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									@foreach ($data['kriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['kriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_awal'] as $ma)
											@if ($ma['kode_kriteria'] == $kr->idkriteria)
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
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									@foreach ($data['kriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['kriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_perbandingan'] as $mp)
											@if ($mp['kode_kriteria'] == $kr->idkriteria)
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
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									@foreach ($data['kriteria'] as $kr)
										<th>{{ $kr->name }}</th>
									@endforeach
									<th>Jumlah Baris</th>
									<th>Eigen</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data['kriteria'] as $kr)
									<tr>
										<th>{{ $kr->name }}</th>
										@foreach ($data['matriks_normalisasi'] as $mn)
											@if ($mn['kode_kriteria'] == $kr->idkriteria)
												<td>{{ $mn['nilai'] }}</td>
											@endif
										@endforeach
										@foreach ($data['bobot_prioritas'] as $bp)
											@if ($bp['kode_kriteria'] == $kr->idkriteria)
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
								<td>{{ $data['result'] }}</td>
							</tr>
							<tr>
								<td>Hasil Konsistensi</td>
								<td>
									@if ($data['result'] <= 0.1)
										<span class="text-success"><b>Konsisten</b></span>
									@else
										<span class="text-danger">
											<b>Tidak Konsisten</b>, mohon untuk menginput ulang perbandingan!
										</span>
									@endif
								</td>
							</tr>
						</table>
						<a href="{{ url('/bobot/reset') }}" class="btn btn-secondary">
							<i class="bi bi-arrow-counterclockwise"></i> Reset
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection