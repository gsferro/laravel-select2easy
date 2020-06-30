@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						Teste Select2 Easy
					</div>

					<div class="card-body">
						<label for="select2easy">Select2 Easy:</label>
						<select id="select2easy" name="select2easy" class="form-control select2easy"
								data-sl2-hash="{{ \Illuminate\Support\Facades\Crypt::encryptString("App\User") }}"
								data-sl2-model="App\User"
								data-sl2-method="sl2"
						>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<link href="{{ asset('vendor/select2easy/select2/css/select2.css') }}" rel="stylesheet">
	{{--<script type="text/javascript" src="{{ asset('vendor/select2easy/js/jquery-3.2.1.min.js') }}"></script>--}}
	<script type="text/javascript" src="{{ asset('vendor/select2easy/select2/js/select2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('vendor/select2easy/js/select2easy.js') }}"></script>
	<script type="text/javascript">
		$( '#select2easy' ).select2easy( );
	</script>
@stop