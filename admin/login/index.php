<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="../bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<!--     <link href="../bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="container">
  
    <h1 class="text-center">Your Challenge Area Administrativa</h1>
    <hr />
	<div class="row">
		<div class="col-lg-4 col-md-4"></div>
		<div class="col-lg-4 col-md-4">
			<div class="form-horizontal" id="formLogin">
				<div class="form-group">
					<label for="user" class="label-control">Usuario</label>
					<input class="form-control" name="user" id="user" type="text">
				</div>
				<div class="form-group">
					<label for="password" class="label-control">Contraseña</label>
					<input class="form-control" name="password" id="password" type="password">
				</div>
				<div class="form-group pull-right">
                                    <button class="btn btn-primary" id="btnLogin">Iniciar Sesión</button>
				</div>                          
			</div>	
		</div>
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../jquery/jquery-1.11.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script src="login.js"></script>
  </body>
</html>