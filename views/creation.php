<?php

if(basename($_SERVER["PHP_SELF"]) != "index.php"){
	header("Location:../index.php?view=connexion");
	die("");
}

securiser('index.php?view=connexion');

//Si idTierlist est passé par l'URL en method="GET", on reprend un brouillon existant et sinon, on crée un nouveau brouillon vide automatiquement
$idTierlist = valider("idTierlist");
$idUser = valider("idUser", "SESSION");

if(!$idTierlist){
    $idTierlist = creerNouveauBrouillon($idUser, "Sans titre");
    if(!$idTierlist){
        // Si la création échoue (ex: BDD indispo), on renvoie vers la galerie
        header("Location: index.php?view=galerie");
        die("");
    }
}

//On récupère les infos de la tierlist pour préremplir la vue avec les informations qu'il faut
//Donc, si le brouillon existait déjà avant, des infos vont être mises, sinon tout sera vide avec juste le titre qui aura "Sans titre"
$infosTierlist = getTierlistPourEdition($idTierlist, $idUser);
if(!$infosTierlist){
    header("Location: index.php?view=galerie"); //La tierlist n'existe pas ou elle n'appartient pas au bon utilisateur (donc si l'utilisateur tente de faire crasher notre site via l'URL)
    die("");
}

// On récupère les infos de la tierlist (titre, type…) pour préremplir le formulaire
$infosTierlist = getTierlistPourEdition($idTierlist, $idUser);
if(!$infosTierlist){
    // Cette tierlist n'existe pas ou n'appartient pas à cet utilisateur
    header("Location: index.php?view=galerie");
    die("");
}

$contenuParTier = getContenuTierlist($idTierlist); //On récupère le contenu de la tierlist si c'est un brouillon qui existait déjà

$actionsDispo = getActionsRestantes($idTierlist); //On récupère toutes les actions que le brouillon n'a pas déjà 

$categories = getCategories();

//Comme pour la vue detail_tierlist, on fait un tableau de tiers pour pouvoir écrire les lettres et un tableau de couleur pour correspondre et que ce soit mieux designer, comme sur le mockup
$tiers = ["S", "A", "B", "C", "D"];
$couleurs = ["S"=>"#e72925", "A"=>"#e47e01", "B"=>"#b69b02", "C"=>"#4aaf4f", "D"=>"#1b7bcf"];

?>

<div class="pageCreation">

    <h1 class="titreCreation">Création de Tierlist</h1>

    <!--Formulaire qui englobe toute la page pour qu'on puisse tout soumettre d'un coup-->
    <!--Les données du drag & drop sont liées avec les inputs hidden créent par synchroniserFormulaire() en JS-->
    <form action="controleur.php" method="GET">
        <input type="hidden" name="idTierlist" value="<?=$idTierlist?>">

        <!--Ligne du haut avec le titre, le type et les boutons sauvegarder (est_publique reste à 0) et partager (est_publique passe à 1)-->
        <div class="barreHaut">
            <input type="text" name="titre" id="titreTierlistInput" class="inputTitre" placeholder="Nom de la tierlist" value="<?=htmlspecialchars((string)$infosTierlist["titre"])?>" maxlength="150">
            <div class="inputType">
            <?php
            $tabCategories = array();
            $tabCategories[] = array("id" => "", "label" => "Catégorie (Toutes)");
            
            if ($categories != false) {
                foreach($categories as $cat) {
                    $tabCategories[] = array("id" => $cat['id_categorie'], "label" => $cat['nom_categorie']);
                }
            }
            mkSelect("categorie", $tabCategories, "id", "label", valider("categorie"));
            ?>
            </div>
            <div class="boutons">
                <button type="submit" name="action" value="SauvegarderCreation" class="btn btn-primary btnValider" onclick="synchroniserFormulaire()">Sauvegarder</button>
                <button type="submit" name="action" value="PublierTierlist" class="btn btn-primary btnValider" onclick="synchroniserFormulaire()">Partager</button>
            </div>
        </div>

        <!--Centre avec la tierlist à gauche et la bibliothèque à droite-->
        <div class="zoneCreation">
            <!--À gauche, la tierlist-->
            <div class="grilleTierlist" id="grilleTierlist">
                <?php 
                foreach($tiers as $tier){
                    echo "<div class=\"ligneTier\" id=\"ligne-$tier\">";
                    //On s'occupe des couleurs derrière les lettres
                    echo "<div class=\"lettreTier\" style=\"background-color:$couleurs[$tier];\">"  . $tier . "</div>";

                    //Zone de dépôt pour recevoir les vidéos (en vignettes) par drag & drop
                    //data-tier permet au JS de savoir dans quel tier on dépose, c'est utile pour le drag & drop
                    echo "<div class=\"zoneDepot\" id=\"zone-$tier\" data-tier=\"$tier\" ondragover=\"permettreSurvolLigne(event)\" ondrop=\"validerPlacement(event)\">";
                    if(!empty($contenuParTier[$tier])){
                        foreach($contenuParTier[$tier] as $vignette){
                            //On reprend le brouillon s'il n'est pas vide et on place les vignettes déjà mises 
                            $idAction = $vignette["id_action"];
                            $joueur = isset($vignette["joueur"]) ? htmlspecialchars($vignette["joueur"]) : '';
                            $competition = isset($vignette["competition"]) ? htmlspecialchars($vignette["competition"]) : '';
                            $categorie = isset($vignette["nom_categorie"]) ? htmlspecialchars($vignette["nom_categorie"]) : '';
                            $image = isset($vignette["url_image"]) ? htmlspecialchars($vignette["url_image"]) : '';
                            $media = isset($vignette["url_media"]) ? htmlspecialchars($vignette["url_media"]) : '';
                            //On utilise "data-" car c'est, selon nos recherches, la bonne pratique 
                            echo "<div class=\"vignetteCreation\" draggable=\"true\" id=\"vignette-" . $idAction . "\" data-id-action=\"" . $idAction . "\" 
                                data-joueur=\"" . $joueur . "\" data-competition=\"" . $competition . "\" data-categorie=\"" . $categorie . "\" data-image=\"" . $image . "\"  
                                data-media=\"" . $media . "\" ondragstart=\"commencerGlisser(event)\" onclick=\"ouvrirPopupVideo(this)\"></div>";
                        }
                    }
                    echo "</div>"; //fin de la div zoneDepot
                    echo "</div>"; //fin de la div ligneTier
                }
                ?>
            </div> <!--fin de la div grilleTierlist-->


            <!--À droite, la bibliothèque des actions disponibles-->
            <div class="bibliotheque" id="bibliotheque" ondragover="permettreSurvolLigne(event)" ondrop="validerPlacement(event)" data-tier="BIBLIO">
                <p class="titreBibliotheque">Actions disponibles</p>
                <?php 
                if(!empty($actionsDispo)){
                    foreach($actionsDispo as $action){
                        $idAction = $action["id_action"];
                        $joueur = isset($action["joueur"]) ? htmlspecialchars($action["joueur"]) : '';
                        $competition = isset($action["competition"]) ? htmlspecialchars($action["competition"]) : '';
                        $categorie = isset($action["nom_categorie"]) ? htmlspecialchars($action["nom_categorie"]) : '';
                        $image = isset($action["url_image"]) ? htmlspecialchars($action["url_image"]) : '';
                        $media = isset($action["url_media"]) ? htmlspecialchars($action["url_media"]) : '';
                        echo "<div class=\"vignetteCreation vignetteBiblio\" draggable=\"true\" id=\"vignette-" . $idAction . "\" data-id-action=\"" . $idAction . "\" 
                            data-joueur=\"" . $joueur . "\" data-competition=\"" . $competition . "\" data-categorie=\"" . $categorie . "\" data-image=\"" . $image . "\" 
                            data-media=\"" . $media . "\" ondragstart=\"commencerGlisser(event)\" onclick=\"ouvrirPopupVideo(this)\">";
                        if(!empty($action["url_media"])){
                            //Vidéo de l'action (mp4 stocké localement dans notre répertoire /ressources)
                            //Grâce à autoplay, muted et loop, la vidéo tourne en boucle silencieusement
                            //preload=\"metadata\" est utile pour indiquer au navigateur de télécharger uniquement les informations de base pour l'économie de la page (pour éviter des bugs)
                            echo "<video class=\"mediaVignette\" src=\"" . htmlspecialchars($action["url_media"]) . "\" autoplay muted loop preload=\"metadata\">";
                            
                            //Il faut traiter le cas si le navigateur ne supporte pas la vidéo car sinon ça va planter, donc on affiche l'image
                            if(!empty($action["url_image"])){
                                echo "<img src=\"" . htmlspecialchars($action["url_image"]) . "\" alt=\"" . htmlspecialchars($action["joueur"]) . "\">";
                            }
                            
                            echo "</video>";
                        }
                        elseif(!empty($action["url_image"])){
                            //On traite aussi le cas s'il n'y a pas de vidéo mais une image
                            echo "<img class=\"mediaVignette\" src=\"" . htmlspecialchars($action["url_image"]) . "\" alt=\"" . htmlspecialchars($action["joueur"]) . "\">";
                        }
                        else{
                            //Si rien n'est disponible, on met un placeholder, mais ce cas ne devrait pas arriver
                            echo "<div class=\"vignetteVide\"></div>";
                        }

                        echo "<p class=\"labelVignette\">" . htmlspecialchars($action["joueur"]) . "</p>";
                        echo "</div>"; //fin de la div vignetteCreation
                    }
                }
                else{
                    echo "<p class=\"pasActions\">Toutes les actions ont été placées !</p>"; //C'est le cas de quelqu'un qui mettrait toutes les actions dans sa tierlist (ça fait beaucoup mais on trouvait ça inutile d'empêcher ça)
                }
                ?>

            </div> <!--fin de la div bibliotheque-->
        </div><!--fin de la grande div zoneCreation qui gère le centre avec la partie gauche et la partie droite-->

        <!--Champs hidden générés par synchroniserFormulaire(), juste avant la soumission du formulaire, donc c'est en JS que l'on s'occupe de cette div-->
        <div id="champsHidden"></div>
    </form>

    <div id="popupVideo" class="popupPourVideo">
        <div class="contenuPopup">
            <p class="boutonFermer" onclick="fermerPopupVideo()">x</p>
            <div id="divPourVideo"></div>
            <div class="infosCulturelles">
                <h3 id="nomJoueur"></h3>
                <p>
                    <p id="souligne">Compétition :</p>
                    <p id="nomCompetition"></p>
                </p>
                <p>
                    <p id="souligne">Type d'action :</p>
                    <p id="nomCategorie"></p>
                </p>
            </div>
        </div>
    </div>

</div>


