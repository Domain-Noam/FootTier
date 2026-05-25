<?php

// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php"); 


function verifUserBdd($login,$passe)
{
  // Vérifie l'identité d'un utilisateur 
  // dont les identifiants sont passes en paramètre
  // renvoie faux si user inconnu
  // renvoie l'id de l'utilisateur si succès

  $SQL="SELECT id FROM users WHERE pseudo='$login' AND passe='$passe'";

  return SQLGetChamp($SQL);
  // si on avait besoin de plus d'un champ
  // on aurait du utiliser SQLSelect
}

function verifUserBddHash($login, $passe){
	//Vérifie l'identité via bcrypt (algorithme de hashage qui stocke des mots de passe de façon sécurisée) : on récupère le hash stocké puis password_verify()
	$SQL = "SELECT id, pass FROM users WHERE pseudo='$login';";
	$resultat = SQLSelect($SQL);

	if(!$resultat){
    	return false;
	}

	$resultat = parcoursRs($resultat);
	if(password_verify($passe, $resultat[0]['pass'])){
		return $resultat[0]['id'];  //on retourne l'id
	}

	return false; // Mauvais mot de passe
}

function isAdmin($idUser)
{
  // vérifie si l'utilisateur est un administrateur
  $SQL ="SELECT admin FROM users WHERE id='$idUser'";
  return SQLGetChamp($SQL); 
}


//Création de nouveaux utilisateurs
function creerUtilisateurHash($pseudo, $passe){
    //Hachage du mot de passe avec Bcrypt comme demandé
    $hash = password_hash($passe, PASSWORD_BCRYPT);

    //(est_admin est à 0 par défaut, NOW() met la date actuelle)
    $sql = "INSERT INTO utilisateur (pseudo, mot_de_passe, est_admin, date_inscription) VALUES ('$pseudo', '$hash', 0, NOW())";
    
    return SQLInsert($sql);
}

?>
