<?php
require "connect.php";
/*
*	Vérification formulaire d'inscription
*/
if(!empty($_POST['Inscrire']) && $_POST['Inscrire'] == 'Inscrire')
{
	if(!empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['conf_pass']) && !empty($_POST['nom']) && !empty($_POST['prenom']))
 	{
		if($_POST['pass'] == $_POST['conf_pass'])
		{
			if(strlen($_POST['login']) >= 6 && strlen($_POST['pass']) >= 8)
			{
				if(preg_match("`^[[:alnum:]]+$`",$_POST['login']) && preg_match("`^[[:alnum:]]+$`",$_POST['pass']) && preg_match("`^[[:alnum:]]+$`",$_POST['nom']) && preg_match("`^[[:alnum:]]+$`",$_POST['prenom']))
				{
					$req = $bdd->query('SELECT count(*) FROM user WHERE login = "'.$_POST['login'].'"');
					$data = $req->fetch();
					if($data[0] == 0)
					{
						$login = $_POST['login'];
						$nom = $_POST['nom'];
						$nom = strtoupper($nom);
						$prenom = $_POST['prenom'];
						$prenom = ucfirst($prenom);
						$pass = sha1($_POST['pass']);
						$pass = md5($pass);
						$req = $bdd->exec('INSERT INTO user VALUES("", "'.$nom.'", "'.$prenom.'", "'.$login.'", "'.$pass.'", "0", "0")');
						$error = "Inscription réussit";
						echo "<meta http-equiv='Refresh' content='2;url=index.php' />";
					}
					else
					{
						$error = "Nom de compte déjà utilisé!";
					}
				}
				else
				{
					$error = "Un ou plusieurs champ contient des caractères invalide!";
				}
			}
			else
			{
				$error = "Nom de compte doit contenir 6 caractère minimum<br>Mot de passe doit contenir 8 caractère minimum";
			}
		}
		else
		{
			$error = "Les deux mot de passe sont différents";
		}
	}
	else
	{
		$error = "Un ou plusieur champ sont vide!";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Inscription forum promotion monge 2013-2015</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<a class="bout" href="index.php">Connexion</a><br>
		<?php
		if(isset($error))
		{
			echo $error;
		}
		?>
		<form action="inscription.php" method="post">
			<input class="login" type="text" name="login" placeholder="Nom de compte" autofocus /> 6 caractère minimum(alpha-numérique)<br>
			<input class="pass" type="password" name="pass" placeholder="Mot de passe" /> 8 caractère minimum(alpha-numérique)<br>
			<input class="conf_pass" type="password" name="conf_pass" placeholder="Confirmation mot de passe" /><br>
			<input class="Nom" type="text" name="nom" placeholder="Nom" /><br>
			<input class="Prenom" type="text" name="prenom" placeholder="Prenom" /><br>
			<input type="submit" name="Inscrire" value="Inscrire" /><br>
		</form>
	</body>
</html>