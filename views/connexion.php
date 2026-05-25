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
<div class="page-header">
	<h1>Connexion</h1>
</div>

<p class="lead">

 <form role="form" action="controleur.php">
  <div class="form-group">
    <label for="pseudo">Pseudo</label>
    <input type="text" class="form-control" id="pseudo" name="Pseudo" value="<?php echo $pseudo;?>" >
  </div>
  <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="form-control" id="mdp" name="passe" value="<?php echo $passe;?>">
  </div>
  <button type="submit" name="action" value="Connexion" class="btn btn-default">Connexion</button>
</form>
<a href="index.php?view=inscription" class="nv_compte">Nouveau compte ?</a>
</p>

</div>


