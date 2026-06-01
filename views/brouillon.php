<?php
//sécurité : empêche l'accès direct
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php");
	die("");
}

securiser('index.php?view=connexion');

//récupération de l'ID utilisateur
$idUser = $_SESSION["idUser"];

//appel de la fonction pour récupérer les brouillons
$brouillons = getBrouillonsUtilisateur($idUser);
?>


<h1 class="titreBrouillon">Mes brouillons</h1>


<div class="row">
    <?php
    if ($brouillons != false) {
        foreach ($brouillons as $brouillon) {
            $idTierlist = $brouillon['id_tierlist']; 
            $titre = htmlspecialchars($brouillon['titre']);
            $dateModif = htmlspecialchars($brouillon['date_modification']);
            
            //liens d'action routés par le contrôleur
            $lienReprendre = "index.php?view=creation&idTierlist=" . $idTierlist;
            $lienSupprimer = "controleur.php?action=SupprimerTierlist&id_tierlist=" . $idTierlist;
            ?>
            
            <div class="col-md-4">
                <div class="carte-brouillon">
                    
                    <div class="miniature-tierlist">
                        <div class="mini-ligne"><div class="mini-label bg-tier-s">S</div><div class="bg-tier-s" style="flex:1; opacity:0.6; height:100%; margin-left:2px;"></div></div>
                        <div class="mini-ligne"><div class="mini-label bg-tier-a">A</div><div class="bg-tier-a" style="flex:1; opacity:0.6; height:100%; margin-left:2px;"></div></div>
                        <div class="mini-ligne"><div class="mini-label bg-tier-b">B</div><div class="bg-tier-b" style="flex:1; opacity:0.6; height:100%; margin-left:2px;"></div></div>
                        <div class="mini-ligne"><div class="mini-label bg-tier-c">C</div><div class="bg-tier-c" style="flex:1; opacity:0.6; height:100%; margin-left:2px;"></div></div>
                        <div class="mini-ligne"><div class="mini-label bg-tier-d">D</div><div class="bg-tier-d" style="flex:1; opacity:0.6; height:100%; margin-left:2px;"></div></div>
                    </div>
                    
                    <div class="infos-brouillon">
                        <h3 class="titre-brouillon"><?php echo $titre; ?></h3>
                        <p class="date-brouillon">Modifié le : <br><?php echo $dateModif; ?></p>
                        
                        <a href="<?php echo $lienReprendre; ?>" class="btn btn-primary btn-brouillon">
                            Continuer la création
                        </a>
                        
                        <a href="<?php echo $lienSupprimer; ?>" class="btn btn-danger btn-brouillon" onclick="confirmerSuppression(event, 'Supprimer ce brouillon définitivement ?');">
                            Supprimer
                        </a>
                    </div>
                    
                </div>
            </div>
            
            <?php
        }
    }
    else {
        echo '<div class="col-md-12 text-center" style="color: #ffffff; font-size: 18px; margin-top: 50px;">Vous n\'avez aucun brouillon en cours.</div>';
    }
    ?>
</div>