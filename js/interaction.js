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



/*POUR LA VUE CRÉATION DE TIERLIST*/
//On utilise l'API native de HTML5 avec dragstart, dragover, drop
//Pour mieux comprendre la différence compliqué entre drop et dragend : drop s'occupe de la destination, tandis que dragend s'occupe de l'objet déplacé

var idActionEnCours = null; //C'est une variable globale pour stocker l'id de la vignette en cours de déplacement
var zoneOrigine     = null; //La zone depuis laquelle elle part

//Se déclenche quand l'utilisateur commence à faire glisser une vignette, on utilise dragend quand on relâche
function commencerGlisser(event){
    idActionEnCours = event.currentTarget.dataset.idAction; // On stocke l'id de l'action dans dataTransfer pour le récupérer au drop
    event.dataTransfer.setData("text/plain", idActionEnCours); //Capture l'id de l'action avec dataTransfer
    event.dataTransfer.effectAllowed = "move"; //Le curseur "déplacement"

    zoneOrigine = event.currentTarget.parentElement; //On mémorise la zone d'origine (la bibliothèque)

    event.currentTarget.style.opacity = "0.4"; //On rend la video presque transparente

    event.currentTarget.addEventListener("dragend", function(){
        this.style.opacity = "1"; //On remet la bonne opacité quand on lâche
    }, {
        once: true //L'écouteur se retire automatiquement après un appel
    }); 
}


//Se déclenche quand une vignette survolte la zone de dépôt valide, donc la tierlist
function permettreSurvolLigne(event){ //Lié à dragover pour quand on passe au-dessus de la zone
    event.preventDefault(); //Pour autoriser le drop sur la zone
    event.dataTransfer.dropEffect = "move";
    event.currentTarget.classList.add("survol"); //On met un contour sur la zone survolée pour avoir un visuel
}


//Se déclenche quand l'utilisateur lâche la vignette sur une zone de dépôt
function validerPlacement(event){ //En lien avec drop pour quand on lâche dans la zone
    event.preventDefault();
    event.currentTarget.classList.remove("survol"); //On retire l'effet visuel de survol mis dans la fonction permettreSurvolLigne

    var idAction = event.dataTransfer.getData("text/plain"); //On récupère l'id de l'action depuis dataTransfer
    if(!idAction){
        return; 
    }

    var vignette = document.getElementById("vignette-" + idAction); //On cherche la vignette dans le DOM pour que la vidéo soit mise en image dans la tierlist
    if(!vignette){
        return;
    }

    var zoneDest = event.currentTarget; //Zone de destination (la tierlist) 
    zoneDest.appendChild(vignette); //On place physiquement la vignette dans la tierlist

    idActionEnCours = null; //On réinitialise car on a fini le drag and drop sur la vidéo, donc on peut le refaire sur une autre
    zoneOrigine = null;
}


//Lit l'état actuel du DOM et génère les champs hidden correspondants dans le formulaire, juste avant la soumission
function synchroniserFormulaire(){ //Cette fonction est appelée via onclick sur les boutons Sauvegarder et Partager
    var conteneur = document.getElementById("champsHidden"); 
    conteneur.innerHTML = ""; //On vide les anciens champs hidden pour éviter les doublons

    var tiers = ["S", "A", "B", "C", "D"]; //On parcourt toutes les zones de dépôt possibles de la tierlist S/A/B/C/D
    tiers.forEach(function(tier){
        var zone = document.getElementById("zone-" + tier);
        if(!zone){
            return;
        }
    
        var vignettes = zone.querySelectorAll(".vignetteCreation"); //Pour chaque vignette présente dans la zone
        vignettes.forEach(function(v){
            var idAction = v.dataset.idAction;
            if(!idAction){
                return;
            }

            var input = document.createElement("input"); //On crée un input hidden : placements[idAction] = tier (pour donner l'information dans quel tier sera la vignette)
            input.type = "hidden";
            input.name = "placements[" + idAction + "]";
            input.value = tier;
            conteneur.appendChild(input);
        });
    });

    var biblio = document.getElementById("bibliotheque"); //On parcourt aussi la bibliothèque 
    if(biblio){
        var vignettesBiblio = biblio.querySelectorAll(".vignetteCreation");
        vignettesBiblio.forEach(function(v){
            var idAction = v.dataset.idAction;
            if(!idAction){
                return;
            }
            var input = document.createElement("input"); //On crée un input hidden : placements[idAction] = BIBLIO (pour dire que l'action est encore en bibliothèque)
            input.type  = "hidden";
            input.name  = "placements[" + idAction + "]";
            input.value = "BIBLIO";
            conteneur.appendChild(input);
        });
    }
}


document.addEventListener("DOMContentLoaded", function(){ //On retire le survol si on a survolé la zone de dépôt mais qu'on part sans déposer, il faut donc retirer la classe .survol
    console.log("Drag & Drop initialise : FootTier Création"); //On réinitialise car l'utilisateur a annulé son dépôt
 
    document.querySelectorAll(".zoneDepot, .bibliotheque").forEach(function(zone){
        zone.addEventListener("dragleave", function(event){ //On met dragleave sur toutes les zones 
             //dragleave : il se déclenche sur la zone de dépôt au moment exact où l'élément que l'utilisateur est en train de glisser quitte la zone sans y avoir été déposé 
            if(!zone.contains(event.relatedTarget)){
                zone.classList.remove("survol"); //dragleave se déclenche aussi en entrant dans une balise enfant 
            }
        });
    });
});




