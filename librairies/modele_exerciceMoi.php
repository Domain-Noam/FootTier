<?php

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/


/********* PARTIE 1 : prise en main de la base de données *********/


// inclure ici la librairie faciliant les requêtes SQL
include_once "maLibSQL.pdo.php";

// TODO : compléter les fonctions listerUtilisateurs, interdireUtilisateur et autoriserUtilisateur
function listerUtilisateurs($classe = "both")
{

	$SQL = "SELECT id,pseudo,blacklist,connecte,couleur FROM users";
	
	if ($classe == "bl") 
		$SQL .= " WHERE blacklist=1";
		
	if ($classe == "nbl") 
		$SQL = $SQL . " WHERE blacklist=0";
		
		
	// die($SQL); // affiche puis cesse l'interprétation php 
	
	$rs = SQLSelect($SQL); // NB : résultat : objet recordset 
	$tab = parcoursRs($rs); 
	return $tab; 
	
	// return parcoursRs(SQLSelect($SQL)); 
	
	// Cette fonction liste les utilisateurs de la base de données 
	// et renvoie un tableau d'enregistrements. 
	// Chaque enregistrement est un tableau associatif contenant les champs 
	// id,pseudo,blacklist,connecte,couleur

	// Lorsque la variable $classe vaut "both", elle renvoie tous les utilisateurs
	// Lorsqu'elle vaut "bl", elle ne renvoie que les utilisateurs blacklistés
	// Lorsqu'elle vaut "nbl", elle ne renvoie que les utilisateurs non blacklistés

}


function interdireUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à vrai pour l'utilisateur concerné 
	
	// NEVER TRUST USER INPUT !! 
	// Attention aux injections SQL 
	// si idUser=1; drop table users; DANGER ! 
	// contre-mesures : 
	// 1) encadrer les champs - mêmes numériques - par des apostrophes
	// si idUser=1'; drop table users;' DANGER ! 
	// 2) banaliser les éventuels apostrophes venant des entrées utilisateur => addslashes dans la fonction proteger qui appelle valider 

	// On peut aussi trouver des solutions exploitant des requetes préparées : équivalent d'un printf 
	// executerSQL("format de requete avec des %s", arguments user)
	
	$SQL = "UPDATE users SET blacklist=1 WHERE id='$idUser'"; 
	return SQLUpdate($SQL); 
}

function autoriserUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à faux pour l'utilisateur concerné 
	$SQL = "UPDATE users SET blacklist=0 WHERE id='$idUser'";
	return SQLUpdate($SQL);
}

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


function isAdmin($idUser)
{
	// vérifie si l'utilisateur est un administrateur
	$SQL ="SELECT admin FROM users WHERE id='$idUser'";
	return SQLGetChamp($SQL); 
}

/********* PARTIE 2 *********/

function mkUser($pseudo, $passe,$admin=false,$couleur="black")
{
	$SQL = "INSERT INTO users (pseudo, passe, admin, couleur) VALUES ($pseudo, $passe, $admin, $couleur);";
	return SQLInsert($SQL);	
// Cette fonction crée un nouvel utilisateur et renvoie l'identifiant de l'utilisateur créé
}

function connecterUtilisateur($idUser)
{
	$SQL = "UPDATE users SET connecte=1 WHERE id='$idUser';";
	return SQLUpdate($SQL);
// cette fonction affecte le booléen "connecte" à vrai pour l'utilisateur concerné 
}

function deconnecterUtilisateur($idUser)
{
	$SQL = "UPDATE users SET connecte=0 WHERE id=\"$idUser\";";
	return SQLUpdate($SQL);	
// cette fonction affecte le booléen "connecte" à faux pour l'utilisateur concerné 
}

function changerCouleur($idUser,$couleur="black")
{
	// cette fonction modifie la valeur du champ 'couleur' de l'utilisateur concerné
	return SQLUpdate("
	  UPDATE users
	  SET couleur='$couleur'
	  WHERE id='$idUser';
	");
}

function changerPasse($idUser,$passe)
{
	$SQL = "UPDATE users SET passe=\"$passe\" WHERE id=\"$idUser\";";	
	return SQLUpdate($SQL);
// cette fonction modifie le mot de passe d'un utilisateur
}

function changerPseudo($idUser,$pseudo)
{
	$SQL = "UPDATE users SET pseudo=\"$pseudo\" WHERE id=\"$idUser\";";	
	return SQLUpdate($SQL);	
// cette fonction modifie le pseudo d'un utilisateur
}

function promouvoirAdmin($idUser)
{
	$SQL = "UPDATE users SET admin=1 WHERE id=\"$idUser\";";
	return SQLUpdate($SQL);	
	// cette fonction fait de l'utilisateur un administrateur
}

function retrograderUser($idUser)
{
	$SQL = "UPDATE users SET admin=0 WHERE id=\"$idUser\";";
	return SQLUpdate($SQL);		
// cette fonction fait de l'utilisateur un simple mortel
}


/********* PARTIE 3 *********/

function listerUtilisateursConnectes()
{
	$SQL = "SELECT pseudo FROM users WHERE connecte=1;";
	return SQLSelect($SQL);	
// Liste les utilisteurs connectes
}

function listerConversations($mode="tout")
{
	if($mode=="inactives"){
		$SQL = "SELECT id, theme FROM conversations WHERE active=0;";
		
	}	
	else if($mode=="actives"){
		$SQL = "SELECT id, theme FROM conversations WHERE active=1;";

	}
	else{
		$SQL = "SELECT id, theme FROM conversations;";

	}
	return parcoursRs(SQLSelect($SQL));
// Liste toutes les conversations ($mode="tout")
	// OU uniquement celles actives  ($mode="actives"), ou inactives  ($mode="inactives")
}

function archiverConversation($idConversation)
{
	// rend une conversation inactive
}

function creerConversation($theme)
{
	$SQL = "INSERT INTO conversations (theme) VALUES ('$theme');";
	SQLInsert($SQL);
	return SQLGetChamp("SELECT MAX(id) FROM conversations"); //cette fonction retourne juste un champ
// crée une nouvelle conversation et renvoie son identifiant
}

function reactiverConversation($idConversation)
{	
	// rend une conversation active

}

function supprimerConversation($idConv)
{
	// supprime une conversation et ses messages

	// NB : on aurait pu aussi demander à mysql de supprimer automatiquement
	// les messages lorsqu'une conversation est supprimée, 
	// en déclarant idConversation comme clé étrangère vers le champ id de la table 
	// des conversations et en définissant un trigger
}


function enregistrerMessage($idConversation, $idAuteur, $contenu)
{
	// Enregistre un message dans la base en encodant les caractères spéciaux HTML : <, > et & pour interdire les messages HTML
}

function listerMessages($idConv,$format="asso")
{
	// Liste les messages de cette conversation, au format JSON ou tableau associatif
	// Champs à extraire : contenu, auteur, couleur 
	// en ne renvoyant pas les utilisateurs blacklistés
	
}

function listerMessagesFromIndex($idConv,$index)
{
	// Liste les messages de cette conversation, 
	// dont l'id est superieur à l'identifiant passé
	// Champs à extraire : contenu, auteur, couleur 
	// en ne renvoyant pas les utilisateurs blacklistés

}

function getConversation($idConv)
{	
	// Récupère les données de la conversation (theme, active)
}



?>
