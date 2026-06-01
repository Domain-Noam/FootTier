<?php

include_once("maLibSQL.pdo.php"); 

/*FONCTIONS POUR CONNEXION/INSCRIPTION*/

function verifUserBdd($login,$passe)
{
  // Vérifie l'identité d'un utilisateur
  // dont les identifiants sont passes en paramètre
  // renvoie faux si user inconnu
  // renvoie l'id de l'utilisateur si succès

  $SQL="SELECT id_user FROM utilisateur WHERE pseudo='$login' AND mot_de_passe='$passe'";

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


//Création de nouveaux utilisateurs
function creerUtilisateurHash($pseudo, $passe){
    //Hachage du mot de passe avec Bcrypt comme demandé
    $hash = password_hash($passe, PASSWORD_BCRYPT);

    //(est_admin est à 0 par défaut, NOW() met la date actuelle)
    $sql = "INSERT INTO utilisateur (pseudo, mot_de_passe, est_admin, date_inscription) VALUES ('$pseudo', '$hash', 0, NOW())";
    
    return SQLInsert($sql);
}


//vérifie si l'utilisateur est un administrateur
function isAdmin($idUser)
{
  $SQL ="SELECT est_admin FROM utilisateur WHERE id_user='$idUser'";
  return SQLGetChamp($SQL); 
}

/*FONCTIONS RÉALISÉES DANS LE LIVRABLE 2*/

//Récupère les tierlists publiques les plus populaires (triées par nombre de likes)
//Retourne un tableau de tableaux associatifs contenant les infos des tierlists qui sont triées selon le nombre de likes dans le tableau
function getPopulariteTierlists(){
    //on fait un left join pour récupérer les tierlists même avec 0 like
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, t.date_creation, COUNT(l.id_user) AS nb_likes FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user LEFT JOIN like_tierlist AS l ON t.id_tierlist = l.id_tierlist
            WHERE t.est_publique = '1' 
            GROUP BY t.id_tierlist, t.titre, u.pseudo, t.date_creation 
            ORDER BY nb_likes DESC, t.date_creation DESC;";
    
    return parcoursRs(SQLSelect($sql));
}

//Récupère le contenu détaillé d'une tierlist spécifique (ses actions et leurs rangs)
//Paramètre : $idTierlist l'identifiant de la tierlist à charger
//Retourne un tableau contenant les actions classées par Tier et triées de S à D
function getContenuTierlist($idTierlist){
    $sql = "SELECT ct.tier, af.joueur, af.competition, af.url_image, c.nom_categorie FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action JOIN categorie AS c ON af.id_categorie = c.id_categorie
            WHERE ct.id_tierlist = $idTierlist ORDER BY ct.tier ASC, af.competition DESC;";

    $lignes = parcoursRs(SQLSelect($sql));

    //On regroupe par tier pour faciliter l'affichage 
    $parTier = [];
    foreach($lignes as $ligne){
        $parTier[$ligne["tier"]][] = $ligne;
    }
    return $parTier; //Comme parcoursRs c'est déjà un tableau de tableau associatifs, et que $parTier est un tableau de ces tableaux, alors on a 3 dimensions
}

//Récupère la liste chronologique des commentaires d'une tierlist
//Paramètre : $idTierlist l'identifiant de la tierlist consultée
//Retourne la liste des commentaires avec le pseudo de l'auteur
function getCommentairesTierlist($idTierlist){
    $sql = "SELECT u.pseudo, c.contenu, c.date_publication FROM commentaire AS c JOIN utilisateur AS u ON c.id_user = u.id_user
            WHERE c.id_tierlist = $idTierlist ORDER BY c.date_publication DESC;";

    return parcoursRs(SQLSelect($sql));
}


//Recherche les tierlists publiques contenant au moins une action d'un joueur précis
//Paramètre : $nomJoueur le nom exact du joueur recherché
//Retourne la liste des tierlists correspondantes
function RechercheTierlistsParJoueur($nomJoueur){
    //ajout de t.id_tierlist et sécurisation de la variable dans le LIKE
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, t.date_creation FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user
            WHERE t.est_publique = '1' AND t.id_tierlist IN (SELECT ct.id_tierlist FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action WHERE af.joueur LIKE '%$nomJoueur%')
            ORDER BY t.date_creation DESC;";

    return parcoursRs(SQLSelect($sql));
}

//Recherche les tierlists publiques créées par un utilisateur (recherche partielle)
//Paramètre : $pseudo le pseudo (ou partie du pseudo) recherché
//Retourne la liste des tierlists trouvées classées par popularité
function rechercheTierlistsParPseudo($pseudo){
    $sql = "SELECT t.titre, u.pseudo AS createur, COUNT(lt.id_user) AS nb_likes FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user JOIN like_tierlist AS lt ON t.id_tierlist = lt.id_tierlist
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


/*POUR LA VUE ADMINISTRATION*/

//récupère tous les utilisateurs inscrits pour le panneau d'administration
function getUtilisateurs() {
    $sql = "SELECT id_user, pseudo, est_admin, date_inscription 
            FROM utilisateur 
            ORDER BY id_user ASC;";
    return parcoursRs(SQLSelect($sql)); 
}

//récupère la liste des catégories pour le menu déroulant d'ajout d'action
function getCategories() {
    $sql = "SELECT id_categorie, nom_categorie 
            FROM categorie 
            ORDER BY nom_categorie ASC;";
    return parcoursRs(SQLSelect($sql));
}

//récupérer une seule action pour la pré-remplir dans le formulaire de modification
function getActionById($idAction) {
    $sql = "SELECT * FROM action_foot WHERE id_action = '$idAction';";
    return parcoursRs(SQLSelect($sql));
}

//modifie le rôle d'un membre pour le passer administrateur
function promouvoirAdmin($idUser) {
    $sql = "UPDATE utilisateur SET est_admin = 1 WHERE id_user = '$idUser';";
    return SQLUpdate($sql);
}

//rétrograde un administrateur en membre
function demettreAdmin($idUser) {
    $sql = "UPDATE utilisateur SET est_admin = 0 WHERE id_user = '$idUser';";
    return SQLUpdate($sql);
}

//supprime définitivement un compte utilisateur (bannissement)
function bannirUtilisateur($idUser) {
    $sql = "DELETE FROM utilisateur WHERE id_user = '$idUser';";
    return SQLDelete($sql);
}

//ajoute une nouvelle action
function ajouterNouvelleAction($joueur, $competition, $id_categorie, $url_image, $url_media) {
    //les variables ont déjà été sécurisées par valider() dans le contrôleur (qui appelle protéger() et addslashes)
    $sql = "INSERT INTO action_foot (joueur, competition, id_categorie, url_image, url_media) 
            VALUES ('$joueur', '$competition', '$id_categorie', '$url_image', '$url_media');";
    
    if (SQLInsert($sql)) {
        return 1;
    } else {
        return 0;
    }
}

//récupère toutes les actions enregistrées pour alimenter le bloc 3 (gestion)
function getActions() {
    $sql = "SELECT af.id_action, af.joueur, af.competition, af.url_image, c.nom_categorie 
            FROM action_foot af 
            JOIN categorie c ON af.id_categorie = c.id_categorie 
            ORDER BY af.id_action DESC;";
    return parcoursRs(SQLSelect($sql));
}

//supprime définitivement une action
function supprimerAction($idAction) {
    $sql = "DELETE FROM action_foot WHERE id_action = '$idAction';";
    if (SQLDelete($sql)) {
        return 1;
    } else {
        return 0;
    }
}

/*POUR LA VUE DE GALERIE*/ 

//recherche les tierlists publiques selon leur catégorie
function rechercheTierlistsParCategorie($idCat){
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, COUNT(l.id_user) AS nb_likes 
            FROM tierlist AS t 
            JOIN utilisateur AS u ON t.id_user = u.id_user 
            LEFT JOIN like_tierlist AS l ON t.id_tierlist = l.id_tierlist
            WHERE t.est_publique = '1' AND t.id_categorie = '$idCat'
            GROUP BY t.id_tierlist, t.titre, u.pseudo
            ORDER BY nb_likes DESC;";

    return parcoursRs(SQLSelect($sql));
}


/*POUR LA VUE BROUILLON*/ 
/*Toutes les fonctions utiles sont réparties déjà pour les autres vues*/

/*POUR LA VUE DE DETAIL_TIERLIST*/

//Ajouter un commentaire
function ajouterCommentaire($idTierlist, $idUser, $msg){
    $msgPropre = htmlspecialchars(trim($msg));  //on rend les @, &, et autres caractères spécials en text (htmlspecialchars) et 
    //on enlève les espaces en trop en début et fin (trim)
    if(empty($msgPropre)){
      return false;
    }

    $msgSecurise = proteger($msgPropre);

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
    $sql = "SELECT t.id_tierlist, t.titre, t.date_creation, t.date_modification, u.pseudo, (SELECT COUNT(*) FROM like_tierlist AS lt WHERE lt.id_tierlist = t.id_tierlist) AS nb_likes
            FROM tierlist AS t JOIN utilisateur AS u ON t.id_user = u.id_user WHERE t.id_tierlist = $idTierlist;";

    $lignes = parcoursRs(SQLSelect($sql));

    if(!empty($lignes)){
        return $lignes[0];
    }

    return false; //Si aucune tierlist ne correspond à cet ID car empty renvoyé 1
}


//Compter les commentaires d'une tierlist (c'est simplement pour notre affichage au-dessus des commentaires dans la vue detail_tierlist
function getNbCommentaires($idTierlist){
    $sql = "SELECT COUNT(*) FROM commentaire WHERE id_tierlist = $idTierlist;";

    return SQLGetChamp($sql); //Cela va retourner directement le nombre de commentaires
}


/*POUR LA VUE CRÉATION*/

// un nouveau brouillon vide pour l'utilisateur et retourne son ID.ou false si échec
//est_publique = 0 par défaut 
function creerNouveauBrouillon($idUser, $titre){
    $titrePropre = htmlspecialchars(trim($titre));
    if(empty($titrePropre)){
        $titrePropre = "Sans titre";
    }
    $titreSecurise = proteger($titrePropre);
    $sql = "INSERT INTO tierlist (titre, est_publique, date_creation, date_modification, id_user) VALUES ('$titreSecurise', 0, NOW(), NOW(), $idUser);";
    return SQLInsert($sql); 
}


//Récupère les infos d'une tierlist appartenant à un utilisateur précis pour préremplir la vue de création
function getTierlistPourEdition($idTierlist, $idUser){
    $sql = "SELECT t.id_tierlist, t.titre, t.est_publique, t.date_creation, t.date_modification, t.id_categorie, c.nom_categorie
            FROM tierlist AS t LEFT JOIN categorie AS c ON t.id_categorie = c.id_categorie
            WHERE t.id_tierlist = $idTierlist AND t.id_user = $idUser;";

    $lignes = parcoursRs(SQLSelect($sql));

    if(!empty($lignes)){
        return $lignes[0]; 
    }
    return false;
}


//Récupère toutes les actions de la bibliothèque qui ne sont pas encore placées dans la tierlist
function getActionsRestantes($idTierlist){
    $sql = "SELECT af.id_action, af.joueur, af.competition, af.url_image, c.nom_categorie FROM action_foot AS af
            JOIN categorie AS c ON af.id_categorie = c.id_categorie
            WHERE af.id_action NOT IN (SELECT ct.id_action FROM contenu_tierlist AS ct WHERE ct.id_tierlist = $idTierlist)
            ORDER BY c.nom_categorie ASC, af.joueur ASC;";

    return parcoursRs(SQLSelect($sql));
}


//Met à jour l'emplacement d'une action dans la tierlist 
//Si l'action est déjà dans contenu_tierlist pour cette tierlist, on met à jour le tier et sinon on insère
//Retourne true si succès sinon false
function sauvegarderPlacement($idTierlist, $idAction, $tier){
    /*$tiersValides = ["S", "A", "B", "C", "D"];
    if(!in_array($tier, $tiersValides)){
        return false; //Le tier n'est pas bon
    }*/

    //On vérifie si le placement existe déjà
    $sqlVerifier = "SELECT COUNT(*) FROM contenu_tierlist WHERE id_tierlist = $idTierlist AND id_action = $idAction;";
    $existe = SQLGetChamp($sqlVerifier) > 0;

    if($existe){
        //On met à jour si l'action était déjà placée
        $sql = "UPDATE contenu_tierlist SET tier = '$tier' WHERE id_tierlist = $idTierlist AND id_action = $idAction;";
        return SQLUpdate($SQL); ;
    }
    else{
        //On insert un nouveau placement
        $sql = "INSERT INTO contenu_tierlist (id_tierlist, id_action, tier) VALUES ($idTierlist, $idAction, '$tier');";
        return SQLInsert($sql);
    }
}


//Supprime tous les placements d'actions retournées en bibliothèque d'actions, donc un drag and drop mais de la tierlist vers les vidéos 
//Ou quand on publie, on remet tout dans la bibliothèque
function supprimerPlacement($idTierlist, $idAction){
    $sql = "DELETE FROM contenu_tierlist WHERE id_tierlist = $idTierlist AND id_action = $idAction;";
    return SQLDelete($sql);
}


//Publie un brouillon, donc on passe est_publique à 1 et on met à jour le titre 
function publierBrouillon($idTierlist, $titre){
    $titrePropre = htmlspecialchars(trim($titre));
    if(empty($titrePropre)){
        $titrePropre = "Sans titre";
    }
    $titreSecurise = proteger($titrePropre);

    $sql = "UPDATE tierlist SET est_publique = 1, titre = '$titreSecurise', date_modification = NOW() WHERE id_tierlist = $idTierlist;";
    
    return SQLUpdate($sql);
}


//Sauvegarde le titre d'un brouillon et met à jour date_modification
function mettreAJourTitreBrouillon($idTierlist, $titre){
    $titrePropre = htmlspecialchars(trim($titre));
    if(empty($titrePropre)){
        $titrePropre = "Sans titre";
    }
    $titreSecurise = proteger($titrePropre);

    $sql = "UPDATE tierlist SET titre = '$titreSecurise', date_modification = NOW() WHERE id_tierlist = $idTierlist;";

    return SQLUpdate($sql);
}


//Supprime définitivement un brouillon et tout son contenu (contenu_tierlist en cascade)
function supprimerTierlist($idTierlist){
    //On suprime le contenu
    $sql = "DELETE FROM contenu_tierlist WHERE id_tierlist = $idTierlist;";
    SQLDelete($sql);
    //Ensuite les likes associés
    $sql = "DELETE FROM like_tierlist WHERE id_tierlist = $idTierlist;";
    SQLDelete($sql);
    //Puis les commentaires
    $sql = "DELETE FROM commentaire WHERE id_tierlist = $idTierlist;";
    SQLDelete($sql);
    //Et enfin la tierlist elle-même
    $sql = "DELETE FROM tierlist WHERE id_tierlist = $idTierlist;";
    return SQLDelete($sql); //Nous avons mis le return ici comme c'est le dernier DELETE
}




?>
