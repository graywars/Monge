<?php
session_start();
require "connect.php";

if(!isset($_SESSION['connect']))
{
	$_SESSION['connect'] = "0";
}

if($_SESSION['connect'] == "1")
{
?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<link rel="stylesheet" href="css/forum.css" />
			<script src="jquery.js"></script>
			<script src="sujet.js"></script>
		</head>
		<body>
		<?php
			$id = htmlentities(trim($_SESSION['id']));
			/*
			*	Vérification du droit de l'utilisateur
			*/
			$req = $bdd->query('SELECT droit FROM user WHERE id = "'.$id.'"');
			$data = $req->fetch();
			if($data['droit'] == 1)
			{
				$admin = '<a href="administration.php">Administration</a>';
			}
			else
			{
				$admin = "";
			}

			echo '<a href="deconnexion.php">Déconnexion</a> | <a href="forum.php">Forum</a> | '.$admin.'<br>';
		?>
			<br><br>Si vous voulez mettre des cours veuillez me les envoyer à l'adresse: <a href="mailto:alexis.ostalier@laposte.net?subject=Cours pour le forum&cc=dylan.varoquaux@gmail.com&body=Bonjour,%0AVoici un cours de:%0Aque je souhaiterais mettre sur le forum.">alexis.ostalier@laposte.net</a><br><br>
			Les sources seront prochainement sur: <a href="https://github.com/graywars/Monge" target="_blank">Github</a><br>
		</body>
	</html>
<?php
}
else
{
	echo "Vous n'étes pas connecté!";
	echo "<meta http-equiv='Refresh' content='2;URL=index.php' />";
}
?>