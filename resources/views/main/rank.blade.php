@extends('layout')
@section('title', 'Ranking')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Ranking</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">Hasil akhir</div>
				<div class="card-body">
					<div id="chart-ranking"></div>
					Jadi, ranking tertingginya adalah {{ $highest->alternatif->name }}
					dengan skor {{ $highest->skor }}
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
			}, ],
			colors: "#435ebe",
			xaxis: {
				categories: [
					@foreach ($alt as $alts)
						'{{ $alts->name }}',
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
