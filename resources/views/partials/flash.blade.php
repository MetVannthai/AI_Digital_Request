@if (session('success'))
	<div class="mb-5 rounded-lg bg-emerald-50 p-4 text-emerald-700">{{ session('success') }}</div>
@endif

@if (session('error'))
	<div class="mb-5 rounded-lg bg-red-50 p-4 text-red-700">{{ session('error') }}</div>
@endif

@if ($errors->any())
	<div class="mb-5 rounded-lg bg-red-50 p-4 text-red-700">
		<ul class="list-disc pl-5">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
