<?php
//sécurité pour empêcher l'accès direct
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php");
	die("");
}

securiser('index.php?view=connexion');

$idUser = $_SESSION["idUser"];

$brouillons = getBrouillonsUtilisateur($idUser); //appel de la fonction pour récupérer les brouillons
?>


<h1 class="titreBrouillons">Mes brouillons</h1>


<div class="row">
    <?php
    if($brouillons != false){
        foreach($brouillons as $brouillon){
            $idTierlist = $brouillon['id_tierlist']; 
            $titre = htmlspecialchars($brouillon['titre']);
            $dateModif = htmlspecialchars($brouillon['date_modification']);
            ?>
            
            <div class="col-md-4">
                <div class="leBrouillon">
                    
                    <div class="tierlistEnPetit">
                        <div class="PetiteLigne">
                            <div class="labelS">S</div>
                            <div class="tierS" ></div>
                        </div>
                        <div class="PetiteLigne">
                            <div class="labelA">A</div>
                            <div class="tierA"></div>
                        </div>
                        <div class="PetiteLigne">
                            <div class="labelB">B</div>
                            <div class="tierB"></div>
                        </div>
                        <div class="PetiteLigne">
                            <div class="labelC">C</div>
                            <div class="tierC"></div>
                        </div>
                        <div class="PetiteLigne">
                            <div class="labelD">D</div>
                            <div class="tierD"></div>
                        </div>
                    </div>
                    
                    <div class="infosDuBrouillon">
                        <h3 class="titreDuBrouillon"><?php echo $titre;?></h3>
                        <p class="dateDuBrouillon">Modifié le : <br><?php echo $dateModif;?></p>

                        <?php mkForm("controleur.php");?>
                        <input type="hidden" name="idTierlist" value="<?=$idTierlist?>">
                        <button type="submit" name="action" value="ReprendreCreation" class="btn btn-primary btnBrouillon">Continuer la création</button>
                        <button type="submit" name="action" value="SupprimerBrouillon" class="btn btn-danger btnBrouillon" onclick="confirmerSuppression(event, 'Supprimer ce brouillon définitivement ?');">Supprimer</button>
                        <?php endForm();?>
                    </div>
                    
                </div>
            </div>
            
            <?php
        }
    }
    else{
        echo '<div class="col-md-12 text-center" style="color: #ffffff; font-size: 18px; margin-top: 50px;">Vous n\'avez aucun brouillon en cours</div>';
    }
    ?>
</div>