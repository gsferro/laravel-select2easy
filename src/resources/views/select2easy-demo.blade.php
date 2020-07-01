<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
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
							data-sl2_hash="{{ \Illuminate\Support\Facades\Crypt::encryptString("App\User") }}"
							data-sl2_model="App\User"
							{{--data-sl2_method="sl2"--}}
					>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<link href="{{ asset('vendor/select2easy/select2/css/select2.css') }}" rel="stylesheet">
{{--<script type="text/javascript" src="{{ asset('vendor/select2easy/js/jquery-3.2.1.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('vendor/select2easy/select2/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/select2easy/js/select2easy.js') }}"></script>
<script type="text/javascript">
	$( '#select2easy' ).select2easy({
		"sl2_method" : "sl2"
	} );
</script>
</body>
</html>
