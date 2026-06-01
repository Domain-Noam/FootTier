<?php

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}

include_once "librairies/maLibForms.php";

securiser('index.php?view=connexion');

if (!valider("admin", "SESSION")) {
	header("Location:index.php?view=connexion&msg=" . urlencode("Accès refusé ! Vous devez être administrateur"));
	die("");
}

$utilisateurs = getUtilisateurs();
$categories = getCategories();
$actions = getActions();
?>

<h1 class="titreAdmin">Page d'Administration</h1>

<div class="DivsAdmin">
    <h3 class="titreDivs">Liste des Utilisateurs</h3>
    
    <div class="table-responsive"> <!--nom de class Boostrap-->
        <table class="table table-striped table-hover table-admin"> <!--nom de class Boostrap-->
            <thead>
                <tr class="tr-entete">
                    <th>ID User</th>
                    <th>Pseudo</th>
                    <th>Date d'inscription</th>
                    <th>Rôle</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(is_array($utilisateurs) && !empty($utilisateurs)){
                    foreach($utilisateurs as $utilisateur){
                        $idUser = $utilisateur['id_user'];
                        $pseudoUser = htmlspecialchars($utilisateur['pseudo']);
                        $dateInscription = htmlspecialchars($utilisateur['date_inscription']);
                        $estAdmin = $utilisateur['est_admin'];
                        ?>
                        <tr>
                            <td class="align-middle"><?php echo $idUser;?></td>
                            <td class="align-middle"><?php echo $pseudoUser;?></td>
                            <td class="align-middle"><?php echo $dateInscription;?></td>
                            <td class="align-middle">
                                <?php 
                                if($estAdmin == 1){ 
                                    echo "<p class=\"label label-danger\">Administrateur</p>";
                                }
                                else{ 
                                    echo "<p class=\"label label-primary\">Membre</p>";
                                }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php 
                                if($estAdmin == 0){
                                    mkLien("controleur.php", "Promouvoir Admin", "action=Promouvoir&id_user=$idUser", "class=\"btn btn-xs btn-success btn-arrondi\"");
                                }
                                else{
                                    mkLien("controleur.php", "Rétrograder", "action=Demettre&id_user=$idUser", "class=\"btn btn-xs btn-warning btn-arrondi\"");
                                } 
                                mkLien("controleur.php", "Bannir", "action=Bannir&id_user=$idUser", "class=\"btn btn-xs btn-danger btn-arrondi\" onclick=\"return confirm('Bannir ce membre ?');\""); 
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
                else{
                    echo '<tr><td colspan="5" class="text-center text-muted">Aucun utilisateur trouvé</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="DivsAdmin">
    <h3 class="titreDivs">Ajouter une action</h3>
    
    <div class="fond-blanc-form">
        <?php mkForm("controleur.php");?>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="titre-principal">Joueur :</label>
                    <?php mkInput("text", "joueur", "", "class=\"form-control\" placeholder=\"Ex: Kylian Mbappe\" required");?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="titre-principal">Compétition :</label>
                    <?php mkInput("text", "competition", "", "class=\"form-control\" placeholder=\"Ex: Russie 2018\" required");?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="titre-principal">Catégorie :</label>
                    <?php
                    $tabCategories = array(array("id" => "", "label" => "Sélectionner une catégorie"));
                    if(is_array($categories)){
                        foreach($categories as $cat){
                            $tabCategories[] = array("id" => $cat['id_categorie'], "label" => htmlspecialchars($cat['nom_categorie']));
                        }
                    }
                    mkSelect("id_categorie", $tabCategories, "id", "label", false);
                    ?>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="titre-principal">Sélectionner une image :</label>
                    <?php
                    $dossierImages = "ressources/img";
                    $tabImages = array(array("chemin" => "", "nom" => "Choisir un fichier image"));
                    foreach(scandir($dossierImages) as $fichier){
                        //Il faut ignorer les points "." et ".." qui ne sont pas des vrais fichiers
                        if ($fichier !== "." && $fichier !== "..") {
                            $tabImages[] = ["chemin" => $dossierImages . '/' . $fichier, "nom" => $fichier];
                        }
                    }
                    mkSelect("url_image", $tabImages, "chemin", "nom", false);
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="titre-principal">Sélectionner un média :</label>
                    <?php
                    $dossierMedias = "ressources/medias";
                    $tabMedias = array(array("chemin" => "", "nom" => "Choisir un fichier vidéo/gif"));
                    foreach(scandir($dossierMedias) as $fichier){
                        if($fichier !== "." && $fichier !== ".."){
                            $tabMedias[] = ["chemin" => $dossierMedias . '/' . $fichier, "nom" => $fichier];
                        }
                    }
                    mkSelect("url_media", $tabMedias, "chemin", "nom", false);
                    ?>
                </div>
            </div>
        </div>

        <div class="text-right" style="margin-top: 20px;">
            <?php mkInput("submit", "btn_ajouter", "Ajouter l'action", "class=\"btn btn-primary btnValider\"");?>
        </div>
        <?php endForm();?>
    </div>
</div>

<div class="DivsAdmin">
    <h3 class="titreDivs">Gérer les actions existantes</h3>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover table-admin">
            <thead>
                <tr class="tr-entete">
                    <th>Vignette</th>
                    <th>Joueur</th>
                    <th>Compétition</th>
                    <th>Type</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(is_array($actions) && !empty($actions)){
                    foreach($actions as $action){
                        $idAction = $action['id_action'];
                        $img = htmlspecialchars($action['url_image']);
                        $joueur = htmlspecialchars($action['joueur']);
                        $compet = htmlspecialchars($action['competition']);
                        $type = htmlspecialchars($action['nom_categorie']);
                        ?>
                        <tr>
                            <td class="align-middle">
                                <img src="<?php echo $img;?>" alt="vignette" class="imageTierlist" style="width: auto; height: 45px; margin-bottom: 0;">
                            </td>
                            <td class="align-middle"><strong><?php echo $joueur;?></strong></td>
                            <td class="align-middle"><?php echo $compet;?></td>
                            <td class="align-middle"><span class="label label-default" style="background-color: #79808d;"><?php echo $type; ?></span></td>
                            <td class="text-center align-middle">
                                <?php mkLien("controleur.php", "Supprimer", "action=SupprimerAction&id_action=$idAction", "class=\"btn btn-xs btn-danger btn-arrondi\" onclick=\"return confirm('Supprimer définitivement cette action ?');\""); ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else{
                    echo '<tr><td colspan="5" class="text-center text-muted" style="padding: 20px;">Aucune action enregistrée.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>