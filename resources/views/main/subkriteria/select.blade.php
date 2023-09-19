@extends('layout')
@section('title', 'Perbandingan Sub Kriteria')
@section('content')
	<div class="page-heading">
		<div class="page-title">
			<h3>Perbandingan Sub Kriteria</h3>
		</div>
		<section class="section">
			@include('components.message')
			<div class="card">
				<div class="card-header">Pilih Kriteria</div>
				<div class="card-body">
					<form action="{{ url('/bobot/sub/comp') }}">
						<div class="input-group mb-3">
							<label class="input-group-text" for="kriteria">
								Kriteria
							</label>
							<select class="form-select @error('kriteria_id') is-invalid @enderror "
								id="kriteria" name="kriteria_id" required>
								<option value="">Pilih</option>
								@foreach ($allkrit as $kr)
									<option value="{{ $kr->id }}">{{ $kr->name }}</option>
								@endforeach
							</select>
							@error('kriteria_id')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
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
