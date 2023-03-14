@extends('layout')
@section('title', 'Hasil Penilaian Alternatif')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Hasil Penilaian Alternatif</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Analisa</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria/Alternatif</th>
									@foreach ($kr as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($alt as $alter)
									@php($anal=$hasil->where('alternatif_id','=',$alter->id)->all())
									@if(count($anal)>0)
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
					<h4 class="card-title">Normalisasi Terbobot</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria/Alternatif</th>
									@foreach ($kr as $krit)
										<th>{{ $krit->name }}</th>
									@endforeach
									<th>Jumlah Baris</th>
									<th>Eigen</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($alt as $alter)
									@php($anal=$hasil->where('alternatif_id','=',$alter->id)->all())
									@if(count($anal)>0)
									<tr>
										<th>{{ $alter->name }}</th>
										{{-- @foreach ($data['matriks_perbandingan'] as $mp)
											@if ($mp['kode_kriteria'] == $kr->idkriteria)
												<td>{{ $mp['nilai'] }}</td>
											@endif
										@endforeach --}}
										<td></td>
									</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
