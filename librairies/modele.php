<?php

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/


/********* PARTIE 1 : prise en main de la base de données *********/


// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php"); 

function listerUtilisateurs($classe = "both")
{
  $SQL="SELECT id,pseudo,blacklist,connecte,couleur FROM users";
  if ($classe == "bl") $SQL = $SQL . " WHERE blacklist=1"; 
  if ($classe == "nbl") $SQL .= " WHERE blacklist=0"; 
  
  // die($SQL); // affiche et interrompt l'interprétation  
  
  return parcoursRs(SQLSelect($SQL)); 
  
  // Cette fonction liste les utilisateurs de la base de données 
  // et renvoie un tableau d'enregistrements. 
  // Chaque enregistrement est un tableau associatif contenant les champs 
  // id,pseudo,blacklist,connecte,couleur

  // Lorsque la variable $classe vaut "both", elle renvoie tous les utilisateurs
  // Lorsqu'elle vaut "bl", elle ne renvoie que les utilisateurs blacklistés
  // Lorsqu'elle vaut "nbl", elle ne renvoie que les utilisateurs non blacklistés

}


//function changerStatutUtilisateur($idUser, $statut=false)
//function changerUtilisateur($idUser, $champ, $valeur)

function interdireUtilisateur($idUser)
{
  // cette fonction affecte le booléen "blacklist" à vrai pour l'utilisateur concerné
  // NEVER TRUST USER INPUT !!! 
  // Attention aux injections SQL !!
  // si $idUser = 1; drop table users; // DANGER 
  // contre-mesures : 
  // 1) encadrer les champs d'entree utilisateur par des apostrophes 
  // si $idUser = 1'; drop table users;' // DANGER 
  // 2) banaliser les apostrophes dans les entrées !!
  // => fonction valider ! 
  
  // alternative : requetes préparées 
  // équivalent de printf avec format et des arguments 
  // objPDO.prepare("requete sql avec des %s", entreeuser1, ...)
   
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
  // Cette fonction crée un nouvel utilisateur et renvoie l'identifiant de l'utilisateur créé
  $strAdmin = $admin ? "1" : "0";
  SQLInsert("
    INSERT INTO users (pseudo, passe, admin, couleur)
    VALUES ('$pseudo', '$passe', $strAdmin, '$couleur');
  ");
  return SQLGetChamp("
    SELECT MAX(id) FROM users;
  ");
}

function connecterUtilisateur($idUser)
{
  // cette fonction affecte le booléen "connecte" à vrai pour l'utilisateur concerné 
  return SQLUpdate("
    UPDATE users
    SET connecte = 1
    WHERE id='$idUser';
  ");
}

function deconnecterUtilisateur($idUser)
{
  // cette fonction affecte le booléen "connecte" à faux pour l'utilisateur concerné 
  return SQLUpdate("
    UPDATE users
    SET connecte = 0
    WHERE id='$idUser';
  ");
}

function changerCouleur($idUser,$couleur="black")
{
  // cette fonction modifie la valeur du champ 'couleur' de l'utilisateur concerné
  $SQL = "UPDATE users SET couleur='$couleur' WHERE id='$idUser'"; 
  return SQLUpdate($SQL);
}

function changerPasse($idUser,$passe)
{
  // cette fonction modifie le mot de passe d'un utilisateur
  return SQLUpdate("
    UPDATE users
    SET passe='$passe'
    WHERE id='$idUser';
  ");
}

function changerPseudo($idUser,$pseudo)
{
  // cette fonction modifie le pseudo d'un utilisateur
  return SQLUpdate("
    UPDATE users
    SET pseudo='$pseudo'
    WHERE id='$idUser';
  ");
}

function promouvoirAdmin($idUser)
{
  // cette fonction fait de l'utilisateur un administrateur
  return SQLUpdate("
    UPDATE users
    SET admin=1
    WHERE id='$idUser';
  ");
}

function retrograderUser($idUser)
{
  // cette fonction fait de l'utilisateur un simple mortel
  return SQLUpdate("
    UPDATE users
    SET admin=0
    WHERE id='$idUser';
  ");
}


/********* PARTIE 3 *********/

function listerUtilisateursConnectes()
{
  // Liste les utilisteurs connectes
  return parcoursRs(SQLSelect("
    SELECT id,pseudo,blacklist,couleur
    FROM users
    WHERE connecte;
  "));
}

function listerConversations($mode="tout")
{
  // Liste toutes les conversations ($mode="tout")
  // OU uniquement celles actives  ($mode="actives"), ou inactives  ($mode="inactives")
  $SQL = "
    SELECT *
    FROM conversations
  ";
  if ($mode == "actives") {
    $SQL .= "\n WHERE active";
  }
  if ($mode == "inactives") {
    $SQL .= "\n WHERE NOT active";
  }
  return parcoursRs(SQLSelect($SQL));
}

function archiverConversation($idConversation)
{
  // rend une conversation inactive
  return SQLUpdate("
    UPDATE conversations
    SET active=0
    WHERE id='$idConversation';
  ");
}

function creerConversation($theme)
{
  // crée une nouvelle conversation et renvoie son identifiant
  SQLInsert("
    INSERT INTO conversations(theme)
    VALUES ('$theme');
  ");
  return SQLGetChamp("
    SELECT MAX(id) FROM conversations;
  ");
}

function reactiverConversation($idConversation)
{  
  // rend une conversation active
  return SQLUpdate("
    UPDATE conversations
    SET active=1
    WHERE id='$idConversation';
  ");
}

function supprimerConversation($idConv)
{
  // supprime une conversation et ses messages

  // NB : on aurait pu aussi demander à mysql de supprimer automatiquement
  // les messages lorsqu'une conversation est supprimée, 
  // en déclarant idConversation comme clé étrangère vers le champ id de la table 
  // des conversations et en définissant un trigger
  
  return SQLDelete("
    DELETE FROM conversations
    WHERE id='$idConv';
  ");
}


function enregistrerMessage($idConversation, $idAuteur, $contenu)
{
  // Enregistre un message dans la base en encodant les caractères spéciaux HTML : <, > et & pour interdire les messages HTML
  $contenu = htmlentities($contenu);
  return SQLInsert("
    INSERT INTO messages(idConversation, idAuteur, contenu)
    VALUES ('$idConversation', '$idAuteur', '$contenu');
  ");
}

function listerMessages($idConv,$format="asso")
{
  // Liste les messages de cette conversation, au format JSON ou tableau associatif
  // Champs à extraire : contenu, auteur, couleur 
  // en ne renvoyant pas les utilisateurs blacklistés
  $res = parcoursRs(SQLSelect("
    SELECT
      messages.contenu AS contenu,
      users.pseudo AS auteur,
      users.couleur AS couleur
    FROM messages
    JOIN users ON users.id = messages.idAuteur
    WHERE messages.idConversation = '$idConv'
    AND users.blacklist=0
    ORDER BY messages.id DESC;
  "));
  if (strtoupper($format) == 'JSON') {
    return json_encode($res);
  } else {
    return $res;
  }
}

function listerMessagesFromIndex($idConv,$index)
{
  // Liste les messages de cette conversation, 
  // dont l'id est superieur à l'identifiant passé
  // Champs à extraire : contenu, auteur, couleur 
  // en ne renvoyant pas les utilisateurs blacklistés
  return parcoursRs(SQLSelect("
    SELECT
      messages.contenu AS contenu,
      users.pseudo AS auteur,
      users.couleur AS couleur
    FROM messages
    JOIN users ON users.id = messages.idAuteur
    WHERE messages.idConversation = '$idConv'
      AND messages.id > '$index';
  "));
}

function getConversation($idConv)
{  
  // Récupère les données de la conversation (theme, active)
  $tab = parcoursRs(SQLSelect("
    SELECT theme, active
    FROM conversations
    WHERE id='$idConv';
  "));
  if (count($tab)) return $tab[0];
  else return false; //Quand la conv n'existe pas
}



?>
