<?php
session_start();
require "connect.php";
$id = htmlentities(trim($_SESSION['id']));
$req=$bdd->query('SELECT * FROM user WHERE id = "'.$id.'"');
$data=$req->fetch();
$Nom = $data['Nom'];
$Prenom = $data['Prenom'];
if($_SESSION['connect'] == 1)
{
	date_default_timezone_set('Europe/Paris');
	/*sujet*/
	if(isset($_POST['section']))
	{
		$req=$bdd->query('SELECT count(*) FROM section WHERE id = "'.$_POST['section'].'"');
		$data=$req->fetch();
		
		if($data[0] == 1)
		{
			$sec = $_POST['section'];
			
			if(!empty($_POST['sujet']) && !empty($_POST['message']))
			{
				$message = nl2br(htmlentities($_POST['message']));
				$sujet = nl2br(htmlentities($_POST['sujet']));
				$req = $bdd->query('SELECT count(*) FROM message WHERE Nom = "'.$Nom.'" && contenu = "'.$message.'"');
				$data = $req->fetch();
				if($data[0] == 0)
				{
					$req = $bdd->query('SELECT id_cat FROM section WHERE id = "'.$sec.'"');
					$data = $req->fetch();
					$cat = $data['id_cat'];
					// sujet
					$sujet = $_POST['sujet'];
					$req=$bdd->exec('INSERT INTO sujet SET id = "", id_cat = "'.$cat.'", id_sec = "'.$sec.'", Nom = "'.$sujet.'"');
					$req=$bdd->query('SELECT id FROM sujet WHERE Nom = "'.$sujet.'" ORDER BY id DESC');
					$data=$req->fetch();
					$id_suj = $data['id'];
					// message
					
					$date = date('d/m/Y_H:i:s');
					$req=$bdd->exec('INSERT INTO message SET id = "", id_cat = "'.$cat.'", id_sec = "'.$sec.'", id_suj = "'.$id_suj.'", contenu = "'.$message.'", Date = "'.$date.'", Nom = "'.$Nom.'", Prenom = "'.$Prenom.'"');
					echo "votre sujet a bien été créé";
					echo "<meta http-equiv='Refresh' content='3;url=forum.php?sec=".$_POST['section']."' />";
				}
				else
				{
					echo "Sujet déjà poster!";
				}
			}
			else
			{
				echo "Un des champs est vide!";
			}
		}
		elseif($data[0] == 0)
		{
			echo "la section n'existe pas";
		}
	}
	elseif(isset($_POST['sujet']))
	{
		$req=$bdd->query('SELECT count(*) FROM sujet WHERE id = "'.$_POST['sujet'].'"');
		$data=$req->fetch();
		
		if($data[0] == 1)
		{
			$id_suj = $_POST['sujet'];
			$req = $bdd->query('SELECT id_sec FROM sujet WHERE id = "'.$id_suj.'"');
			$data = $req->fetch();
			$id_sec = $data['id_sec'];
			$req = $bdd->query('SELECT id_cat FROM section WHERE id = "'.$sec.'"');
			$data = $req->fetch();
			$id_cat = $data['id_cat'];
			$message = nl2br(htmlentities($_POST['message']));
			$date=date('d/m/Y_H:i:s');
			$req=$bdd->exec('INSERT INTO message SET id = "", id_cat = "'.$id_cat.'", id_sec = "'.$id_sec.'", id_suj = "'.$id_suj.'", contenu = "'.$message.'", Date = "'.$date.'", Nom = "'.$Nom.'", Prenom = "'.$Prenom.'"');
			echo "votre message a bien été créé";
			echo "<meta http-equiv='Refresh' content='3;url=forum.php' />";
		}
		elseif($data[0] == 0)
		{
			echo "le sujet choisi n'est pas ou plus valide";
		}
	}
}
else
{
	echo "Vous n'étes pas connecté!";
	echo "<meta http-equiv='Refresh' content='2;URL=index.php' />";
}
?>