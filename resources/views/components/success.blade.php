@if (Session::has('success'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<i class="bi bi-check-circle-fill"></i>
		{{ Session::get('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"
			aria-label="Close"></button>
	</div>
@endif
