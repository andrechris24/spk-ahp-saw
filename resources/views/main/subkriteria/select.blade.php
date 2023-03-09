@extends('layout')
@section('title', 'Sub Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Perbandingan Sub Kriteria</h3>
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
				<div class="card-header">Pilih Kriteria</div>
				<div class="card-body">
					<form method="get" action="{{ url('bobot/sub/comp') }}">
						{{-- @csrf --}}
						<div class="input-group mb-3">
							<label class="input-group-text" for="kriteria">
								Kriteria
							</label>
							<select class="form-select" id="kriteria" name="kriteria_id" required>
								<option value="">Pilih</option>
								@foreach ($allkrit as $kr)
									<option value="{{ $kr->id }}">{{ $kr->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-check">
							<div class="checkbox">
								<input type="checkbox" id="jump" class="form-check-input" name="lompat"/>
								<label for="jump">Lompat ke hasil</label>
							</div>
						</div>
						<button type="submit" class="btn btn-primary ml-1">
							<i class="bi bi-arrow-right"></i>
							Lanjut
						</button>
					</form>
				</div>
			</div>
		</section>
	</div>
@endsection
