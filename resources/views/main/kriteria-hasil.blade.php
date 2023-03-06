@extends('layout')
@section('title', 'Hasil Perbandingan Kriteria')
@php
use App\Http\Controllers\KriteriaCompController;
@endphp
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Hasil Perbandingan Kriteria</h3>
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
					<h4 class="card-title">Matriks Perbandingan Awal</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									<th>...</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>...</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Matriks Nilai Perbandingan</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									<th>...
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Jumlah</td>
									<td>...</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Normalisasi dan Eigen</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Kriteria</th>
									<th>...</th>
									<th>Jumlah Baris</th>
									<th>Eigen</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<div class="card-title">Nilai Konsistensi</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<tr>
								<td>Consistency Vector</td>
								<td></td>
							</tr>
							<tr>
								<td>Rata-rata Consistency Vector</td>
								<td></td>
							</tr>
							<tr>
								<td>Consistency Index (CI)</td>
								<td></td>
							</tr>
							<tr>
								<td>Consistency Ratio (CR)</td>
								<td></td>
							</tr>
							<tr>
								<td>Hasil Konsistensi</td>
								<td>
									@if (true)
										<span class="text-success"><b>Konsisten</b></span>
									@else
										<span class="text-danger">
											<b>Tidak Konsisten</b>, mohon untuk menginput ulang perbandingan!
										</span>
									@endif
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
