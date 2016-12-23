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
				<div class="col-xs-9 col-sm-10 col-md-10 ">
						<form>
			        		<input id="search_bar" type="text" class="input-sm form-control" placeholder="Recherche">
			       		</form>
				</div>
				<div class="col-xs-3 col-sm-2 col-md-2 ">
			       		<button id = "search_button" type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open"></span> Chercher</button>
				</div>
			</div>
		</div>

		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/autocompletion.js"></script>
	</body>
</html>
