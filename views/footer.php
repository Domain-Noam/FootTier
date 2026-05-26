<?php
// Si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}
?>

    </div> <div style="height: 50px;"></div>

</div> <footer style="background-color: #dedede; border-top: 2px solid #b2bec3; padding: 30px 0; margin-top: auto;">
    <div class="container">
        <div class="row text-center">
            
            <div class="col-md-4">
                <h4 style="font-weight: bold; color: #2c166d;">Navigation</h4>
                <ul class="list-unstyled" style="color: #555;">
                    <li>Accueil</li>
                    <li>Contact</li>
                    <li>À propos du projet</li>
                    <li>Mentions Légales</li>
                </ul>
            </div>

            <div class="col-md-4">
                <img src="ressources/img/logo.png" alt="Logo FootTier" width="50" style="margin-bottom: 10px;" />
                <p style="font-weight: bold; margin: 0; color: #333;">&copy; 2026 FootTier - Tous droits réservés.</p>
            </div>

            <div class="col-md-4">
                <h4 style="font-weight: bold; color: #2c166d;">Projet</h4>
                <p style="color: #555;">
                    Projet étudiant - Hommage au patrimoine<br />
                    culturel de la Coupe du Monde de la FIFA.
                </p>
            </div>

        </div>
    </div>
</footer>

</body>
</html>
