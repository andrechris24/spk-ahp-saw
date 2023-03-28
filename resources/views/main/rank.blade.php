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
					Jadi, nilai tertingginya (3 besar) adalah:
					<ol>
						@foreach ($highest as $top3)
							<li>{{ $top3->alternatif->name }} dengan nilai {{ $top3->skor }}</li>
						@endforeach
					</ol>
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
