<?php
//si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}
?>

    </div> 
    <div style="height: 50px;"></div>

</div> 

<footer class="footer-perso">
    <div class="container">
        <div class="row text-center">
            
            <div class="col-md-4">
                <h4 class="titre-footer">Navigation</h4>
                <ul class="list-unstyled texte-footer">
                    <li>Accueil</li>
                    <li>Contact</li>
                    <li>À propos du projet</li>
                    <li>Mentions Légales</li>
                </ul>
            </div>

            <div class="col-md-4">
                <img src="ressources/img/logo.png" alt="Logo FootTier" class="logo-footer" />
                <p class="texte-gras-footer">&copy; 2026 FootTier - Tous droits réservés.</p>
            </div>

            <div class="col-md-4">
                <h4 class="titre-footer">Projet</h4>
                <p class="texte-footer">
                    Projet étudiant - Hommage au patrimoine<br />
                    culturel de la Coupe du Monde de la FIFA.
                </p>
            </div>

        </div>
    </div>
</footer>

</body>
</html>