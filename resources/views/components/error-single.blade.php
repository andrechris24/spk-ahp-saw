@if (Session::has('error'))
	<div class="alert alert-danger alert-dismissible" role="alert">
		<i class="bi bi-x-circle-fill"></i>
		{{ ucfirst(Session::get('error')) }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"
			aria-label="Close"></button>
	</div>
@endif
