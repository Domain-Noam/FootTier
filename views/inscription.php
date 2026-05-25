<?php

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=connexion");
	die("");
}

?>
<div class="centre">
	<h1>Inscription</h1>

 <form role="form" action="controleur.php">
  <div class="formPseudo">
    <label for="pseudo">Pseudo</label>
    <input type="text" class="info" id="pseudo" name="Pseudo" placeholder="NOM D'UTILISATEUR" value="" >
  </div>
  <div class="formPseudo">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="info" id="mdp" name="passe" placeholder="MOT DE PASSE" value="">
  </div>
  <button type="submit" name="action" value="Inscription" class="bouton">Inscription</button>
</form>

</div>