@extends('layout')
@php
	use App\Http\Controllers\SubKriteriaCompController;
	$subkriteriacomp = new SubKriteriaCompController();
	$title = $subkriteriacomp->nama_kriteria($kriteria_id);
@endphp
@section('title', 'Perbandingan Sub Kriteria ' . $title)
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Perbandingan Sub Kriteria {{ $title }}</h3>
		</div>
		<section class="section">
			@include('main.message')
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">
						Masukkan Perbandingan Sub Kriteria {{ $title }}
					</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						@if($cek>0)
						<a href="{{ url('bobot/sub/hasil/' . $kriteria_id) }}"
							class="btn btn-primary mb-3">
							Lihat hasil
						</a>
						@endif
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
								@include('main.ahp')
							</div>
							<div class="tab-pane fade" id="input" role="tabpanel"
								aria-labelledby="input-tab">
								@if (count($array) > 0)
									<div class="table-responsive">
										<form method="post" enctype="multipart/form-data"
											action="{{ url('bobot/sub/comp') }}">
											@csrf
											<input type="hidden" name="kriteria_id" value="{{ $kriteria_id }}">
											<table class="table table-lg table-hover text-center">
												<thead>
													<tr>
														<th>Sub Kriteria</th>
														<th>Perbandingan</th>
														<th>Sub Kriteria</th>
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
										Masukkan data <a href="{{ url('kriteria/sub') }}">Sub Kriteria</a>
										dulu untuk melakukan perbandingan.
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
