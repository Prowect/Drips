<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>{{ config('app.name') }}</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	@stack('styles')
</head>
<body>
	<div id="app">
		<header id="main-header">
		
		</header>

		<div id="main-container">
			<div class="container">
				@yield('content')
			</div>
		</div>

		<footer id="main-footer">
			
		</footer>
	</div>
	<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
	@stack('scripts')
</body>
</html>