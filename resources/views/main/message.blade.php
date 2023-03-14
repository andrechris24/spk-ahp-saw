<noscript>
	<div class="alert alert-danger text-center">
		<i class="fa-solid fa-triangle-exclamation"></i>
		Peringatan: JavaScript tidak bekerja.
		Beberapa fungsi tidak akan bekerja dengan baik.
	</div>
</noscript>
@if (Session::has('error') || $errors->any())
	<div class="alert alert-danger alert-dismissible" role="alert">
		<i class="bi bi-x-circle-fill"></i>
		@if (Session::has('error'))
			{{ ucfirst(Session::get('error')) }}
		@else
		 Gagal:
		@endif
		@if($errors->any())
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
