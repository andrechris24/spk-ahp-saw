@extends('layout')
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
			<div class="row" id="basic-table">
				<div class="col-12 col-md-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Masukkan Perbandingan</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="table-responsive">
									<form method="post" enctype="multipart/form-data">
										@csrf
										<table class="table table-lg">
											<thead>
												<tr>
													<th colspan="2">Pilih yang lebih penting</th>
													<th>Nilai</th>
												</tr>
											</thead>
											<tbody>
												@if($total>0)
													@php
														$urutan=0;
														foreach($crit as $criterias)
															$pilihan[]=$criteria->name;
													@endphp
													@for($a=0;$a<($total-1);$a++)
														@for($b=0;$b<$total;$b++)
														@php($urutan++)
														<tr>
															<td>
																<div class="form-check">
																	<input class="form-check-input" type="radio"
																		name="pilihan[{{ $urutan }}]" id="pilihan-1"
																		value="1" checked/>
																	<label class="form-check-label" for="pilihan-1">
																		{{ $pilihan[$a] }}
																	</label>
																</div>
															</td>
															<td>
																<div class="form-check">
																	<input class="form-check-input" type="radio"
																		name="pilihan[{{ $urutan }}]" id="pilihan-2"
																		value="1" checked/>
																	<label class="form-check-label" for="pilihan-2">
																		{{ $pilihan[$b] }}
																	</label>
																</div>
															</td>
															<td>
																@php($nilai=getCriteriaComp($a,$b))
																<input type="number" name="bobot[{{ $urutan }}]" 
																class="form-control" value="{{ $nilai }}" max="9">
															</td>
														</tr>
														@endfor
													@endfor
												@endif
											</tbody>
										</table>
										@if($total>0)
										<input type="submit" name="submit" class="btn btn-primary">
										@endif
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Tabel Nilai Perbandingan menurut Saaty</h4>
						</div>
						<div class="card-content">
							<div class="table-responsive">
								<table class="table table-lg">
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
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')

@endsection