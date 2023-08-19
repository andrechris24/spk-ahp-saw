@if (Session::has('error') || $errors->any())
	<div class="alert alert-danger alert-dismissible" role="alert">
		<i class="bi bi-x-circle-fill"></i>
		@if (Session::has('error'))
			{{ ucfirst(Session::get('error')) }}
		@else
			Gagal:
		@endif
		@if ($errors->any())
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
