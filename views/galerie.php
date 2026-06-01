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

$tiers = ["S", "A", "B", "C", "D"];
$couleurs = ["S"=>"#e72925", "A"=>"#e47e01", "B"=>"#b69b02", "C"=>"#4aaf4f", "D"=>"#1b7bcf"];

?>

<h1 class="titreGalerie">La Galerie des Tierlists de la Communauté</h1>

<div class="bloc-recherche-galerie">
    
    <?php mkForm("index.php", "GET"); //création du formulaire de recherche ?>
    <?php mkInput("hidden", "view", "galerie"); //on conserve le routage sur la galerie ?>
    
    <div class="row">
        <div class="col-md-3">
            <?php 
            mkInput("text", "joueur", valider("joueur"), "class=\"form-control input-recherche\" placeholder=\"Joueur (Ex: Mbappé)\""); 
            ?>
        </div>
        
        <div class="col-md-3">
            <?php 
            mkInput("text", "pseudo", valider("pseudo"), "class=\"form-control input-recherche\" placeholder=\"Créateur\""); 
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
        foreach($tierlists as $t){
            $idTierlist = $t['id_tierlist']; 
            $contenuParTier = getContenuTierlist($idTierlist);  
            echo "<div class=\"col-md-3 colonne-carte\">";
            echo "<div class=\"carte-tierlist\">";
            echo "<a href=\"index.php?view=detail_tierlist&idTierlist=$idTierlist\">";
            //On affiche la tierlist en petit comme dans la vue detail_tierlist
            echo "<div class=\"detailTierlist\">";
            foreach($tiers as $tier){
                echo "<div class=\"ligne\">";
                echo "<p class=\"lettre\" style=\"background-color: $couleurs[$tier];\">";
                echo $tier . "</p>";

                echo "<div class=\"vignettes\">";
                if(!empty($contenuParTier[$tier])){
                    foreach($contenuParTier[$tier] as $vignette){
                        echo "<div class=\"detailVignette\">";
                        if(!empty($vignette["url_image"])){
                            echo "<img src=" . htmlspecialchars($vignette["url_image"]) . " alt=" . htmlspecialchars($vignette["joueur"]) . ">";
                        }
                        else{
                            echo "<div class=\"pasVignette\"></div>";
                        }
                        echo "</div>";
                    }
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</a>";
            echo "<div class=\"infos-tierlist\">";
            echo "<div class=\"texte-likes\">";
            echo "♡";
            if (valider("nb_likes")){
                echo $tl['nb_likes'];
            }
            else{
                echo "0";
            }
            echo "</div>";
            echo "<a href=\"index.php?view=detail_tierlist&idTierlist=$idTierlist\" class=\"lien-titre\">";
            echo htmlspecialchars($t['titre']); 
            echo "</a>";
            echo "</div>";
            
            echo "<div class=\"texte-auteur\">";
            echo "Par <strong>" . htmlspecialchars($t['createur']) . "</strong>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }     
    }
    else{
        //message d'alerte si aucun résultat n'est retourné par le modèle
        echo "<div class=\"col-md-12 text-center message-vide\">Aucune tierlist trouvée pour cette recherche.</div>";
    }
    ?>
</div>