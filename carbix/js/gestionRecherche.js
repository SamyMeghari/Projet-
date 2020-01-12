//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener('load',initPage);

let map = L.map('cartecarburant');
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetmap</a> contributors'
}).addTo(map);

function initPage(){
  document.forms.form_recherche.addEventListener("submit",initCarte);
}

function initCarte(ev){
  ev.preventDefault();
  fetchFromJson("http://webtp.fil.univ-lille1.fr/~clerbout/carburant/recherche.php?"+formDataToQueryString(new FormData(this)))
  .then(processAnswerRecherche)
  .then(dessinerCarte,displayRechercheError);
}
function processAnswerRecherche(answer){
  if (answer.status == "ok")
    return answer;
  else
    throw new Error(answer.message);
}

function displayRechercheError(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#nbstations>div#error');
  elt.textContent='';
  elt.appendChild(p);
}

function dessinerCarte(answer){
    let p = document.createElement('p');
    if (answer.taille>20) p.innerHTML=answer.taille+" stations ont été trouvées. Seules les 20 plus proches sont affichées";
    else p.innerHTML = answer.taille+" stations ont été trouvées";
    let elt = document.querySelector('section#nbstations>div#resultat');
    elt.textContent='';
    elt.appendChild(p);
    //fetchFromJson("services/findStation.php?id="+answer.data.id)
    //.then(processAnswer)
    //.then(placerMarqueurs,displayRechercheError);
    //placerMarqueurs(map,answer.data);
    //map.on("popupopen",activerBouton);
}

function placerMarqueurs(answer) {
   let pointList= [];
   let nom = nb
   for (let i=0; i<nbStations.length; i++){
        let nom = nbStations[i].querySelector("td.adresse").textContent;
        let id = nbStations[i].dataset.num;
        let texte = nom + " <button value=\""+id+"\">Plus d'informations</button>";
        let point = [nbStations[i].dataset.lat, nbStations[i].dataset.lon];
        L.marker(point).addTo(map).bindPopup(texte);
        pointList.push(point);
   }
    map.fitBounds(pointList);
}

function activerBouton(ev) {
    let textePopup = ev.popup._contentNode;
    let bouton = textePopup.querySelector("button");
    bouton.addEventListener("click",affichePanel);
}
