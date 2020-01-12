//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener("load",initState);

function initState(){
  document.forms.form_login.addEventListener('submit',connectUser);
  document.forms.form_create.addEventListener('submit',createUser);
  document.querySelector('#logout').addEventListener('click',deconnectUser);
}

function connectUser(ev){
  ev.preventDefault();
  let args = new FormData(this);
  fetchFromJson("services/login.php",{method:'post',body:args,credentials:'same-origin'})
  .then(processAnswer)
  .then(etatConnecte,displayErrorLogin);
}

function createUser(ev){
  ev.preventDefault();
  let args = new FormData(this);
  fetchFromJson("services/createUtilisateur.php",{method:'post',body:args,credentials:'same-origin'})
  .then(processAnswer)
  .then(etatConnecte,displayErrorCreate);
}

function deconnectUser(ev){
  ev.preventDefault();
  fetchFromJson("services/logout.php",{credentials:'same-origin'})
  .then(processAnswer)
  .then(etatDeconnecte);
}

function displayErrorLogin(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#connexion>div.error');
  elt.textContent='';
  elt.appendChild(p);
}

function displayErrorCreate(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#inscription>div.error');
  elt.textContent='';
  elt.appendChild(p);
}
