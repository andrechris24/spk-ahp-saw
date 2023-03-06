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
						Weighting dan Analytial Hierarchy Process.
					</p>
					<p>
						In case you want the navbar to be sticky on top while
						scrolling, add <code>.navbar-fixed</code> class alongside
						with <code>.layout-navbar</code> class.
					</p>
				</div>
			</div>
		</section>
	</div>
@endsection
