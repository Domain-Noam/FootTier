<?php 

if(basename($_SERVER["PHP_SELF"]) != "index.php"){
	header("Location:../index.php?view=connexion");
	die("");
}

$idTierlist = valider("idTierlist", "GET");
$nbCommentaires = getNbCommentaires($idTierlist);
$infosTierlist = getTierlistParId($idTierlist);

?>




<div class="detail">
    <h1>Vue détaillée d'une Tierlist</h1>

    <div class="laTierlist">
        <h3>Auteur</h3>
        <div>ICI IMAGE TIERLIST</div>
        <span id="coeur" onclick="coeurRouge(event);">♡</span><!-- Faire une fonction JS qui le passe en coeur rouge si enregistrerVoteLike($idTierlist, $idUser) renvoie true-->
        <p id="like"><?=$infosTierlist["nb_likes"]?></p> <!-- Faire fonction JS qui permet de faire +1 à $nbLikes si enregistrerVoteLike($idTierlist, $idUser) renvoie true-->
        <h4><?=$infosTierlist["titre"]?></h4>
    </div>

    <p id="com">Nombre de commentaires : <?=$nbCommentaires?></p>
    <iframe id="ifMessages" src="views/getMessages.php?idTierlist=<?=$idTierlist?>" frameborder="1"></iframe>
</div>


