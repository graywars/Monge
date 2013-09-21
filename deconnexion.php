<?php
session_start();
 
if($_SESSION['connect'] == "1")
{
	session_destroy();
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Au revoir</title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />              
			<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" height="48" width="48"/>
			<link rel="stylesheet" media="screen" type="text/css" title="TNM" href="design_accueil.css" />
		</head>
		<body>
			<div id="corps">
				<h1 style="color-font: #6ba5ef; font-size: 2em;">Vous êtes à présent déconnecté</h1>
				<form action="index.php" method="post">
					<p><input type="submit" value="OK"/></p>
				</form>
			</div>
			<div id="pied_de_page3">
			</div>
		</body>
	</html>
<?php
}
else
{
?>
<!DOCTYPE html>
<html>
    <head>
		<title>Visiteur inconnu</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" media="screen" type="text/css" title="suite" href="design_accueil.css" />
		<!-- Lien vers la favicon -->      
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" height="48" width="48"/>
    </head>
	<body>
		<div id="corps">
			<p>Veuillez-vous identifier pour accéder au site :</p>
			<form action="index.php" method="post">
				<p>
					<input type="submit" value="OK" />
				</p>
			</form>
		</div>
		<div id="pied_de_page1">
		</div>
    </body>
</html>
<?php
}