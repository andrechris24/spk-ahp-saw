@extends('layout')
@section('title', 'Hasil Penilaian Alternatif')
@php
	use App\Http\Controllers\NilaiController;
	$saw = new NilaiController();
	$countkriteria = count($data['kriteria']);
@endphp
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Penilaian Alternatif</h3>
		</div>
		<section class="section">
			@include('components.error-multi')
			@include('components.warning')
			@include('components.success')
			@include('components.noscript')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Keputusan</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th rowspan="2">Alternatif</th>
									<th colspan="{{ $countkriteria }}">Kriteria</th>
								</tr>
								<tr>
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
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th rowspan="2">Alternatif</th>
									<th colspan="{{ $countkriteria }}">Kriteria</th>
								</tr>
								<tr>
									@foreach ($data['kriteria'] as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($data['alternatif'] as $alts)
									@php
										$counter = 0;
										$norm = $hasil->where('alternatif_id', '=', $alts->id)->all();
									@endphp
									@if (count($norm) > 0)
										<tr>
											<th>{{ $alts->name }}</th>
											@foreach ($norm as $nilai)
												<td>
													@php
														$arrays = $saw->getNilaiArr($nilai->kriteria_id);
														$result = $saw->normalisasi($arrays, $nilai->kriteria->type, $nilai->subkriteria->bobot);
														echo $result;
														$lresult[$alts->id][$counter] = $result * $saw->getBobot($nilai->kriteria_id);
														$counter++;
													@endphp
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
					<a href="{{ route('ranking.show') }}" class="btn btn-primary mb-3">
						Lihat Grafik
					</a>
					<table class="table table-hover text-center" id="table-hasil"
						style="width: 100%">
						<thead class="text-center">
							<tr>
								<th rowspan="2">Alternatif</th>
								<th colspan="{{ count($data['kriteria']) }}">Kriteria</th>
								<th rowspan="2">Jumlah</th>
							</tr>
							<tr>
								@foreach ($data['kriteria'] as $krit)
									<th>{{ $krit->name }}</th>
								@endforeach
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
												@php
													echo round($datas, 5);
													$jml += round($datas, 5);
												@endphp
											</td>
										@endforeach
										@php($saw->simpanHasil($alts->id, $jml))
										<td>{{ $jml }}</td>
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
@section('js')
	<script type="text/javascript">
		var dt_hasil;
		$(document).ready(function() {
			try {
				dt_hasil = $('#table-hasil').DataTable({
					"lengthChange": false,
					"searching": false,
					responsive: true,
					order: [
						[1 + {{ $countkriteria }}, 'desc']
					],
					language: {
						url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
					}
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 7000,
					backgroundColor: "#dc3545"
				}).showToast();
			}
			dt_hasil.on('draw', setTableColor);
		});
	</script>
@endsection
