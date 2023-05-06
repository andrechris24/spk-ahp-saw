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
										<form method="post" enctype="multipart/form-data"
											action="{{ url('bobot') }}">@csrf
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
														@if ($krit['baris'] == $krit['kolom'])
														<input type="hidden" name="kriteria[{{ $loop->index }}]" value="equal" required>
														<input type="hidden" name="skala[{{$loop->index}}]" value="1">
														@else
														<tr>
															<th>
																<div>
																	<label class="form-check-label" for="left-{{$loop->index}}">
																	{{ $krit['baris'] }}
																	</label>
																	<input type="radio" name="kriteria[{{ $loop->index }}]" class="form-check-input" value="left" id="left-{{$loop->index}}" required>
																</div>
															</th>
															<td>
																<div class="input-group mb-3">
																	<input type="number" name="skala[{{$loop->index}}]" min="1" max="9" class="form-control text-center" required>
																</div>
															</td>
															<th>
																<div>
																	<input type="radio" name="kriteria[{{ $loop->index }}]" class="form-check-input" value="right" id="right-{{$loop->index}}">
																	<label class="form-check-label" for="right-{{$loop->index}}">
																	{{ $krit['kolom'] }}
																	</label>
																</div>
															</th>
														</tr>
														@endif
													@endforeach
												</tbody>
											</table>
											<div class="col-12 d-flex justify-content-end">
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

@section('js')
	<script type="text/javascript">
		function setscale(comp, idx) {
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
