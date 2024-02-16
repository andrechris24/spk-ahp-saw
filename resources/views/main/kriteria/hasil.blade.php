@extends('layout')
@section('title', 'Hasil Perbandingan Kriteria')
@section('subtitle', 'Hasil Perbandingan Kriteria')
@section('content')
<x-inconsistent-reason />
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Matriks Perbandingan Awal</h4>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover table-striped text-center">
				<thead>
					<tr>
						<th>Kriteria</th>
						@foreach ($data['kriteria'] as $kr)
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($data['kriteria'] as $kr)
					<tr>
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@foreach ($data['matriks_awal'] as $ma)
						@if ($ma['kode_kriteria'] === $kr->idkriteria)
						<td>
							@if($ma['nilai']<0) <sup>1</sup>/<sub>{{ abs($ma['nilai']) }}</sub>
							@else <sup>{{ $ma['nilai'] }}</sup>/<sub>1</sub>
							@endif
						</td>
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
			<table class="table table-hover table-striped text-center">
				<thead>
					<tr>
						<th>Kriteria</th>
						@foreach ($data['kriteria'] as $kr)
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($data['kriteria'] as $kr)
					<tr>
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@foreach ($data['matriks_perbandingan'] as $mp)
						@if ($mp['kode_kriteria'] === $kr->idkriteria)
						<td>{{ round($mp['nilai'], 5) }}</td>
						@endif
						@endforeach
					</tr>
					@endforeach
					<tr>
						<th>Jumlah</th>
						@foreach ($data['jumlah'] as $nilai)
						<td class="text-info">{{ round($nilai, 5) }}</td>
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
			<table class="table table-hover table-striped text-center">
				<thead>
					<tr>
						<th>Kriteria</th>
						@foreach ($data['kriteria'] as $kr)
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@endforeach
						<th>Jumlah Baris</th>
						<th data-bs-toggle="tooltip" title="Bobot Prioritas">Eigen</th>
						<th>Consistency Measure</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data['kriteria'] as $kr)
					<tr>
						<th data-bs-toggle="tooltip" title="{{ $kr->name }}">
							C{{ $kr->id }}
						</th>
						@foreach ($data['matriks_normalisasi'] as $mn)
						@if ($mn['kode_kriteria'] === $kr->idkriteria)
						<td>{{ round($mn['nilai'], 5) }}</td>
						@endif
						@endforeach
						@if ($data['bobot_prioritas'][$loop->index]['kode_kriteria'] === $kr->idkriteria)
						<td class="text-info">
							{{ round($data['bobot_prioritas'][$loop->index]['jumlah_baris'], 5) }}
						</td>
						<td class="text-info">
							{{ round($data['bobot_prioritas'][$loop->index]['bobot'], 5) }}
						</td>
						@endif
						@if ($data['cm'][$loop->index]['kode_kriteria'] === $kr->idkriteria)
						<td class="text-info">
							{{ round($data['cm'][$loop->index]['cm'], 5) }}
						</td>
						@endif
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
					<td>Principe Eigen Vektor</td>
					<td>{{ round($data['average_cm'], 5) }}</td>
				</tr>
				<tr>
					<td>Consistency Index (CI)</td>
					<td>{{ round($data['ci'], 5) }}</td>
				</tr>
				<tr>
					<td>Consistency Ratio (CR)</td>
					<td>
						@if (is_numeric($data['result']))
							@php $consistent = $data['result'] <= 0.1; @endphp
							{{round($data['result'], 5)}}
							<span @class(['text-danger'=> $data['result'] > 0.1])>
								({{ round($data['result'] * 100, 2) }}%)
							</span>
						@else
							@php $consistent = true; @endphp
							-
						@endif
					</td>
				</tr>
				<tr>
					<td>Hasil Konsistensi</td>
					<td>
						<span @class(['text-warning'=> !is_numeric($data['result']),
							'text-danger' => !$consistent,
							'text-success' => is_numeric($data['result']) && $consistent])>
							@if (!is_numeric($data['result'])) <b>Tidak bisa dievaluasi</b>
							@elseif ($consistent) <b>Konsisten</b>
							@else
							<b>Tidak Konsisten</b>, mohon untuk menginput ulang perbandingan!
							@endif
						</span>
					</td>
				</tr>
			</table>
			<div class="col-12 d-flex justify-content-end">
				<div class="spinner-grow text-info me-3 d-none" role="status">
					<span class="visually-hidden">Mereset...</span>
				</div>
				<div class="btn-group">
					<a href="{{ route('bobotkriteria.index') }}" class="btn btn-secondary">
						<i class="bi bi-arrow-left"></i> Kembali
					</a>
					<a href="{{ route('bobotkriteria.reset') }}" class="btn btn-warning" id="reset-button">
						<i class="bi bi-arrow-counterclockwise"></i> Reset
					</a>
					@if ($consistent)
					<a href="{{ route('bobotsubkriteria.pick') }}" class="btn btn-primary">
						<i class="bi bi-arrow-right"></i> Lanjut
					</a>
					@else
					<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#inconsistentModal">
						?
					</button>
					@endif
				</div>
				<form action="{{ route('bobotkriteria.reset') }}" method="POST" id="reset-kriteria">
					@csrf
					@method('DELETE')
				</form>
			</div>
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
			if (result.isConfirmed) {
				document.getElementById('reset-kriteria').submit();
				$('.spinner-grow').removeClass('d-none');
			}
		});
	});
</script>
@endsection