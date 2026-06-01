<?php
session_start();

include_once("../librairies/maLibUtils.php");
include_once("../librairies/modele.php");

securiser('index.php?view=connexion');

$idTierlist = valider("idTierlist", "GET");
$connecte = valider("connecte", "SESSION");
$idUser = valider("idUser", "SESSION");
$pseudo = valider("pseudo", "SESSION");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../css/styleIframe.css">
</head>
<body>

<div class="tousLesCommentaires">

<?php

if($idTierlist && $idUser && $connecte){
	$commentaires=getCommentairesTierlist($idTierlist);
	if($commentaires){
		foreach($commentaires as $nextCommentaire){
			if($nextCommentaire["pseudo"]==$pseudo){
				echo "<div class=\"unCommentaire\" style=\"color:white;\">";
			}
			else{
				echo "<div class=\"unCommentaire\" style=\"color:rgba(16, 27, 58, 0.7);\">";
			}
			echo "<p class=\"auteur\">" . htmlspecialchars($nextCommentaire["pseudo"]) . "</p>"; 
			echo "<p class=\"leContenu\">" . stripslashes($nextCommentaire["contenu"]) . "</p>"; //On enlève les \ des messages grâce à cette fonction stripslashes() comme ça on remet ceux enlevr par addsashes mais c'est plus sécuriser que de ne pas faire de addslashes
			echo "</div>";
		}
	}
	else{
		echo "<p class=\"vide\">Aucun commentaire pour l'instant</p>";
	}
}
else{
	echo "<p class=\"vide\">Tierlist introuvable ou utilisateur non connecté</p>";
}
?>
</div>


<div class="envoi">
	<?php 
	if($connecte && $idUser && $idTierlist){
		echo "<form action=\"../controleur.php\" method=\"GET\" target=\"_parent\">"; //target="_parent" permet que le navigateur recharge la vue detail_tierlist (le parent) et pas le petit block de l'iframe
		echo "<input type=\"hidden\" name=\"idTierlist\" value=$idTierlist>";
		echo "<input type=\"text\" name=\"nvCommentaire\" id=\"monCommentaire\" placeholder=\"Ajoutez un commentaire\" maxlength=\"500\">";
		echo "<button type=\"submit\" name=\"action\" value=\"Envoyer\">Envoyer</button>";
		echo "</form>";
	}
	else{
        echo "<p class=\"vide\" style=\"padding:10px;\">Connectez-vous pour commenter</p>";
    }
	?>
</div>

</body>
</html>
