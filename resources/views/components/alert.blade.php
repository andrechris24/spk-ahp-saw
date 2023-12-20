@if (Session::has($type))
	<div @class(['alert', 'alert-danger' => $type === 'error',
		'alert-success' => $type === 'success',
		'alert-warning' => $type === 'warning', 'alert-dismissible'])>
		<i class="{{ $icon }}"></i> {{ ucfirst(Session::get($type)) }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"
			aria-label="Close"></button>
	</div>
@endif
