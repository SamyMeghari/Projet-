//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener('load',initPage);

var currentUtilisateur = null;

function initPage(){ // initialise l'état de la page
  if (document.body.dataset.utilisateur){
    let utilisateur = JSON.parse(document.body.dataset.utilisateur);
    etatConnecte(utilisateur);
  }
  else etatDeconnecte();
}

function etatDeconnecte() { // passe dans l'état 'déconnecté'
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=false;
    // nettoie la partie personnalisée :
    currentUtilisateur = null;
    delete(document.body.dataset.personne);
}

function etatConnecte(utilisateur) { // passe dans l'état 'connecté'
    currentUtilisateur = utilisateur;
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=false;
}

//on rajoute la fonction processAnswer ici puisqu'elle est commune à tous les scripts js

function processAnswer(answer){
  if (answer.status == "ok")
    return answer.result;
  else
    throw new Error(answer.message);
}
