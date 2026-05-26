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
	
	<link href="css/style.css" rel="stylesheet" />

	<script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/interaction.js"></script>
	
	<style>
        /* fond du site */
        body {
            background-color: #79808d; /* bleu-gris du fond */
            margin-top: 0; /* enlève la marge par défaut tout en haut */
            padding-top: 0;
        }
        
        /* la pilule grise claire */
        .pilule-entete {
            background-color: #dedede;
            border-radius: 50px;
            padding: 15px 30px;
            margin-top: 30px; /* Espace réduit entre le haut de l'écran et la pilule */
            margin-bottom: 30px;
            display: flex; /* Utilisation de Flexbox */
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* variantes de disposition Flexbox selon la vue connectée ou non */
        .entete-centre {
            justify-content: center;
        }
        .entete-espace {
            justify-content: space-between;
        }

        /* logo + titre */
        .section-marque {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .section-marque img {
            width: 40px;
            height: auto;
        }
        .section-marque h1 {
            margin: 0 0 0 10px;
            font-weight: bold;
            font-size: 32px;
            color: #2c166d; /* Violet foncé */
        }
        .section-marque:hover {
            text-decoration: none;
        }

        /* boutons de navigation */
        .boutons-nav {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .bouton-nav {
            border: 2px solid #2c166d;
            border-radius: 20px;
            padding: 8px 20px;
            color: #2c166d;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            background-color: transparent;
            transition: all 0.2s;
            line-height: 1.2;
        }
        .bouton-nav:hover, .bouton-nav:focus {
            background-color: #2c166d;
            color: #ffffff;
            text-decoration: none;
        }

        /* icône utilisateur à droite du footer */
        .icone-utilisateur {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #2c166d;
            cursor: pointer;
        }

        /* popup de déconnexion */
        #popupUtilisateur {
            display: none;
            position: absolute;
            right: 30px;
            top: 80px;
            background: white;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            z-index: 1000;
            min-width: 150px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

	</style>
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
                <img src="ressources/img/logo.png" alt="Logo" /> <!-- le logo de FootTier -->
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
                //vérification du statut admin pour afficher le bouton
                if (valider("admin", "SESSION") == 1) { 
                ?>
                    <a href="index.php?view=admin" class="bouton-nav">Administration</a>
                <?php } ?>
            </div>

            <div>
                <?php $pseudo = htmlspecialchars($_SESSION["pseudo"]); //protection XSS avec htmlspecialchars() ?>
                <img src="ressources/img/user_icon.png" alt="Profil" class="icone-utilisateur" onclick="document.getElementById('popupUtilisateur').style.display = document.getElementById('popupUtilisateur').style.display === 'none' ? 'block' : 'none';">
                
                <div id="popupUtilisateur">
                    <p style="font-weight: bold; color: #333; margin-bottom: 10px;"><?php echo $pseudo; ?></p>
                    <a href="controleur.php?action=Logout" class="btn btn-sm btn-danger w-100">Déconnexion</a> <!-- btn-danger : vient de Boostrap -->
                </div>
            </div>

        </div>
    <?php
    }
    ?>

    <div class="contenu-principal">
