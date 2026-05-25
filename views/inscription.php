<?php

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=connexion");
	die("");
}

?>
<div class="centre">
<br><br><br>
<div class="page-header">
	<h1>Inscription</h1>
</div>

<p class="lead">

 <form role="form" action="controleur.php">
  <div class="form-group">
    <label for="pseudo">Pseudo</label>
    <input type="text" class="form-control" id="pseudo" name="Pseudo" value="" >
  </div>
  <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="form-control" id="mdp" name="passe" value="">
  </div>
  <button type="submit" name="action" value="Inscription" class="btn btn-default">Inscription</button>
</form>

</p>

</div>