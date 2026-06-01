<?php 

if(basename($_SERVER["PHP_SELF"]) != "index.php"){
	header("Location:../index.php?view=connexion");
	die("");
}

$idTierlist = valider("idTierlist", "GET");

if(!$idTierlist){
    header("Location: index.php?view=galerie");
    die("");
}

$nbCommentaires = getNbCommentaires($idTierlist);
$infosTierlist = getTierlistParId($idTierlist);
$contenuParTier = getContenuTierlist($idTierlist); //C'est un tableau groupé par tier

$tiers = ["S", "A", "B", "C", "D"];
//Au lieu d'aller chercher en CSS les couleurs, on peut directement les amener ici dans un tableau associatif
$couleurs = ["S"=>"#e72925", "A"=>"#e47e01", "B"=>"#b69b02", "C"=>"#4aaf4f", "D"=>"#1b7bcf"];

//On regard si l'utilisateur connecté a déjà liké 
$idUser = valider("idUser", "SESSION");
$dejaLike = ($idUser && aDejaLike($idUser, $idTierlist));

?>

<div class="grandeDiv">
    <h1>Vue détaillée d'une Tierlist</h1>

    <div class="sousDivGD">

        <div class="laTierlist"> <!--Div de gauche, la tierlist-->
            <h3>Auteur : <?=htmlspecialchars($infosTierlist["pseudo"])?></h3>

            <!--Affichage de S/A/B/C/D et les vignettes-->
            <div class="detailTierlist">
                <?php 
                foreach($tiers as $tier){
				    echo "<div class=\"ligne\">";
				    echo "<p class=\"lettre\" style=\"background-color: $couleurs[$tier];\">";
				    echo $tier . "</p>"; 

                    //On passe ensuite aux images, que nous avons appelées les vignettes
                        echo "<div class=\"vignettes\">";
					    if(!empty($contenuParTier[$tier])){ //Donc s'il y a des vignettes présentes pour une lettre 
						    foreach($contenuParTier[$tier] as $vignette){
							    echo "<div class=\"detailVignette\">";
							    if(!empty($vignette["url_image"])){ //Donc si il y a une url dans la BDD
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
                ?>
            </div>

            <!--Affichage de la tierlist terminé, donc on fait les likes avec le coeur et le titre, que nous appelons la légende-->
            <div class="legende">
				<!--Le formulaire pour le like-->
				<form action="controleur.php" method="GET" id="formLike">
					<input type="hidden" name="action" value="Like"> <!--Ici c'est complexe car on met en hidden mais pourtant on a name="action", en faite c'est le JS qui va soumettre le formulaire (voir la fonction coeurRouge()-->
					<input type="hidden" name="idTierlist" value="<?=$idTierlist?>">
					<button type="button" id="coeur" onclick="coeurRouge();" style="color: <?=$dejaLike ? "red" : "rgba(255,255,255,0.7)"?>;">
                        <?=$dejaLike ? '♥' : '♡'?>
                    </button>
				</form>
                <!--Le nombre de likes-->
				<p id="nbLikes">
                    <?=$infosTierlist["nb_likes"]?>
                </p>
                <!--Le titre-->
				<p class="titreTierlist">
                    <?=htmlspecialchars($infosTierlist["titre"])?>
                </p>
			</div>
        </div>

        <!--On passe à droite avec le iframe-->
        <div class="espaceCommentaire">
            <p class="nbCommentaires">Nombre de commentaire<?=$nbCommentaires > 1 ? 's' : '' ?> : <?=$nbCommentaires?></p>
            <iframe id="ifMessages" src="views/getMessages.php?idTierlist=<?=$idTierlist?>" frameborder="0"></iframe>
        </div>
    </div>
</div>


