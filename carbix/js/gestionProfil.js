//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener('load',findInfos);
window.addEventListener('load',initModif);

function findInfos(ev){
  ev.preventDefault();
  let url = 'services/findUtilisateur.php?pseudo='+currentUtilisateur;
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayInfoProfil,displayErrorProfil);
}

function displayErrorProfil(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#profil>div#infoProfil');
  elt.textContent='';
  elt.appendChild(p);
}

function displayInfoProfil(profilInfo){
  let titre = document.querySelector('section#profil>h2#pseudo');
  titre.textContent = profilInfo.pseudo;
  let infos = document.querySelector('section#profil>div#infoProfil');
  let mail;
  let ville;
  let description;
  if (profilInfo.mail===null) mail = "Pas de mail défini !";
  else mail = profilInfo.mail;
  if (profilInfo.ville===null) ville = "Pas de ville définie !";
  else ville = profilInfo.ville;
  if (profilInfo.description===null) description = "Pas de description définie !";
  else description = profilInfo.description;
  let liste = "<ul>\n";
  liste += "<li>Mail : "+mail+"</li>\n";
  liste += "<li>Ville : "+ville+"</li>\n";
  liste += "<li>Description : "+description+"</li>\n";
  liste += "<li>Nombre de posts : "+profilInfo.nbposts+"</li>\n";
  liste += "<li>Nombre total de likes : "+profilInfo.nblike+"</li>\n";
  liste += "<li>Nombre total de nolikes : "+profilInfo.nbnolike+"</li>\n";
  liste += "<li>Nombre total de notes données : "+profilInfo.nbavis+"</li>\n";
  liste += "<li>Somme de toutes le notes globales données : "+profilInfo.total+"</li>\n";
  liste += "</ul>\n";
  infos.innerHTML=liste;
}

function initModif(){
  document.forms.form_profil.addEventListener('submit',modifProfil);
}

function modifProfil(ev){
  ev.preventDefault();
  let args = new FormData(this);
  fetchFromJson("services/updateProfil.php",{method:"post",body:args,credentials:"same-origin"})
  .then(processAnswer)
  .then(refreshInfos,displayErrorUpdate);
}

function refreshInfos(answer){
  let p = document.createElement('p');
  p.innerHTML = "Les informations ont bien été modifiées.";
  let elt = document.querySelector('section#custom_profil>div#error');
  elt.textContent='';
  elt.appendChild(p);
  fetchFromJson('services/findUtilisateur.php?pseudo='+currentUtilisateur)
  .then(processAnswer)
  .then(displayInfoProfil,displayErrorProfil);
}

function displayErrorUpdate(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#custom_profil>div#error');
  elt.textContent='';
  elt.appendChild(p);
}
