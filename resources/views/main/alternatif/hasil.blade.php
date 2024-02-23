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
						<th>Alternatif</th>
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
						<th>Alternatif</th>
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
							$result = $saw->normalisasi(
							$arrays, $nilai->kriteria->type, $nilai->subkriteria->bobot
							);
							$lresult[$alts->id][$counter] = $result * $saw->getBobot($nilai->kriteria_id);
							$counter++;
							echo $result;
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
<div class="modal fade text-left" id="RankModal" tabindex="-1" role="dialog" aria-labelledby="RankLabel"
	aria-hidden="true">
	<div @class(['modal-dialog', 'modal-dialog-centered', 'modal-dialog-scrollable', 'modal-fullscreen-md-down'=> $totalalts <= 5, 
		'modal-fullscreen-lg-down'=> $totalalts > 5 && $totalalts <=10, 'modal-lg'=> $totalalts > 5 && $totalalts <= 10, 
		'modal-fullscreen-xl-down'=> $totalalts > 10 && $totalalts <= 18, 'modal-xl'=> $totalalts > 10 && $totalalts <= 18, 
		'modal-fullscreen'=>$totalalts>18]) role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="RankLabel">Grafik hasil penilaian</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<i data-feather="x"></i>
				</button>
			</div>
			<div class="modal-body">
				<div id="chart-ranking"></div>
				<p>Jadi, nilai tertingginya diraih oleh
					<b><span id="SkorTertinggi">...</span></b>
					(A<span id="AltID">x</span>) dengan nilai
					<b><span id="SkorHasil">...</span></b>
				</p>
			</div>
			<div class="modal-footer">
				<div class="spinner-grow text-primary" role="status">
					<span class="visually-hidden">Memuat grafik...</span>
				</div>
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
		<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#RankModal"
			id="spare-button">
			<i class="bi bi-bar-chart-line-fill"></i> Lihat Grafik
		</button>
		<table class="table table-hover table-striped text-center" id="table-hasil" style="width: 100%">
			<thead class="text-center">
				<tr>
					<th>Alternatif</th>
					@foreach ($data['kriteria'] as $krit)
					<th data-bs-toggle="tooltip" title="{{ $krit->name }}">
						C{{ $krit->id }} <span class="visually-hidden">{{ $krit->name }}</span>
					</th>
					@endforeach
					<th>Jumlah</th>
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
						A{{ $alts->id }} <span class="visually-hidden">{{ $alts->name }}</span>
					</th>
					@foreach ($lresult[$alts->id] as $datas)
					<td>{{ round($datas, 5) }}</td>
					@php $jml += round($datas, 5); @endphp
					@endforeach
					<td class="text-info">
						@php $saw->simpanHasil($alts->id, $jml); @endphp
						{{ $jml }}
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
	let dt_hasil = $('#table-hasil'), loaded = false, errmsg;
	const options = {
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
				@foreach($data['alternatif'] as $alts)
				["A{{ $alts->id }}", "{{ $alts->name }}"],
				@endforeach
			]
		},
		plotOptions: {
			bar: {
				distributed: true
			}
		}
	};
	const chart = new ApexCharts(document.querySelector("#chart-ranking"), options);
	$(document).ready(function () {
		try {
			$.fn.dataTable.ext.errMode = "none";
			dt_hasil = dt_hasil.DataTable({
				lengthChange: false,
				searching: false,
				responsive: true,
				order: [[1 + {{ count($data['kriteria']) }}, 'desc']],
				language: {
					url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
				},
				columnDefs: [{//Alternatif
					targets: 0,
					type: "natural"
				},
				@foreach($data['kriteria'] as $krit)
				{//Nilai Kriteria
					targets: 1 + {{ $loop->index }},
					render: function (data) { return parseFloat(data); }
				},
				@endforeach
				{ //Jumlah
					targets: -1,
					render: function (data) { return parseFloat(data); }
				}],
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
					text: '<i class="bi bi-printer me-2"></i> Cetak',
					className: 'btn dropdown-toggle',
					buttons: [{
						extend: 'print',
						title: 'Alternatif Tertinggi',
						text: '<i class="bi bi-person me-2"></i> Alternatif saja',
						className: 'dropdown-item',
						exportOptions: {
							columns: [0],
							format: {
								body: function (inner) {
									return inner.substring(inner.indexOf('>') + 1);
								}
							}
						},
						customize: function (win) {
							$(win.document.body).find('table').addClass('table-bordered')
								.removeClass('table-hover table-striped text-center')
								.css('width', '');
						}
					}, {
						extend: 'print',
						title: 'Hasil Penilaian',
						text: '<i class="bi bi-clipboard-data me-2"></i> Semua data',
						className: 'dropdown-item',
						exportOptions: {
							format: {
								header: function (data) {
									if (data.indexOf('C') === 7)
										return data.substring(data.indexOf('>') + 1);
									return data;
								},
								body: function (inner, coldex, rowdex) {
									if (rowdex === 0)
										return inner.substring(inner.indexOf('>') + 1);
									return inner;
								}
							}
						},
						customize: function (win) {
							$(win.document.body).find('table').addClass('table-bordered')
								.removeClass('table-hover table-striped text-center')
								.css('width', '');
						}
					}]
				}]
			}).on('draw', setTableColor).on('init.dt', function () {
				$('#spare-button').addClass('d-none');
			}).on('error.dt', function (e, settings, techNote, message) {
				errorDT(message, techNote);
			});
		} catch (dterr) {
			swal.fire({
				icon: 'error',
				title: "Gagal mengurutkan hasil penilaian"
			});
			console.error(dterr.message);
		}
	});
	chart.render();
	$('#RankModal').on('show.bs.modal', function () {
		if (!loaded) {
			$.getJSON("{{ route('hasil.ranking') }}", function (response) {
				$('#SkorHasil').text(response.score);
				$('#SkorTertinggi').text(response.nama);
				$('#AltID').text(response.alt_id);
				chart.updateSeries([{
					name: 'Nilai',
					data: response.result.skor
				}]);
				loaded = true;
			}).fail(function (xhr, st) {
				if (xhr.status === 400) errmsg = xhr.responseJSON.message;
				else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status}. ${xhr.statusText}`;
				}
				swal.fire({
					title: 'Gagal memuat grafik',
					text: errmsg,
					icon: 'error'
				});
			}).always(function(){
				$(".spinner-grow").addClass("d-none");
			});
		}
	}).on('hidden.bs.modal',function(){
		if(!loaded) $(".spinner-grow").removeClass("d-none");
	});
</script>
@endsection