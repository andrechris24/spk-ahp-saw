@extends('layout')
@section('title', 'Perbandingan Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Perbandingan Kriteria</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Masukkan Perbandingan</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						@if ($cek > 0)
							<div class="alert alert-primary">
								<i class="bi bi-info-circle-fill"></i>
								Perbandingan kriteria sudah dilakukan,
								<a href="{{ url('bobot/hasil') }}">klik disini</a>
								untuk melihat hasil perbandingan
							</div>
						@endif
						<ul class="nav nav-tabs" id="InputCompTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="info-tab" data-bs-toggle="tab"
									href="#info" role="tab" aria-controls="info" aria-selected="true">
									Tabel Nilai Perbandingan
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="input-tab" data-bs-toggle="tab" href="#input"
									role="tab" aria-controls="input" aria-selected="false">
									Input Perbandingan
								</a>
							</li>
						</ul>
						<div class="tab-content" id="InputCompTabContent">
							<div class="tab-pane fade show active" id="info" role="tabpanel"
								aria-labelledby="info-tab">
								@include('main.ahp')
							</div>
							<div class="tab-pane fade" id="input" role="tabpanel"
								aria-labelledby="input-tab">
								@if ($jmlcrit >= 2)
									<div class="table-responsive">
										<div class="alert alert-info">
											Nilai perbandingan akan dihitung 1 jika Anda memasukkan skala 0.
										</div>
										<form method="post" enctype="multipart/form-data"
											action="{{ url('bobot') }}">
											@csrf
											<table class="table table-lg table-hover text-center">
												<thead>
													<tr>
														<th>Kriteria</th>
														<th>Perbandingan</th>
														<th>Kriteria</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($array as $krit)
														<tr>
															<th>{{ $krit['baris'] }}</th>
															<td>
																@if ($krit['baris'] == $krit['kolom'])
																	<input type="range" class="form-range" disabled>
																	<input type="hidden" name="banding[]" value="1">
																@else
																	<input type="range" name="banding[]" min="-9"
																		max="9" step="1" class="form-range"
																		oninput="this.nextElementSibling.value=this.value">
																@endif
																<output>0</output>
															</td>
															<th>{{ $krit['kolom'] }}</th>
														</tr>
													@endforeach
												</tbody>
											</table>
											<div class="p-4">
												<input type="submit" name="submit" class="btn btn-primary">
											</div>
										</form>
									</div>
								@else
									<div class="alert alert-warning mt-3">
										<i class="bi bi-sign-stop-fill"></i>
										Masukkan data <a href="{{ url('kriteria') }}">Kriteria</a>
										dulu (Minimal 2) untuk melakukan perbandingan.
										(Jumlah sekarang: {{ $jmlcrit }})
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
