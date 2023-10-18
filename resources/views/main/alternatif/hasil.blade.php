@extends('layout')
@php
	use App\Http\Controllers\NilaiController;
	$saw = new NilaiController();
	$countkriteria = count($data['kriteria']);
@endphp
@section('title', 'Hasil Penilaian Alternatif')
@section('subtitle', 'Hasil Penilaian Alternatif')
@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Matriks Keputusan</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-striped text-center">
					<thead>
						<tr>
							<th rowspan="2">Alternatif</th>
							<th colspan="{{ $countkriteria }}">Kriteria</th>
						</tr>
						<tr>
							@foreach ($data['kriteria'] as $krit)
								<th data-bs-toggle="tooltip" title="{{ $krit->desc }}">
									{{ $krit->name }}
								</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach ($data['alternatif'] as $alter)
							@php
								$anal = $hasil->where('alternatif_id', $alter->id)->all();
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
				<table class="table table-hover table-striped text-center">
					<thead>
						<tr>
							<th rowspan="2">Alternatif</th>
							<th colspan="{{ $countkriteria }}">Kriteria</th>
						</tr>
						<tr>
							@foreach ($data['kriteria'] as $krit)
								<th data-bs-toggle="tooltip" title="{{ $krit->desc }}">
									{{ $krit->name }}
								</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach ($data['alternatif'] as $alts)
							@php
								$counter = 0;
								$norm = $hasil->where('alternatif_id', $alts->id)->all();
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
	<div class="modal fade text-left" id="RankModal" tabindex="-1" role="dialog"
		aria-labelledby="RankLabel" aria-hidden="true">
		<div
			class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down modal-lg"
			role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="RankLabel">Grafik hasil penilaian</h4>
					<button type="button" class="close" data-bs-dismiss="modal"
						aria-label="Close">
						<i data-feather="x"></i>
					</button>
				</div>
				<div class="modal-body">
					<div id="chart-ranking"></div>
					Jadi, nilai tertingginya diraih oleh <span id="SkorTertinggi">...</span>
					dengan nilai <span id="SkorHasil">...</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">
						Tutup
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Ranking</h4>
		</div>
		<div class="card-body">
			<button type="button" class="btn btn-primary mb-3 d-none"
				data-bs-toggle="modal" data-bs-target="#RankModal" id="spare-button">
				<i class="bi bi-bar-chart-line-fill"></i> Lihat Grafik
			</button>
			<table class="table table-hover table-striped text-center" id="table-hasil"
				style="width: 100%">
				<thead class="text-center">
					<tr>
						<th rowspan="2">Alternatif</th>
						<th colspan="{{ count($data['kriteria']) }}">Kriteria</th>
						<th rowspan="2">Jumlah</th>
					</tr>
					<tr>
						@foreach ($data['kriteria'] as $krit)
							<th data-bs-toggle="tooltip" title="{{ $krit->desc }}">
								{{ $krit->name }}
							</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($data['alternatif'] as $alts)
						@php
							$rank = $hasil->where('alternatif_id', $alts->id)->all();
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
								@php
									$saw->simpanHasil($alts->id, $jml);
									echo "<td>$jml</td>";
								@endphp
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
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
					},
					dom: 'Bfrtip',
					buttons: [{
							text: '<i class="bi bi-bar-chart-line-fill me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Lihat Grafik</span>',
							className: 'btn btn-primary',
							attr: {
								'data-bs-toggle': 'modal',
								'data-bs-target': '#RankModal'
							}
						},
						{
							extend: 'collection',
							text: '<i class="bi bi-download me-0 me-sm-1"></i> Ekspor',
							className: 'btn btn-primary dropdown-toggle',
							buttons: [{
									extend: 'print',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-printer me-2"></i> Print',
									className: 'dropdown-item'
								},
								{
									extend: 'csv',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-text me-2"></i> CSV',
									className: 'dropdown-item'
								},
								{
									extend: 'excel',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-spreadsheet me-2"></i> Excel',
									className: 'dropdown-item'
								},
								{
									extend: 'pdf',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-file-text me-2"></i> PDF',
									className: 'dropdown-item'
								},
								{
									extend: 'copy',
									title: 'Nilai Alternatif',
									text: '<i class="bi bi-clipboard me-2"></i> Copy',
									className: 'dropdown-item'
								}
							]
						}
					]
				});
			} catch (dterr) {
				Toastify({
					text: "DataTables Error: " + dterr.message,
					duration: 8000,
					backgroundColor: "#dc3545"
				}).showToast();
				if (!$.fn.DataTable.isDataTable('#table-hasil'))
					$('#spare-button').removeClass('d-none');
			}
			dt_hasil.on('draw', setTableColor);
		});
		var options = {
			chart: {
				height: 320,
				type: 'bar'
			},
			dataLabels: {
				enabled: true
			},
			series: [],
			title: {
				text: 'Hasil Penilaian'
			},
			noData: {
				text: 'Memuat grafik...'
			},
			xaxis: {
				categories: [
					@foreach ($data['alternatif'] as $alts)
						"{{ $alts->name }}",
					@endforeach
				]
			}
		}
		var chart = new ApexCharts(
			document.querySelector("#chart-ranking"), options
		);
		chart.render();
		$('#RankModal').on('show.bs.modal', function() {
			$.getJSON('{{ route('hasil.ranking') }}', function(response) {
				$('#SkorHasil').text(response.score);
				$('#SkorTertinggi').text(response.nama);
				console.log(response.result);
				chart.updateSeries([{
					name: 'Nilai',
					data: response.result.skor
				}]);
			}).fail(function(e, status) {
				Swal.fire({
					title: 'Gagal memuat grafik',
					text: e.responseJSON.message ?? status,
					icon: 'error',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				});
			});
		});
	</script>
@endsection
