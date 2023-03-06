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
								@if ($total > 1)
									<div class="table-responsive">
										<form method="post" enctype="multipart/form-data" action="{{ url('bobot') }}">
											@csrf
											<table class="table table-lg table-hover">
												<thead>
													<tr>
														<th colspan="2">Pilih yang lebih penting</th>
														<th>Nilai</th>
													</tr>
												</thead>
												<tbody>
													@php
														$urutan = 0;
														foreach ($crit as $criterias):
														  $pilihan[] = $criterias->name;
														endforeach;
													@endphp
													@for ($a = 0; $a < $total - 1; $a++)
														@for ($b = 0; $b < $total; $b++)
															<tr>
																<td>
																	<div class="form-check">
																		<input class="form-check-input" type="radio"
																			name="pilihan[{{ $urutan }}]" id="pilihan-1.{{ $urutan }}"
																			value="1" required />
																		<label class="form-check-label" for="pilihan-1.{{ $urutan }}">
																			{{ $pilihan[$a] }}
																		</label>
																	</div>
																</td>
																<td>
																	<div class="form-check">
																		<input class="form-check-input" type="radio"
																			name="pilihan[{{ $urutan }}]" id="pilihan-2.{{ $urutan }}"
																			value="2" />
																		<label class="form-check-label" for="pilihan-2.{{ $urutan }}">
																			{{ $pilihan[$b] }}
																		</label>
																	</div>
																</td>
																<td>
																	@if ($pilihan[$a] == $pilihan[$b])
																		<input type="number" name="bobot[{{ $urutan }}]"
																			class="form-control" value="1" readonly>
																	@else
																		<input type="number" name="bobot[{{ $urutan }}]"
																			class="form-control" value="" min="1"
																			max="9" required>
																	@endif
																</td>
															</tr>
															@php($urutan++)
														@endfor
													@endfor
												</tbody>
											</table>
											<input type="submit" name="submit" class="btn btn-primary">
										</form>
									</div>
								@else
									<div class="alert alert-warning">
										Masukkan data <a href="{{ url('kriteria') }}">Kriteria</a> dulu untuk
										melakukan perbandingan (Minimal 2).
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
