@extends('layout')
@section('title', 'Ranking')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Ranking</h3>
		</div>
		<section class="section">
			@include('components.message')
			<div class="card">
				<div class="card-header">Hasil akhir</div>
				<div class="card-body">
					<div id="chart-ranking"></div>
					Jadi, nilai tertingginya diraih oleh {{ $highest->alternatif->name }}
					dengan nilai {{ $highest->skor }}
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		var optionsRankPenilaian = {
			annotations: {
				position: "back",
			},
			dataLabels: {
				enabled: true,
			},
			chart: {
				type: "bar",
				height: 300,
			},
			fill: {
				opacity: 1,
			},
			plotOptions: {},
			series: [{
				name: "Nilai",
				data: [
					@foreach ($result as $score)
						{{ $score->skor }},
					@endforeach
				],
			}],
			// colors: "#33ff33",
			xaxis: {
				categories: [
					@foreach ($result as $alts)
						'{{ $alts->alternatif->name }}',
					@endforeach
				],
			},
		}
		var rankPenilaian = new ApexCharts(
			document.querySelector("#chart-ranking"),
			optionsRankPenilaian
		);
		rankPenilaian.render();
	</script>
@endsection
