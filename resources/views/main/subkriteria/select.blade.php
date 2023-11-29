@extends('layout')
@section('title', 'Perbandingan Sub Kriteria')
@section('subtitle', 'Perbandingan Sub Kriteria')
@section('content')
	<div class="card">
		<div class="card-header">Pilih Kriteria</div>
		<div class="card-body">
			<form action="{{ route('bobotsubkriteria.index') }}" class="needs-validation">
				<div class="input-group has-validation mb-3">
					<label class="input-group-text" for="kriteria">Kriteria</label>
					<select class="form-select @error('kriteria_id') is-invalid @enderror " id="kriteria"
						name="kriteria_id" required>
						<option value="">Pilih</option>
						@foreach ($allkrit as $kr)
							<option value="{{ $kr->id }}"
								{{ old('kriteria_id') == $kr->id ? 'selected' : '' }}>
								{{ $kr->name }}
							</option>
						@endforeach
					</select>
					<div class="invalid-feedback">
						@error('kriteria_id')
							{{ $message }}
						@else
							Pilih salah satu Kriteria
						@enderror
					</div>
				</div>
				<button type="submit" class="btn btn-primary ml-1">
					<i class="bi bi-arrow-right"></i> Lanjut
				</button>
			</form>
		</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		function submitform(e) {
			$('#kriteria').removeClass('is-invalid');
		}
	</script>
