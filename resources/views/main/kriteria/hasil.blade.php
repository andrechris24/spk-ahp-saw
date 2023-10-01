@extends('layout')
@section('title', 'Hasil Perbandingan Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Perbandingan Kriteria</h3>
		</div>
		<section class="section">
			@include('components.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Perbandingan Awal</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
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
											@if ($ma['kode_kriteria'] === $kr->idkriteria)
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
											@if ($mp['kode_kriteria'] === $kr->idkriteria)
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
											@if ($mn['kode_kriteria'] === $kr->idkriteria)
												<td>{{ $mn['nilai'] }}</td>
											@endif
										@endforeach
										@foreach ($data['bobot_prioritas'] as $bp)
											@if ($bp['kode_kriteria'] === $kr->idkriteria)
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
										<span class="text-warning" data-bs-toggle="tooltip"
											title="Minimal 3 kriteria untuk melakukan pengecekan hasil Konsistensi">
											<b>Tidak bisa dievaluasi</b>
										</span>
									@elseif ($data['result'] <= 0.1)
										<span class="text-success"><b>Konsisten</b></span>
									@else
										<span class="text-danger" data-bs-toggle="tooltip"
											title="Nilai CR maksimal 10%">
											<b>Tidak Konsisten</b>, mohon untuk menginput ulang perbandingan!
										</span>
									@endif
								</td>
							</tr>
						</table>
						<div class="col-12 d-flex justify-content-end">
							<div class="spinner-grow text-primary me-3 d-none" role="status">
								<span class="visually-hidden">Mereset...</span>
							</div>
							<div class="btn-group">
								<a href="{{ route('bobotkriteria.reset') }}" class="btn btn-warning"
									id="reset-button">
									<i class="bi bi-arrow-counterclockwise"></i> Reset
								</a>
								@if (!is_numeric($data['result']) || $data['result'] <= 0.1)
									<a href="{{ url('/bobot/sub') }}" class="btn btn-primary">
										<i class="bi bi-arrow-right"></i> Lanjut
									</a>
								@endif
							</div>
							<form action="{{ route('bobotkriteria.reset') }}" method="POST"
								id="reset-kriteria">
								@csrf
								@method('DELETE')
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		$(document).on('click', '#reset-button', function(e) {
			e.preventDefault();
			Swal.fire({
				title: 'Reset perbandingan?',
				text: "Anda akan mereset perbandingan Kriteria. Bobot Kriteria akan direset!",
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
					document.getElementById('reset-kriteria').submit();
					$('.spinner-grow').removeClass('d-none');
				} else if (result.dismiss === Swal.DismissReason
					.cancel) {
					Swal.fire({
						title: 'Dibatalkan',
						text: 'Perbandingan Kriteria tidak direset.',
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
