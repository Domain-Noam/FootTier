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
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
						if (valider("remember")) {
							setcookie("pseudo",$pseudo , time()+60*60*24*30);
							setcookie("passe",$password, time()+60*60*24*30);
						} else {
							setcookie("pseudo","", time()-3600);
							setcookie("passe","", time()-3600);
						}

					}	
				}
				$addArgs = array();

				// On redirigera vers la page index automatiquement
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


		}
	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments
  
	//tprint($addArgs);
  $addArgsStr = '';
  if (is_array($addArgs)) {
    foreach($addArgs as $key => $arg) {
      $addArgsStr .= '&' . $key . '=' . $arg;
    }
    $addArgsStr = substr($addArgsStr, 1);
  } else {
    $addArgsStr = $addArgs;
  }
		//echo ($addArgsStr);
		//die("");
	header("Location:" . $urlBase . '?' . $addArgsStr);

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










