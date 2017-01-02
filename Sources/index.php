<!DOCTYPE html>
<html>
	<head>
		<title>DiVin</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" media="all"/>
	</head>

	<body>
		<div id="container">
			<div class="row">
				<div id="logo" class="logoCenter">
					<img src="pictures/logo.png" alt="logo" />
				</div>
			</div>
			<div id = "div_search_bar" class="row">
				<div class="col-md-6 col-md-offset-3">
						<form onsubmit="return false;">
			        		<input id="search_bar" type="text" class="input-sm form-control" placeholder="Recherche">
			       		</form>
				</div>
				<div class="col-xs-2 col-sm- col-md-2 ">
			       		<button id = "search_button" type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open"></span> Chercher</button>
				</div>
			</div>
		</div>

		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/autocompletion.js"></script>
		<script src="js/divin.js"></script>
		<script src="http://spin.js.org/spin.min.js"></script>
	</body>
</html>
