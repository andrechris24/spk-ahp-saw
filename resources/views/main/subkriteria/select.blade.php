@extends('layout')
@section('title', 'Perbandingan Sub Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Perbandingan Sub Kriteria</h3>
		</div>
		<section class="section">
			@include('main.message')
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
