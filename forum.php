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
			$id = htmlentities(trim($_SESSION['id'])); // récupération de l'id
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

			echo '<a href="deconnexion.php">Déconnexion</a> | <a href="information.php">Information</a> | '.$admin.'<br>';
			/*
			*	Liste des messages d'un sujet
			*/
			if(isset($_GET['suj']))
			{
				if(preg_match('`^[0-9]+$`',$_GET['suj']))
				{
					$req=$bdd->query('SELECT count(*) FROM sujet WHERE id = "'.$_GET['suj'].'"');
					$data=$req->fetch();
				
					if($data[0]==1)
					{
						$req = $bdd->query('SELECT * FROM sujet WHERE id = "'.$_GET['suj'].'"');
						$data = $req->fetch();
						$Nom_suj = $data['Nom'];
						$id_sec = $data['id_sec'];
						
						$req = $bdd->query('SELECT * FROM section WHERE id = "'.$id_sec.'"');
						$data = $req->fetch();
						$id_cat = $data['id_cat'];
						$Nom_sec = $data['Nom'];
						
						$req = $bdd->query('SELECT Nom FROM categorie WHERE id = "'.$id_cat.'"');
						$data = $req->fetch();
						$Nom_cat = $data['Nom'];
						
						echo '<a href="forum.php">Accueil</a>/<a href="forum.php?sec='.$id_sec.'">'.$Nom_cat.'('.$Nom_sec.')</a>/<br><br>';
						/*
						*	Formulaire réponse de message
						*/
					?>
						<div class="creamess">
							<p>création du message pour le sujet ''</p>
							<form method="post" action="sujet.php">
								<input type="hidden" name="sujet" value="<?php echo $_GET['suj']; ?>" />
								<p>
									<label for="message"> écrivez votre message ici</label><br>
									<textarea name="message" id="prmess"></textarea>
								</p>
								<p>
									<input type="submit" name="valider" value="valider" />
								</p>
							</form>
						</div>
						<input type="submit" class="crea_mess" value="Répondre" onclick="creamess()" /><br><br>
					<?php
						echo $Nom_suj.'<br><br>';
						$nb_mess=1;
						$req_mess=$bdd->query('SELECT * FROM message WHERE id_suj = "'.$_GET['suj'].'" ORDER BY id ASC');
						while($message=$req_mess->fetch())
						{
							$id_mess=$message['id'];
							$date_mess[$nb_mess]=explode('_', $message['Date']);
							$nom_mess[$nb_mess]=$message['Nom'];
							$prenom_mess[$nb_mess]=$message['Prenom'];
							$contenu_mess[$nb_mess]=$message['contenu'];
							
							echo $nom_mess[$nb_mess].' '.$prenom_mess[$nb_mess].' /-/ le: '.$date_mess[$nb_mess][0].' à '.$date_mess[$nb_mess][1].'<br>Contenu:<br>'.$contenu_mess[$nb_mess].'<br><br>';
							$nb_mess++;
						}
					}
					elseif($data[0]==0)
					{
						echo "le sujet n'existe pas";
					}
				}
			}
			/*
			*	Liste des sujets
			*/
			elseif(isset($_GET['sec']))
			{
				if(preg_match('`^[0-9]+$`',$_GET['sec']))
				{
					$req=$bdd->query('SELECT count(*) FROM section WHERE id = "'.$_GET['sec'].'"');
					$data=$req->fetch();
					
					if($data[0]==1)
					{
						$req = $bdd->query('SELECT * FROM section WHERE id = "'.$_GET['sec'].'"');
						$data = $req->fetch();
						$id_cat = $data['id_cat'];
						$Nom_sec = $data['Nom'];
						
						$req = $bdd->query('SELECT Nom FROM categorie WHERE id = "'.$id_cat.'"');
						$data = $req->fetch();
						$Nom_cat = $data['Nom'];
						
						echo '<a href="forum.php">Accueil</a>/'.$Nom_cat.'('.$Nom_sec.')/<br><br>';
						/*
						*	Formulaire Créer sujet
						*/
					?>
						<div class="creasuj">
							<p>création du sujet pour la section '<?php echo $Nom_cat.'('.$Nom_sec.')'?>'</p>
							<form method="post" action="sujet.php">
								<input type="hidden" name="section" value="<?php echo $_GET['sec']; ?>" />
								<label for="sujet">Nom du sujet:</label>
								<p>
									<input type="text" name="sujet" />
								</p>
								<p>
									<label for="message">écrivez votre message ici</label><br>
									<textarea name="message" id="prmess"></textarea>
								</p>
								<p>
									<input type="submit" name="envoyer" value="envoyer"/>
								</p>
							</form>
						</div>
						<input type="submit" class="crea_suj" value="crée un sujet" onclick="creasuj()" /><br><br>
					<?php
						$nb_suj=1;
						$req_suj=$bdd->query('SELECT * FROM sujet WHERE id_sec = "'.$_GET['sec'].'" ORDER BY id ASC');
						while($sujet=$req_suj->fetch())
						{
							$id_suj = $sujet['id'];
							$nom_suj[$nb_suj]=$sujet['Nom'];
							echo '<a href="forum.php?suj='.$id_suj.'">'.$nom_suj[$nb_suj].'</a></br>';
							$nb_suj++;	
						}
					}
					elseif($data[0]==0)
					{
						echo "la section n'existe pas";
					}
				}
			}
			/*
			*	Liste des catégories et sections
			*/
			else
			{
				echo "Accueil/<br><br>";
				$req=$bdd->query('SELECT count(*) FROM categorie');
				$data=$req->fetch();
				$nb_cat = $data[0];
				$nb_cat = $nb_cat+1;
				$nb = 1;
				
				while($nb != $nb_cat)
				{
					$req_cat=$bdd->query('SELECT * FROM categorie WHERE id = "'.$nb.'"'); 
					$data_cat=$req_cat->fetch();
					$nom_cat[$nb] = $data_cat['Nom'];
					?>
					<table>
						<tr>
							<th><?php echo $nom_cat[$nb]; ?></th>
							<th>Sujets</th>
							<th>Réponses</th>
							<th>Derniers messages</th>
						</tr>
					<?php
					$nb_sec = 1;
					$req_sec=$bdd->query('SELECT * FROM section WHERE id_cat = "'.$nb.'" ORDER BY id ASC ');
					while($data_sec=$req_sec->fetch())
					{
						$id_sec = $data_sec['id'];
						$nom_sec[$nb_sec] = $data_sec['Nom'];
						
						$req_nb_suj = $bdd->query('SELECT count(*) FROM sujet WHERE id_sec = "'.$id_sec.'"');
						$data_nb_suj = $req_nb_suj->fetch();
						$sujet_nb[$nb_sec] = $data_nb_suj[0];
						
						$req_nb_mess = $bdd->query('SELECT count(*) FROM message WHERE id_sec = "'.$id_sec.'"');
						$data_nb_mess = $req_nb_mess->fetch();
						$message_nb[$nb_sec] = $data_nb_mess[0];
						
						$reponse_nb[$nb_sec] = $sujet_nb[$nb_sec] - $message_nb[$nb_sec];
						if($sujet != 0)
						{
							$req_last = $bdd->query('SELECT * FROM message WHERE id_sec = "'.$id_sec.'" ORDER BY id DESC');
							$data_last = $req_last->fetch();
							$date_last[$nb_sec] = explode('_', $data['Date']);
							$id_sec_last[$nb_sec] = $data_last['id_sec'];
							$Nom_last[$nb_sec] = $data_last['Nom'];
							$Prenom_last[$nb_sec] = $data_last['Prenom'];
							
							$req = $bdd->query('SELECT Nom, id_cat FROM section WHERE id = "'.$id_sec_last[$nb_sec].'"');
							$data = $req->fetch();
							$id_cat_last[$nb_sec] = $data['id_cat'];
							$Nom_sec_last[$nb_sec] = $data['Nom'];
							if($data['id_cat'] < 3)
							{
								$req = $bdd->query('SELECT Nom FROM categorie WHERE id = "'.$id_cat_last[$nb_sec].'"');
								$data = $req->fetch();
								
								$Nom_sec_last[$nb_sec] = $Nom_sec_last[$nb_sec].'('.$data['Nom'].')';
							}
							echo '<tr><td class="col1"><a class="section" href="forum.php?sec='.$id_sec.'">'.$nom_sec[$nb_sec].'</a></td><td class="col2">'.$sujet_nb[$nb_sec].'</td><td class="col3">'.$reponse_nb[$nb_sec].'</td><td class="col4">'.$date_last[$nb_sec][0].' - '.$date_last[$nb_sec][1].'<br>Dans: '.$Nom_sec_last[$nb_sec].'<br>Par: '.$Nom_last[$nb_sec].' '.$Prenom_last[$nb_sec].'</td></tr>';
						}
						else
						{
							echo '<tr><td class="col1"><a class="section" href="forum.php?sec='.$id_sec.'">'.$nom_sec[$nb_sec].'</a></td><td class="col2">'.$sujet_nb[$nb_sec].'</td><td class="col3">'.$reponse_nb[$nb_sec].'</td><td class="col4"></td></tr>';
						}
						$nb_sec++;
					}
					?>
					</table>
					<?php
					$nb++;
				}
				
				/*
				*
				*	Zone des différents cours
				*
				*/
				
				/*
				*	Cours de système et réseau
				*/
				?>
				<br><br><span id="res" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Système et Réseau</span><br>
				<div id="file_res" class="zone_file">
					<?php
					/* Cours de réseau */
					$liste_rep = scandir("./reseau/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="reseau/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div><br>
				<span id="prog" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Programmation</span><br>
				<div id="file_prog" class="zone_file">
					<?php
					/* PROGRAMMATION */
					$liste_rep = scandir("./programmation/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="programmation/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div><br>
				<span id="ang" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Anglais</span><br>
				<div id="file_ang" class="zone_file">
					<?php
					/* ANGLAIS */
					$liste_rep = scandir("./anglais/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="anglais/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div><br>
				<span id="math" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Mathématique</span><br>
				<div id="file_math" class="zone_file">
					<?php
					/* MATH */
					$liste_rep = scandir("./math/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="programmation/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div><br>
				<span id="fra" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Expression Française</span><br>
				<div id="file_fra" class="zone_file">
					<?php
					/* FRANCAIS */
					$liste_rep = scandir("./fra/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="fra/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div><br>
				<span id="eco" class="title_file" onclick="zone_file(this)">Lister des fichiers de cours: Economie droit et management</span><br>
				<div id="file_eco" class="zone_file">
					<?php
					/* ECO */
					$liste_rep = scandir("./eco/");
					$i = 0;
					$num = count($liste_rep);
					if($num > "2")
					{
						while($i < $num)
						{
							$nom[$i] = explode('.', $liste_rep[$i]);
							if(strstr($liste_rep[$i], ".") && !empty($nom[$i][1]))
							{ 
								echo '<a href="eco/'.$liste_rep[$i].'">'.$liste_rep[$i].'</a><br>';
							}
							$i++;
						}
					}
					else
					{
						echo "Dossier vide!";
					}
					?>
				</div>
				<?php
			}
		?>
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