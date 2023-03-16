@extends('layout')
@section('title', 'Hasil Penilaian Alternatif')
@php
	use App\Http\Controllers\NilaiController;
	$saw = new NilaiController();
	$simpan = false;
@endphp
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Penilaian Alternatif</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Keputusan</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Alternatif\Kriteria</th>
									@foreach ($data['kriteria'] as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['alternatif'] as $alter)
									@php
										$anal = $hasil->where('alternatif_id', '=', $alter->id)->all();
									@endphp
									@if (count($anal) > 0)
										<tr>
											<th>{{ $alter->name }}</th>
											@foreach ($anal as $skoralt)
												<td>{{ $skoralt->subkriteria->bobot }}</td>
											@endforeach
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Normalisasi</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Alternatif\Kriteria</th>
									@foreach ($data['kriteria'] as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['alternatif'] as $alts)
									<?php $counter = 0;
									$norm = $hasil->where('alternatif_id', '=', $alts->id)->all();
									?>
									@if (count($norm) > 0)
										<tr>
											<th>{{ $alts->name }}</th>
											@foreach ($norm as $nilai)
												<td>
													<?php $arrays = $saw->getNilaiArr($nilai->kriteria_id);
													$result = $saw->normalisasi($arrays, $nilai->kriteria->type, $nilai->subkriteria->bobot);
													echo $result;
													$lresult[$alts->id][$counter] = $result * $saw->getBobot($nilai->kriteria_id);
													$counter++; ?>
												</td>
											@endforeach
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Ranking</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Alternatif\Kriteria</th>
									@foreach ($data['kriteria'] as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
									<th>Jumlah</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data['alternatif'] as $alts)
									@php
										$rank = $hasil->where('alternatif_id', '=', $alts->id)->all();
										$jml = 0;
									@endphp
									@if (count($rank) > 0)
										<tr>
											<th>{{ $alts->name }}</th>
											@foreach ($lresult[$alts->id] as $datas)
												<td>
													<?php echo round($datas, 5);
													$jml += round($datas, 5); ?>
												</td>
											@endforeach
											@php($simpan = $saw->simpanHasil($alts->id, $jml))
											<td>{{ $jml }}</td>
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
					@if (!$simpan)
						<div class="alert alert-warning">
							Satu atau lebih hasil tidak tersimpan
						</div>
					@endif
				</div>
			</div>
		</section>
	</div>
@endsection
