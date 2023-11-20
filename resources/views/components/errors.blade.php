@if ($errors->any())
	<div class="alert alert-danger alert-dismissible">
		<i class="bi bi-x-circle-fill"></i> Gagal:
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ ucfirst($error) }}</li>
			@endforeach
		</ul>
		<button type="button" class="btn-close" data-bs-dismiss="alert"
			aria-label="Close"></button>
	</div>
@endif
