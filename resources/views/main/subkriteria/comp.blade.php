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
						@if ($cek > 0)
							<div class="alert alert-primary">
								<i class="bi bi-info-circle-fill"></i>
								Perbandingan sub kriteria {{ $title }} sudah dilakukan,
								<a href="{{ url('bobot/sub/hasil/' . $kriteria_id) }}">klik disini</a>
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
								<a href="{{ url('/bobot/sub') }}" class="btn btn-secondary">
									<i class="bi bi-arrow-left"></i> Kembali
								</a>
							</div>
							<div class="tab-pane fade" id="input" role="tabpanel"
								aria-labelledby="input-tab">
								@if ($jmlsubkriteria >= 2)
									<div class="table-responsive">
										<form method="post" enctype="multipart/form-data"
											action="{{ url('bobot/sub/comp') }}">@csrf
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
														@if ($krit['baris'] == $krit['kolom'])
															<input type="hidden" name="kriteria[{{ $loop->index }}]"
																value="equal" required>
															<input type="hidden" name="skala[{{ $loop->index }}]"
																value="1">
														@else
															<tr>
																<th>
																	<input type="radio" class="btn-check" name="kriteria[{{ $loop->index }}]" id="left-{{ $loop->index }}" value="left" autocomplete="off" required>
																	<label class="btn btn-outline-light" for="left-{{ $loop->index }}">
																	{{ $krit['baris'] }}
																	</label>
																</th>
																<td>
																	<div class="input-group mb-3">
																		<input type="number" name="skala[{{ $loop->index }}]"
																			min="1" max="9" class="form-control text-center"
																			required>
																	</div>
																</td>
																<th>
																	<input type="radio" class="btn-check" name="kriteria[{{ $loop->index }}]" id="right-{{ $loop->index }}" value="right" autocomplete="off">
																	<label class="btn btn-outline-light" for="right-{{ $loop->index }}">
																	{{ $krit['kolom'] }}
																	</label>
																</th>
															</tr>
														@endif
													@endforeach
												</tbody>
											</table>
											<div class="col-12 d-flex justify-content-end">
												<div class="btn-group">
													<a href="{{ url('bobot/sub') }}" class="btn btn-secondary">
														<i class="bi bi-arrow-left"></i> Kembali
													</a>
													<input type="submit" name="submit" class="btn btn-primary">
												</div>
											</div>
										</form>
									</div>
								@else
									<div class="alert alert-warning mt-3">
										<i class="bi bi-sign-stop-fill"></i>
										Masukkan data <a href="{{ url('kriteria/sub') }}">Sub Kriteria</a>
										{{ $title }} dulu (Minimal 2) untuk melakukan perbandingan.
										(Jumlah sekarang: {{ $jmlsubkriteria }})
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
	<script type="text/javascript">
		function setsubscale(comp, idx) {
			if (comp === "col[" + idx + "]") document.getElementById("row[" + idx +
				"]").value = 1;
			else if (comp === "row[" + idx + "]")
				document.getElementById("col[" + idx + "]").value = 1;
		}
		const tabList = document.querySelectorAll(
			'#InputCompTab a[data-bs-toggle="tab"]');
		tabList.forEach(tabEl => {
			tabEl.addEventListener('shown.bs.tab', event => {
				location.hash = $(event.target).attr('href');
			});
		});
		var hash = location.hash;
		const triggerEl = document.querySelector('#InputCompTab a[href="' + hash +
			'"]');
		bootstrap.Tab.getOrCreateInstance(triggerEl).show();
	</script>
@endsection
