<?php
session_start();

	include_once "librairies/maLibUtils.php";
	include_once "librairies/maLibSQL.pdo.php";
	include_once "librairies/maLibSecurisation.php"; 
	include_once "librairies/modele.php"; 

	$addArgs = $_GET;

	if ($action = valider("action"))
	{
		ob_start ();
		echo "Action = '$action' <br />";
		switch($action)
		{
			
			case 'Connexion' :
				// On verifie la presence des champs pseudo et mot_de_passe
				if ($pseudo = valider("Pseudo"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($pseudo,$passe)) {
						setcookie("pseudo","", time()-3600);
						setcookie("passe","", time()-3600);
						$addArgs = array("view"=>"galerie");
					}
					else{
						$addArgs = array("view"=>"connexion");
					}	
				}
			break;

			case 'Logout' :
				session_destroy();
				$addArgs = array();
			break;

			case 'Inscription' :
				$pseudo = valider("Pseudo");
				$passe = valider("passe");
				if($pseudo && $passe){
					creerUtilisateurHash($pseudo, $passe);
				}
				$addArgs = array("view"=>"connexion");
			break;

			// ==============================================================================
			// INTERCEPTION DES ACTIONS DE LA PAGE D'ADMINISTRATION
			// ==============================================================================

			case 'Promouvoir':
				if ($idUser = valider("id_user")) {
					promouvoirAdmin($idUser);
				}
				$addArgs = array("view" => "admin");
			break;

			case 'Demettre':
				if ($idUser = valider("id_user")) {
					demettreAdmin($idUser);
				}
				$addArgs = array("view" => "admin");
			break;

			case 'Bannir':
				if ($idUser = valider("id_user")) {
					bannirUtilisateur($idUser);
				}
				$addArgs = array("view" => "admin");
			break;

			case 'AjouterAction':
				$joueur = valider("joueur");
				$competition = valider("competition");
				$id_categorie = valider("id_categorie");
				$url_image = valider("url_image");
				$url_media = valider("url_media");

				if ($joueur && $competition && $id_categorie && $url_image && $url_media) {
					ajouterNouvelleAction($joueur, $competition, $id_categorie, $url_image, $url_media);
				}
				$addArgs = array("view" => "admin");
			break;

			case 'SupprimerAction':
				if ($idAction = valider("id_action")) {
					supprimerAction($idAction);
				}
				$addArgs = array("view" => "admin");
			break;

			case 'SupprimerTierlist':
			if (valider("connecte", "SESSION")) {
				$idTierlist = valider("id_tierlist", "GET");
				if ($idTierlist != false) {
					// Appel de la fonction du modèle
					supprimerTierlist($idTierlist);
				}
			}
			// On redirige vers la vue brouillon une fois la suppression terminée
			$qs = "?view=brouillon";
			break;
		}
	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	
	// On reconstruit les arguments GET pour la redirection (View, messages d'erreur...)
	$addArgsStr = '';
	if (is_array($addArgs)) {
		foreach($addArgs as $key => $val) {
			if ($addArgsStr == '') $addArgsStr .= "?$key=" . urlencode($val);
			else $addArgsStr .= "&$key=" . urlencode($val);
		}
	}

	// Redirection finale automatique vers l'index
	header("Location:" . $urlBase . $addArgsStr);
?>
