@extends('layout')
@section('title', 'Beranda')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Beranda</h3>
					<p class="text-subtitle text-muted">
						@auth
							Hai, {{ auth()->user()->name }}
						@endauth
						@guest
							Silahkan login untuk menggunakan Sistem Pendukung Keputusan
						@endguest
					</p>
				</div>
			</div>
		</div>
		<section class="section">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Selamat datang di Sistem Pendukung Keputusan</h4>
				</div>
				<div class="card-body">
					<p>
						Sistem ini akan membantu seseorang membuat keputusan dengan metode Simple
						Additive Weighting (SAW) dan Analytical Hierarchy Process (AHP).
					</p>
					<p>
						Perhitungan Kriteria dan Sub Kriteria menggunakan metode AHP, sedangkan
						perhitungan Alternatif menggunakan metode SAW.
					</p>
				</div>
			</div>
			@auth
				<div class="row">
					<div class="col-6 col-lg-4 col-md-6">
						<div class="card">
							<div class="card-body px-4 py-4-5">
								<div class="row">
									<div
										class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
										<div class="stats-icon purple mb-2">
											<i class="iconly-boldShow"></i>
										</div>
									</div>
									<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
										<h6 class="text-muted font-semibold">Jumlah Kriteria</h6>
										<h6 class="font-extrabold mb-0">{{ $jml['kriteria'] }}</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-4 col-md-6">
						<div class="card">
							<div class="card-body px-4 py-4-5">
								<div class="row">
									<div
										class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
										<div class="stats-icon blue mb-2">
											<i class="iconly-boldProfile"></i>
										</div>
									</div>
									<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
										<h6 class="text-muted font-semibold">Jumlah Sub Kriteria</h6>
										<h6 class="font-extrabold mb-0">{{ $jml['subkriteria'] }}</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-4 col-md-6">
						<div class="card">
							<div class="card-body px-4 py-4-5">
								<div class="row">
									<div
										class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
										<div class="stats-icon green mb-2">
											<i class="iconly-boldAdd-User"></i>
										</div>
									</div>
									<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
										<h6 class="text-muted font-semibold">Jumlah Alternatif</h6>
										<h6 class="font-extrabold mb-0">{{ $jml['alternatif'] }}</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endauth
		</section>
	</div>
@endsection
