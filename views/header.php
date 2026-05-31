<?php
//si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>	
	<meta charset="utf-8" />
	<title>FootTier</title>

	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
	
	<link href="css/style.css?v=4"<?php echo filemtime('css/style.css'); ?>" rel="stylesheet" />

	<script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/interaction.js"></script>
</head>

<body>
<div id="enveloppe">
  
  <div class="container">
    
    <?php
    //vérification de la session via la fonction valider
    if (!valider("connecte", "SESSION")) {
    ?>
        <div class="pilule-entete entete-centre">
            <a href="index.php" class="section-marque">
                <img src="ressources/img/logo.png" alt="Logo" />
                <h1>FootTier</h1>
            </a>
        </div>
    <?php
    } else {
    ?>
        <div class="pilule-entete entete-espace" style="position: relative;">
            <a href="index.php?view=galerie" class="section-marque">
                <img src="ressources/img/logo.png" alt="Logo" />
                <h1>FootTier</h1>
            </a>

            <div class="boutons-nav">
                <a href="index.php?view=creation" class="bouton-nav">Créer une tierlist</a>
                <a href="index.php?view=galerie" class="bouton-nav">Galerie de la<br>communauté</a>
                <a href="index.php?view=brouillon" class="bouton-nav">Mes<br>Brouillons</a>
                
                <?php 
                if (valider("admin", "SESSION") == 1) { 
                ?>
                    <a href="index.php?view=admin" class="bouton-nav">Administration</a>
                <?php } ?>
            </div>

            <div>
                <?php $pseudo = htmlspecialchars($_SESSION["pseudo"]); ?>
                <img src="ressources/img/user_icon.png" alt="Profil" class="icone-utilisateur" onclick="document.getElementById('popupUtilisateur').style.display = document.getElementById('popupUtilisateur').style.display === 'none' ? 'block' : 'none';">
                
                <div id="popupUtilisateur">
                    <p class="pseudo-popup"><?php echo $pseudo; ?></p>
                    <a href="controleur.php?action=Logout" class="btn btn-sm btn-danger w-100">Déconnexion</a>
                </div>
            </div>

        </div>
    <?php
    }
    ?>

    <div class="contenu-principal">