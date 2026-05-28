window.onload = function(){ //On attend que TOUT le HTML soit chargé dans le navigateur avant de lancer le script JS
    var bouton=document.getElementById("bouton");
    var pseudoInput=document.getElementById("pseudo");
    var mdpInput=document.getElementById("mdp");

    function verifForm(){
        var pseudo=pseudoInput.value.trim(); //trim() permet de nettoyer les espaces, c'est-à-dire que si l'utilisateur entre "  Jose  " cela donne "Jose"
        var mdp=mdpInput.value.trim();

        if(!bouton || !pseudoInput || !mdpInput){
            return;
        }

        //Cas du pseudo
        if(pseudo.length>0){
            pseudoInput.style.borderColor="green";
        }
        else{
            //Si l'utilisateur efface son pseudo, on remet la bordure normale
            pseudoInput.style.borderColor = ""; 
        }

        //Cas du mot de passe
        if(mdp.length>=8){
            mdpInput.style.borderColor="green";
        }
        else{
            //Si le mot de passe repasse sous les 8 caractères, on retire le vert
            mdpInput.style.borderColor = ""; 
        }

        //Cas de l'activation du bouton
        if(pseudo.length > 0 && mdp.length >= 8){
            bouton.disabled = false; //On active le bouton car c'est bon
        } 
        else{
            bouton.disabled = true; //On bloque car ça ne va pas
        }
    }

    bouton.disabled=true;
    pseudoInput.addEventListener("input", verifForm);
    mdpInput.addEventListener("input", verifForm);

    verifForm(); //On lance une première vérification automatique
};


//Fonction pour passer le coeur en rouge quand l'utilisateur clique dessus
function coeurRouge(){
    var coeur = document.getElementById("coeur");
    var nbLikes = document.getElementById("nbLikes");
    var estUnLike = (coeur.textContent.trim() === "♥"); //true alors déjà liké, false alors pas encore liké

    if(estUnLike){
        //Si c'est déjà un like
        coeur.textContent = "♡";
        coeur.style.color = "rgba(255,255,255,0.7)";
        nbLikes.textContent = parseInt(nbLikes.textContent)-1;
    }
    else{
        //Si l'utilisateur veut ajouté le like
        coeur.textContent = "♥";
        coeur.style.color = "red";
        nbLikes.textContent = parseInt(nbLikes.textContent)+1;
    } //On ajoute +1 au chiffre affiché à côté

    document.getElementById("formLike").submit(); //On soumet le formulaire qui est lié aux likes pour envoyer au contrôleur pour modifier la BDD
}
