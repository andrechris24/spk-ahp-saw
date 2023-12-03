@php
	use App\Http\Controllers\NilaiController;
	$saw = new NilaiController();
	$totalalts = count($data['alternatif']);
@endphp
@extends('layout')
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
							<th colspan="{{ count($data['kriteria']) }}">Kriteria</th>
						</tr>
						<tr>
							@foreach ($data['kriteria'] as $krit)
								<th data-bs-toggle="tooltip" title="{{ $krit->name }}">
									C{{ $krit->id }}
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
									<th data-bs-toggle="tooltip" title="{{ $alter->name }}">
										A{{ $alter->id }}
									</th>
									@foreach ($anal as $skoralt)
										<td data-bs-toggle="tooltip" title="{{ $skoralt->subkriteria->name }}">
											{{ $skoralt->subkriteria->bobot }}
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
			<h4 class="card-title">Matriks Normalisasi</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-striped text-center">
					<thead>
						<tr>
							<th rowspan="2">Alternatif</th>
							<th colspan="{{ count($data['kriteria']) }}">Kriteria</th>
						</tr>
						<tr>
							@foreach ($data['kriteria'] as $krit)
								<th data-bs-toggle="tooltip" title="{{ $krit->name }}">
									C{{ $krit->id }}
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
									<th data-bs-toggle="tooltip" title="{{ $alts->name }}">
										A{{ $alts->id }}
									</th>
									@foreach ($norm as $nilai)
										<td>
											@php
												$arrays = $saw->getNilaiArr($nilai->kriteria_id);
												$result = $saw->normalisasi($arrays, $nilai->kriteria->type, $nilai->subkriteria->bobot);
												$lresult[$alts->id][$counter] = $result * $saw->getBobot($nilai->kriteria_id);
												echo $result;
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
		<div role="document" @class([
			'modal-dialog',
			'modal-dialog-centered',
			'modal-dialog-scrollable',
			'modal-fullscreen-md-down' => $totalalts <= 5,
			'modal-fullscreen-lg-down' => $totalalts > 5 && $totalalts <= 10,
			'modal-lg' => $totalalts > 5 && $totalalts <= 10,
			'modal-fullscreen-xl-down' => $totalalts > 10 && $totalalts <= 20,
			'modal-xl' => $totalalts > 10 && $totalalts <= 20,
			'modal-fullscreen' => $totalalts > 20
		])>
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="RankLabel">Grafik hasil penilaian</h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
			<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
				data-bs-target="#RankModal" id="spare-button">
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
							<th data-bs-toggle="tooltip" title="{{ $krit->name }}">
								C{{ $krit->id }}
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
								<th data-bs-toggle="tooltip" title="{{ $alts->name }}">
									A{{ $alts->id }}
								</th>
								@foreach ($lresult[$alts->id] as $datas)
									@php
										echo '<td>' . round($datas, 5) . '</td>';
										$jml += round($datas, 5);
									@endphp
								@endforeach
								<td class="text-info">
									@php
										$saw->simpanHasil($alts->id, $jml);
										echo $jml;
									@endphp
								</td>
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
		var dt_hasil, loaded=false;
		$(document).ready(function() {
			try {
				$.fn.dataTable.ext.errMode = "none";
				dt_hasil = $('#table-hasil').DataTable({
					lengthChange: false,
					searching: false,
					responsive: true,
					order: [
						[1 + {{ count($data['kriteria']) }}, 'desc']
					],
					language: {
						url: "{{ asset('assets/extensions/DataTables/DataTables-id.json') }}"
					},
					dom: 'Bfrtip',
					buttons: [{
						text: '<i class="bi bi-bar-chart-line-fill me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Lihat Grafik</span>',
						className: 'btn',
						attr: {
							'data-bs-toggle': 'modal',
							'data-bs-target': '#RankModal'
						}
					}, {
						extend: 'collection',
						text: '<i class="bi bi-download me-0 me-sm-1"></i> Ekspor',
						className: 'btn dropdown-toggle',
						buttons: [{
							extend: 'print',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-printer me-2"></i> Print',
							className: 'dropdown-item'
						}, {
							extend: 'csv',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-text me-2"></i> CSV',
							className: 'dropdown-item'
						}, {
							extend: 'excel',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-spreadsheet me-2"></i> Excel',
							className: 'dropdown-item'
						}, {
							extend: 'pdf',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-file-text me-2"></i> PDF',
							className: 'dropdown-item'
						}, {
							extend: 'copy',
							title: 'Nilai Alternatif',
							text: '<i class="bi bi-clipboard me-2"></i> Copy',
							className: 'dropdown-item'
						}]
					}]
				}).on('draw', setTableColor).on('init.dt', function() {
					$('#spare-button').addClass('d-none');
				}).on('error.dt', function(e, settings, techNote, message) {
					errorDT(message, techNote);
				});
			} catch (dterr) {
				Toastify({
					text: "Terjadi kesalahan saat mengurutkan hasil penilaian",
					style: {
						background: "#dc3545"
					},
					duration: 7000
				}).showToast();
				console.error(dterr.message);
			}
		});
		var options = {
			chart: {
				height: 320,
				type: 'bar'
			},
			dataLabels: {
        enabled: true
      },
      legend: {
        show: false
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
						["A{{ $alts->id }}","{{$alts->name}}"],
					@endforeach
				]
			},
			plotOptions: {
		    bar: {
		      distributed: true
		    }
		  }  
		}
		var chart = new ApexCharts(
			document.querySelector("#chart-ranking"), options
		);
		chart.render();
		$('#RankModal').on('show.bs.modal', function() {
			if(!loaded){
				$.getJSON('{{ route('hasil.ranking') }}', function(response) {
					loaded=true;
					$('#SkorHasil').text(response.score);
					$('#SkorTertinggi').text(response.nama);
					chart.updateSeries([{
						name: 'Nilai',
						data: response.result.skor
					}]);
				}).fail(function(e, status) {
					Swal.fire({
						title: 'Gagal memuat grafik',
						text: 'Kesalahan HTTP ' + e.status + '. ' + status,
						icon: 'error',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					});
				});
			}
		});
	</script>
@endsection
