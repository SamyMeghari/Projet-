//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener('load',findBest);

function findBest(ev){
  ev.preventDefault();
  fetchFromJson("services/findBestStations.php")
  .then(processAnswer)
  .then(displayBestStations,displayError);
}

function displayError(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#best_stations>div#error');
  elt.textContent='';
  elt.appendChild(p);
}

function displayBestStations(answer){
  let section = document.querySelector("div#best_stations");
  let fragment = document.createDocumentFragment();
  for (let station of answer){
    let div = document.createElement('div');
    div.id='best';
    let nom;
    if (station.nom===null) nom = "Cette station n'a pas de nom";
    else nom = "Nom : "+station.nom;
    div.innerHTML = nom+" Marque : "+station.marque+" Adresse : "+station.adresse+"\n";
    div.innerHTML += "Nombre d'avis reçus : "+station.nbnotes+"\n";
    div.innerHTML += "Moyenne de la note globale : "+(station.noteglobale/station.nbnotes).toFixed(2)+"\n";
    div.innerHTML += "Moyenne de la note d'accueil : "+(station.noteaccueil/station.nbnotes).toFixed(2)+"\n";
    div.innerHTML += "Moyenne de la note du prix : "+(station.noteprix/station.nbnotes).toFixed(2)+"<br/>";
    div.innerHTML += "Moyenne de la note des services : "+(station.noteservice/station.nbnotes).toFixed(2)+"\n";
    fragment.appendChild(div);
  }
  section.appendChild(fragment);
}
