<?php
//sécurité : on empêche l'accès direct à la page
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php");
	die("");
}
include_once "librairies/maLibForms.php";

$rechercheJoueur = valider("joueur");
$recherchePseudo = valider("pseudo");
$rechercheCat = valider("categorie");
$categories = getCategories();

if($rechercheJoueur != false){
	$tierlists = RechercheTierlistsParJoueur($rechercheJoueur);
} 
else if($recherchePseudo != false){
    $tierlists = rechercheTierlistsParPseudo($recherchePseudo);
} 
else if($rechercheCat != false){
	$tierlists = rechercheTierlistsParCategorie($rechercheCat);
}
else{
	$tierlists = getPopulariteTierlists();
}

?>

<h1 class="titreGalerie">La Galerie des Tierlists de la Communauté</h1>

<div class="bloc-recherche-galerie">
    
    <?php mkForm("index.php", "GET"); //création du formulaire de recherche ?>
    <?php mkInput("hidden", "view", "galerie"); //on conserve le routage sur la galerie ?>
    
    <div class="row">
        <div class="col-md-3">
            <?php 
            mkInput("text", "joueur", valider("joueur", "GET"), "class=\"form-control input-recherche\" placeholder=\"Joueur (Ex: Mbappé)\""); 
            ?>
        </div>
        
        <div class="col-md-3">
            <?php 
            mkInput("text", "pseudo", valider("pseudo", "GET"), "class=\"form-control input-recherche\" placeholder=\"Créateur\""); 
            ?>
        </div>

        <div class="col-md-4">
            <?php
            //On réalise le select pour les catégories
            $tabCat = array();
            $tabCat[] = array("id" => "", "label" => "Catégorie (Toutes)");
            
            if ($categories != false) {
                foreach($categories as $cat) {
                    $tabCat[] = array("id" => $cat['id_categorie'], "label" => $cat['nom_categorie']);
                }
            }
            mkSelect("categorie", $tabCat, "id", "label", valider("categorie", "GET"));
            ?>
        </div>
        
        <div class="col-md-2">
            <?php 
            mkInput("submit", "btn_recherche", "Filtrer", "class=\"btn btn-primary btn-block bouton-filtrer\""); 
            ?>
        </div>
    </div>
    
    <?php endForm(); // ?>
</div>

<div class="row">
    <?php
    //si la base de données possède des tierlists publiques correspondantes
    if ($tierlists != false) {
        
        //on parcourt chaque ligne du jeu de résultats SQL
        foreach ($tierlists as $tl) {
            
            //on extrait l'ID unique requis par Noam pour sa vue de détails
            $idTierlist = $tl['id_tierlist']; 
            $lienDetail = "index.php?view=detail_tierlist&idTierlist=" . $idTierlist;
            ?>
            
            <div class="col-md-3 colonne-carte">
                <div class="carte-tierlist">
                    
                    <a href="<?php echo $lienDetail; ?>">
                        <img src="ressources/img/tierlist_placeholder.png" alt="Aperçu Tierlist" class="image-tierlist">
                    </a>
                    
                    <div class="infos-tierlist">
                        
                        <div class="texte-likes">
                            ♡ <?php
                            if (isset($tl['nb_likes']) && $tl['nb_likes'] != "") {
                                echo $tl['nb_likes'];
                            } else {
                                echo "0";
                            }
                            ?>
                        </div>
                        
                        <a href="<?php echo $lienDetail; ?>" class="lien-titre">
                            <?php echo htmlspecialchars($tl['titre']); ?>
                        </a>
                    </div>
                    
                    <div class="texte-auteur">
                        Par <strong><?php echo htmlspecialchars($tl['createur']); ?></strong>
                    </div>

                </div>
            </div>
            
            <?php
        } //fin de la boucle foreach
        
    } else {
        //message d'alerte si aucun résultat n'est retourné par le modèle
        echo '<div class="col-md-12 text-center message-vide">Aucune tierlist trouvée pour cette recherche.</div>';
    }
    ?>
</div>