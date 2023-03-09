@extends('layout')
@section('title', 'Perbandingan Kriteria')
<?php use App\Http\Controllers\KriteriaCompController; ?>
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Perbandingan Kriteria</h3>
			</div>
		</div>
		<section class="section">
			@if (Session::has('error') || $errors->any())
				<div class="alert alert-danger alert-dismissible" role="alert">
					<i class="bi bi-x-circle-fill"></i>
					@if (Session::has('error'))
						{{ ucfirst(Session::get('error')) }}
					@elseif($errors->any())
						Gagal:
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ ucfirst($error) }}</li>
							@endforeach
						</ul>
					@endif
					<button type="button" class="btn-close" data-bs-dismiss="alert"
						aria-label="Close"></button>
				</div>
			@endif
			@if (Session::has('warning'))
				<div class="alert alert-warning alert-dismissible" role="alert">
					<i class="bi bi-exclamation-triangle-fill"></i>
					{{ Session::get('warning') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"
						aria-label="Close"></button>
				</div>
			@endif
			@if (Session::has('success'))
				<div class="alert alert-success alert-dismissible" role="alert">
					<i class="bi bi-check-circle-fill"></i>
					{{ Session::get('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"
						aria-label="Close"></button>
				</div>
			@endif
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Masukkan Perbandingan</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<a href="{{ url('bobot/hasil') }}" class="btn btn-primary mb-3">
							Lihat hasil
						</a>
						<ul class="nav nav-tabs" id="InputCompTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info"
									role="tab" aria-controls="info" aria-selected="true">
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
								<div class="table-responsive">
									<table class="table table-hover table-lg">
										<thead>
											<tr>
												<th>Keterangan</th>
												<th>Nilai</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Kedua elemen sama pentingnya</td>
												<td>1</td>
											</tr>
											<tr>
												<td>
													Satu elemen sedikit lebih penting daripada elemen lain
												</td>
												<td>3</td>
											</tr>
											<tr>
												<td>Satu elemen lebih penting daripada elemen lain</td>
												<td>5</td>
											</tr>
											<tr>
												<td>
													Satu elemen lebih mutlak penting daripada elemen lain
												</td>
												<td>7</td>
											</tr>
											<tr>
												<td>Satu elemen mutlak penting daripada elemen lain</td>
												<td>9</td>
											</tr>
											<tr>
												<td>Nilai antara dua pertimbangan yang berdekatan</td>
												<td>2, 4, 6, 8</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="input" role="tabpanel"
								aria-labelledby="input-tab">
								@if (count($array) > 0)
									<div class="table-responsive">
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
																<div class="input-group mb-3">
																	@if ($krit['baris'] == $krit['kolom'])
																		<input type="number" name="baris[]"
																			class="form-control text-center" min="1" max="9"
																			value="1" readonly />
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				Banding
																			</span>
																		</div>
																		<input type="number" name="kolom[]"
																			class="form-control text-center" value="1" min="1"
																			max="9" readonly />
																	@else
																		<input type="number" name="baris[]"
																			class="form-control text-center" min="1" max="9"
																			required />
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				Banding
																			</span>
																		</div>
																		<input type="number" name="kolom[]"
																			class="form-control text-center" min="1" max="9"
																			required />
																	@endif
																</div>
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
									<div class="alert alert-warning">
										Masukkan data <a href="{{ url('kriteria') }}">Kriteria</a> dulu untuk
										melakukan perbandingan.
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

@section('js')

@endsection
