<?php

// inclure ici la librairie facilitant les requêtes SQL
include_once("maLibSQL.pdo.php"); 


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

//Récupère le contenu détaillé d'une tierlist spécifique (ses actions et leurs rangs)
//Paramètre : $idTierlist l'identifiant de la tierlist à charger
//Retourne un tableau contenant les actions classées par Tier
function getContenuTierlist($idTierlist){
    // Correction: Retrait du texte parasite pour ne garder que la variable SQL
    $sql = "SELECT ct.tier, af.joueur, af.competition, af.annee, c.nom_categorie FROM contenu_tierlist AS ct JOIN action_foot AS af ON ct.id_action = af.id_action JOIN categorie AS c ON af.id_categorie = c.id_categorie
            WHERE ct.id_tierlist = '$idTierlist' ORDER BY ct.tier ASC, af.annee DESC;";

    return parcoursRs(SQLSelect($sql));
}

//Récupère la liste chronologique des commentaires d'une tierlist
//Paramètre : $idTierlist l'identifiant de la tierlist consultée
//Retourne la liste des commentaires avec le pseudo de l'auteur
function getCommentairesTierlist($idTierlist){
    $sql = "SELECT u.pseudo, c.contenu, c.date_publication FROM commentaire AS c JOIN utilisateur AS u ON c.id_user = u.id_user
            WHERE c.id_tierlist = '$idTierlist' ORDER BY c.date_publication DESC;";

    return parcoursRs(SQLSelect($sql));
}

// =============================================
// FONCTIONS REQUISES POUR L'ADMINISTRATION
// =============================================

//vérifie si l'utilisateur est un administrateur
function isAdmin($idUser)
{
  $SQL ="SELECT est_admin FROM utilisateur WHERE id_user='$idUser'";
  return SQLGetChamp($SQL); 
}

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

// =============================================
// FONCTIONS REQUISES POUR LA GALERIE
// =============================================

//récupère les tierlists publiques les plus populaires (triées par nombre de likes)
function getPopulariteTierlists(){
    //on fait un left join pour récupérer les tierlists même avec 0 like
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, t.date_creation, COUNT(l.id_user) AS nb_likes 
            FROM tierlist AS t 
            JOIN utilisateur AS u ON t.id_user = u.id_user 
            LEFT JOIN like_tierlist AS l ON t.id_tierlist = l.id_tierlist
            WHERE t.est_publique = '1' 
            GROUP BY t.id_tierlist, t.titre, u.pseudo, t.date_creation 
            ORDER BY nb_likes DESC, t.date_creation DESC;";
    
    return parcoursRs(SQLSelect($sql));
}

//recherche les tierlists publiques contenant au moins une action d'un joueur précis
function RechercheTierlistsParJoueur($nomJoueur){
    //ajout de t.id_tierlist et sécurisation de la variable dans le LIKE
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, t.date_creation 
            FROM tierlist AS t 
            JOIN utilisateur AS u ON t.id_user = u.id_user
            WHERE t.est_publique = '1' AND t.id_tierlist IN (
                SELECT ct.id_tierlist
                FROM contenu_tierlist AS ct
                JOIN action_foot AS af ON ct.id_action = af.id_action
                WHERE af.joueur LIKE '%$nomJoueur%'
            )
            ORDER BY t.date_creation DESC;";

    return parcoursRs(SQLSelect($sql));
}

//recherche les tierlists publiques créées par un utilisateur (recherche partielle)
function rechercheTierlistsParPseudo($pseudo){
    $sql = "SELECT t.id_tierlist, t.titre, u.pseudo AS createur, COUNT(l.id_user) AS nb_likes 
            FROM tierlist AS t 
            JOIN utilisateur AS u ON t.id_user = u.id_user 
            LEFT JOIN like_tierlist AS l ON t.id_tierlist = l.id_tierlist
            WHERE t.est_publique = '1' AND u.pseudo LIKE '%$pseudo%'
            GROUP BY t.id_tierlist, t.titre, u.pseudo
            ORDER BY nb_likes DESC;";

    return parcoursRs(SQLSelect($sql));
}

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

// =============================================
// FONCTIONS REQUISES POUR BROUILLON
// =============================================

//récupère les tierlists non publiées (brouillons) d'un utilisateur connecté
function getBrouillonsUtilisateur($idUser) {
    // est_publique = 0 signifie que c'est un brouillon
    $sql = "SELECT id_tierlist, titre, date_modification 
            FROM tierlist 
            WHERE id_user = '$idUser' AND est_publique = '0'
            ORDER BY date_modification DESC";
            
    return parcoursRs(SQLSelect($sql));
}

//supprime définitivement un brouillon et son contenu associé
function supprimerTierlist($idTierlist) {
    //on supprime d'abord les vignettes placées dedans (pour respecter les contraintes de la BDD)
    $sqlContenu = "DELETE FROM contenu_tierlist WHERE id_tierlist = '$idTierlist'";
    SQLDelete($sqlContenu);
    
    //puis on supprime la tierlist elle-même
    $sqlTierlist = "DELETE FROM tierlist WHERE id_tierlist = '$idTierlist'";
    return SQLDelete($sqlTierlist);
}

?>
