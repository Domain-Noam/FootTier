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

//intercepte le clic et affiche une alerte de confirmation
function confirmerSuppression(event, msg) {
    if (!window.confirm(msg)) {
        event.preventDefault(); //annule le clic si l'utilisateur dit "Annuler"
        return false;
    }
    return true; //laisse passer le clic si l'utilisateur dit "OK"
}