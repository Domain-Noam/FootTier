<?php
session_start();

include_once("../librairies/maLibUtils.php");
include_once("../librairies/modele.php");

if (valider("connecte","SESSION")) 
if($idUser=valider("idUser", "SESSION"))
if($pseudo = valider("pseudo", "SESSION"))
if ($idTierlist = valider("idTierlist")) {
	$vlalescommentaires=getCommentairesTierlist($idTierlist);
	foreach($vlalescommentaires as $nextMessage){
		if($nextMessage["pseudo"]==$pseudo){
			echo "<div style=\"color:green;\">";
		}
		else{
			echo "<div style=\"color:blue;\">";
		}
		echo "[$nextMessage[pseudo]] "; 
		echo stripslashes($nextMessage["contenu"]); //On enlève les \ des messages grâce à cette fonction stripslashes() comme ça on remet ceux enlevr par addsashes mais c'est plus sécuriser que de ne pas faire de addslashes
		echo "</div>";
	}
?>
<form action="../controleur.php" method="GET" target="_parent"> <!--target="_parent" permet que le navigateur recharge la vue detail_tierlist (le parent) et pas le petit block de l'iframe-->
	<input type="hidden" name="idTierlist" value="<?=$idTierlist?>">	
	<input type="text" name="nvCommentaire" id="monCommentaire">
	<button type="submit" name="action" value="Envoyer">Envoyer</button>
</form>
<?php
}

?>




