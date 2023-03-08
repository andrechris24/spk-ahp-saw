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
						Sistem ini akan membantu seseorang membuat keputusan dengan metode Simple Additive
						Weighting (SAW) dan Analytical Hierarchy Process (AHP).
					</p>
					<p>
						Perhitungan Kriteria dan Sub Kriteria menggunakan metode AHP, sedangkan perhitungan Alternatif menggunakan metode SAW.
					</p>
				</div>
			</div>
		</section>
	</div>
@endsection
