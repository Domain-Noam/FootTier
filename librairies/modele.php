<?php

// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php"); 


function verifUserBdd($login,$passe)
{
  // Vérifie l'identité d'un utilisateur 
  // dont les identifiants sont passes en paramètre
  // renvoie faux si user inconnu
  // renvoie l'id de l'utilisateur si succès

  $SQL="SELECT id_user FROM utilisateur WHERE pseudo='$login' AND mot_de_passe='$passe';";

  return SQLGetChamp($SQL);
  // si on avait besoin de plus d'un champ
  // on aurait du utiliser SQLSelect
}

function verifUserBddHash($login, $passe){
	//Vérifie l'identité via bcrypt (algorithme de hashage qui stocke des mots de passe de façon sécurisée) : on récupère le hash stocké puis password_verify()
	$SQL = "SELECT id_user, mot_de_passe FROM utilisateur WHERE pseudo='$login';";
	$resultat = SQLSelect($SQL);

	if(!$resultat){
    	return false;
	}

	$resultat = parcoursRs($resultat);
	if(password_verify($passe, $resultat[0]['mot_de_passe'])){
		return $resultat[0]['id_user'];  //on retourne l'id
	}

	return false; // Mauvais mot de passe
}

function isAdmin($idUser)
{
  // vérifie si l'utilisateur est un administrateur
  $SQL ="SELECT est_admin FROM utilisateur WHERE id_user='$idUser';";
  return SQLGetChamp($SQL); 
}


//Création de nouveaux utilisateurs
function creerUtilisateurHash($pseudo, $passe){
    //Hachage du mot de passe avec Bcrypt comme demandé
    $hash = password_hash($passe, PASSWORD_BCRYPT);

    //(est_admin est à 0 par défaut, NOW() met la date actuelle)
    $sql = "INSERT INTO utilisateur (pseudo, mot_de_passe, est_admin, date_inscription) VALUES ('$pseudo', '$hash', 0, NOW());";
    
    return SQLInsert($sql);
}


//Récupère les tierlists publiques les plus populaires (triées par nombre de likes)
//Retourne un tableau de tableaux associatifs contenant les infos des tierlists qui sont triées selon le nombre de likes dans le tableau
function getPopulariteTierlists(){

    $sql = "SELECT t.titre, u.pseudo AS createur, t.date_creation, COUNT(like.id_user) AS nb_likes FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user JOIN like_tierlist AS like ON t.id_tierlist = like.id_tierlist
            WHERE t.est_publique = 1 GROUP BY t.id_tierlist, t.titre, u.pseudo, t.date_creation ORDER BY nb_likes DESC, t.date_creation DESC;";
    
    return parcoursRs(SQLSelect($sql));
}

//Récupère le contenu détaillé d'une tierlist spécifique (ses actions et leurs rangs)
//Paramètre : $idTierlist l'identifiant de la tierlist à charger
//Retourne un tableau contenant les actions classées par Tier
function getContenuTierlist($idTierlist){
    $sql = "SELECT ct.tier, af.joueur, af.competition, af.annee, c.nom_categorie FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action JOIN categorie AS c ON af.id_categorie = c.id_categorie
            WHERE ct.id_tierlist = $idTierlist (variable PHP car l'ID changera selon la tierlist cliquée) ORDER BY ct.tier ASC, af.annee DESC;";

    return parcoursRs(SQLSelect($sql));
}

//Récupère la liste chronologique des commentaires d'une tierlist
//Paramètre : $idTierlist l'identifiant de la tierlist consultée
//Retourne la liste des commentaires avec le pseudo de l'auteur
function getCommentairesTierlist($idTierlist){
    $sql = "SELECT u.pseudo, c.contenu, c.date_publication FROM commentaire AS c JOIN utilisateur AS u ON c.id_user = u.id_user
            WHERE c.id_tierlist = $idTierlist (variable PHP car l'ID changera selon la tierlist cliquée) ORDER BY c.date_publication DESC;";

    return parcoursRs(SQLSelect($sql));
}

//Recherche les tierlists publiques contenant au moins une action d'un joueur précis
//Paramètre : $nomJoueur le nom exact du joueur recherché
//Retourne la liste des tierlists correspondantes
function RechercheTierlistsParJoueur($nomJoueur){
    $sql = "SELECT t.titre, u.pseudo AS createur, t.date_creation FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user
            WHERE t.est_publique = 1 AND t.id_tierlist IN (SELECT ct.id_tierlist FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action WHERE af.joueur = $nomJoueur)
            ORDER BY t.date_creation DESC;";

    return parcoursRs(SQLSelect($sql));
}

//Recherche les tierlists publiques créées par un utilisateur (recherche partielle)
//Paramètre : $pseudo le pseudo (ou partie du pseudo) recherché
//Retourne la liste des tierlists trouvées classées par popularité
function rechercheTierlistsParPseudo($pseudo){
    $sql = "SELECT t.titre, u.pseudo AS createur, COUNT(like.id_user) AS nb_likes FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user JOIN like_tierlist AS like ON t.id_tierlist = like.id_tierlist
            WHERE t.est_publique = 1 AND u.pseudo LIKE '%$pseudo%'
            GROUP BY t.id_tierlist, t.titre, u.pseudo
            ORDER BY nb_likes DESC;";

    return parcoursRs(SQLSelect($sql));
}


//Récupère les tierlists non publiées (brouillons) d'un utilisateur connecté
//Paramètre : $idUser l'identifiant de l'utilisateur connecté
//Retourne la liste des brouillons en cours d'édition
function getBrouillonsUtilisateur($idUser){
    $sql = "SELECT t.titre, t.date_modification, COUNT(ct.id_action) AS nb_actions_sauvegardees FROM tierlist t JOIN contenu_tierlist AS ct ON t.id_tierlist = ct.id_tierlist
            WHERE t.id_user = $idUser AND t.est_publique = 0
            GROUP BY t.id_tierlist, t.titre, t.date_modification
            ORDER BY t.date_modification DESC;";

    return parcoursRs(SQLSelect($sql));
}


//Ajouter un commentaire
function ajouterCommentaire($idTierlist, $idUser, $msg){
    $msgSecurise = addslashes(htmlspecialchars(trim($msg))); //On rend en texte les / (addslashes), 
    //on rend les @, &, et autres caractères spécials en text (htmlspecialchars) et on enlève les espaces en trop en début et fin (trim)
    if(empty($msgSecurise)){
      return false;
    }

    $sql = "INSERT INTO commentaire (contenu, date_publication, id_user, id_tierlist)
            VALUES ('$msgSecurise', NOW(), $idUser, $idTierlist);";

    return SQLInsert($sql);
}


//Vérifier si un utilisateur a déjà liké une tierlist
function aDejaLike($idUser, $idTierlist){
    $sql = "SELECT COUNT(*) FROM like_tierlist WHERE id_user = $idUser AND id_tierlist = $idTierlist;";

    return SQLGetChamp($sql) > 0; //Renvoie faux si aucun like et vrai si déjà un like
}


//Ajouter ou retirer un like
//Ajout si pas liké, retrait sinon
function enregistrerVoteLike($idTierlist, $idUser){
    if(aDejaLike($idUser, $idTierlist)){
        //Si l'utilisateur avait déjà liké alors on retire
        $sql = "DELETE FROM like_tierlist WHERE id_user = $idUser AND id_tierlist = $idTierlist;";
        SQLDelete($sql);
        return false; //Le like a été retiré
    }
    else{
        //Si l'utilisateur n'a pas encore liké alors on ajoute
        $sql = "INSERT INTO like_tierlist (id_user, id_tierlist) VALUES ($idUser, $idTierlist);";
        SQLInsert($sql);
        return true;  //Le like a été ajouté
    }
}


//Récupérer les infos principales d'une tierlist (titre, auteur, nb likes)
function getTierlistParId($idTierlist){    
    $sql = "SELECT t.id_tierlist, t.titre, t.date_creation, t.date_modification, u.pseudo, (SELECT COUNT(*) FROM like_tierlist AS like WHERE like.id_tierlist = t.id_tierlist) AS nb_likes
            FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user WHERE t.id_tierlist = $idTierlist;";

    $lignes = parcoursRs(SQLSelect($sql));

    if(!empty($lignes)){
        return $lignes[0];
    }

    return false; //Si aucune tierlist ne correspond à cet ID car empty renvoyé 1
}


//Récupérer le contenu d'une tierlist avec les actions triées par tier
function getContenuTierlist($idTierlist){
    $sql = "SELECT ct.tier, af.joueur, af.competition, af.url_image, c.nom_categorie FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action JOIN categorie AS c ON af.id_categorie = c.id_categorie
            WHERE ct.id_tierlist = $idTierlist
            ORDER BY ct.tier ASC, af.competition DESC;";

    $lignes = parcoursRs(SQLSelect($sql));

    //On regroupe par tier pour faciliter l'affichage 
    $parTier = [];
    foreach($lignes as $ligne){
        $parTier[$ligne["tier"]][] = $ligne;
    }
    return $parTier; 
}


//Récupérer les commentaires d'une tierlist
function getCommentairesTierlist($idTierlist){
    $sql = "SELECT u.pseudo,c.contenu, c.date_publication FROM commentaire AS c JOIN utilisateur AS u ON c.id_user = u.id_user
            WHERE c.id_tierlist = $idTierlist
            ORDER BY c.date_publication DESC;";

    return parcoursRs(SQLSelect($sql));
}


//Compter les commentaires d'une tierlist (c'est simplement pour notre affichage au-dessus des commentaires dans la vue detail_tierlist
function getNbCommentaires($idTierlist){
    $sql = "SELECT COUNT(*) FROM commentaire WHERE id_tierlist = $idTierlist;";

    return SQLGetChamp($sql); //Cela va retourner directement le nombre de commentaires
}


?>
