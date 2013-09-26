<?php
session_start();
require "connect.php";
if(!isset($_SESSION['connect']))
{
	$_SESSION['connect'] = 0;
}

/*
*	Vérification formulaire de connexion
*/
if(!empty($_POST['Connexion']) && $_POST['Connexion'] == 'Connexion')
{
	if(!empty($_POST['login']) && !empty($_POST['pass']))
 	{
		if(preg_match("`^[[:alnum:]]+$`",$_POST['login']))
		{
			$login = $_POST['login'];
			$pass = sha1($_POST['pass']);
			$pass = md5($pass);
			$req = $bdd->query('SELECT count(*) FROM user WHERE login = "'.$login.'" && pass = "'.$pass.'"');
			$data = $req->fetch();
			
			if($data[0] == 1)
			{
				$req = $bdd->query('SELECT id FROM user WHERE login = "'.$login.'" && pass = "'.$pass.'"');
				$data = $req->fetch();
				$_SESSION['id'] = $data['id'];
				$_SESSION['connect'] = "1";
				$date_connect = date('d/m/Y_H:i:s');
				$req = $bdd->exec('UPDATE user SET date_connect = "'.$date_connect.'" WHERE id = "'.$data['id'].'"');
				echo "<meta http-equiv='Refresh' content='0;URL=forum.php' />";
			}
			else
			{
				$error = "Compte incorrect ou inexistant";
			}
		}
	}
	else
	{
		$error = "Champ nom de compte et/ou mot de passe vide!";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Forum promotion monge 2013-2015</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<a class="bout" href="inscription.php">Inscription</a><br>
		<?php
		if(isset($error))
		{
			echo $error;
		}
		?>
		<form action="index.php" method="post">
			<input class="login" type="text" name="login" placeholder="Nom de compte" autofocus /><br>
			<input class="password" type="password" name="pass" placeholder="Mot de passe" /><br>
			<input type="submit" name="Connexion" value="Connexion" />
		</form>
	</body>
</html>