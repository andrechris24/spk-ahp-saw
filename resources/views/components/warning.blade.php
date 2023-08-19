@if (Session::has('warning'))
	<div class="alert alert-warning alert-dismissible" role="alert">
		<i class="bi bi-exclamation-triangle-fill"></i>
		{{ Session::get('warning') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"
			aria-label="Close"></button>
	</div>
@endif
