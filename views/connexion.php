<?php

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=connexion");
	die("");
}

//Chargement eventuel des données en cookies
$pseudo = valider("pseudo", "COOKIE");
$passe = valider("mot_de_passe", "COOKIE"); 

?>
<div class="centre">
	<h1>Connexion</h1>

 <form role="form" action="controleur.php">
  <div class="formPseudo">
    <label for="pseudo">Pseudo</label>
    <input type="text" class="info" id="pseudo" name="Pseudo" placeholder="NOM D'UTILISATEUR" value="<?php echo $pseudo;?>" >
  </div>
  <div class="formPseudo">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="info" id="mdp" name="passe" placeholder="MOT DE PASSE" value="<?php echo $passe;?>">
  </div>
  <button type="submit" name="action" value="Connexion" class="bouton">CONNEXION</button>
</form>
<a href="index.php?view=inscription" class="nv_compte">Nouveau compte ?</a>

</div>


