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
			<div class="accordion" id="cardAccordion">
                        <div class="card">
                          <div
                            class="card-header"
                            id="headingOne"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseOne"
                            aria-expanded="false"
                            aria-controls="collapseOne"
                            role="button"
                          >
                            <span class="collapsed collapse-title"
                              >Accordion Item 1</span
                            >
                          </div>
                          <div
                            id="collapseOne"
                            class="collapse pt-1"
                            aria-labelledby="headingOne"
                            data-parent="#cardAccordion"
                          >
                            <div class="card-body">
                              Cheesecake muffin cupcake dragée lemon drops
                              tiramisu cake gummies chocolate cake. Marshmallow
                              tart croissant. Tart dessert tiramisu marzipan
                              lollipop lemon drops.
                            </div>
                          </div>
                        </div>
                        <div class="card collapse-header">
                          <div
                            class="card-header"
                            id="headingTwo"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo"
                            aria-expanded="false"
                            aria-controls="collapseTwo"
                            role="button"
                          >
                            <span class="collapsed collapse-title"
                              >Accordion Item 2</span
                            >
                          </div>
                          <div
                            id="collapseTwo"
                            class="collapse pt-1"
                            aria-labelledby="headingTwo"
                            data-parent="#cardAccordion"
                          >
                            <div class="card-body">
                              Pastry pudding cookie toffee bonbon jujubes
                              jujubes powder topping. Jelly beans gummi bears
                              sweet roll bonbon muffin liquorice. Wafer lollipop
                              sesame snaps.
                            </div>
                          </div>
                        </div>
                        <div class="card open">
                          <div
                            class="card-header"
                            id="headingThree"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseThree"
                            aria-expanded="true"
                            aria-controls="collapseThree"
                            role="button"
                          >
                            <span class="collapsed collapse-title"
                              >Accordion Item 3</span
                            >
                          </div>
                          <div
                            id="collapseThree"
                            class="collapse show pt-1"
                            aria-labelledby="headingThree"
                            data-parent="#cardAccordion"
                          >
                            <div class="card-body">
                              Sweet pie candy jelly. Sesame snaps biscuit sugar
                              plum. Sweet roll topping fruitcake. Caramels
                              liquorice biscuit ice cream fruitcake cotton candy
                              tart.
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div
                            class="card-header"
                            id="headingFour"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFour"
                            aria-expanded="false"
                            aria-controls="collapseFour"
                            role="button"
                          >
                            <span class="collapsed collapse-title"
                              >Accordion Item 4</span
                            >
                          </div>
                          <div
                            id="collapseFour"
                            class="collapse pt-1"
                            aria-labelledby="headingFour"
                            data-parent="#cardAccordion"
                          >
                            <div class="card-body">
                              Sweet pie candy jelly. Sesame snaps biscuit sugar
                              plum. Sweet roll topping fruitcake. Caramels
                              liquorice biscuit ice cream fruitcake cotton candy
                              tart.
                            </div>
                          </div>
                        </div>
                      </div>
		</section>
	</div>
@endsection
