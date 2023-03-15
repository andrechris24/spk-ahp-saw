@extends('layout')
@section('title','Ranking')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Ranking</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">Pilih Kriteria</div>
				<div class="card-body">
					<div id="chart-ranking"></div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
<script type="text/javascript">
	var optionsProfileVisit = {
  annotations: {
    position: "back",
  },
  dataLabels: {
    enabled: false,
  },
  chart: {
    type: "bar",
    height: 300,
  },
  fill: {
    opacity: 1,
  },
  plotOptions: {},
  series: [
    {
      name: "Nilai",
      data: [
      	@foreach($result as $score)
      	{{ $score->hasil }},
      	@endforeach
      ],
    },
  ],
  colors: "#435ebe",
  xaxis: {
    categories: [
    	@foreach($alt as $alts)
    	{{ $alts->name }},
    	@endforeach
    ],
  },
}
</script>
@endsection