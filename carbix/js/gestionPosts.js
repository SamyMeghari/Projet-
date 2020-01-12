//Auteurs : Happe Josué et Boumahdi Samy
window.addEventListener('load',findMesPosts);

function findMesPosts(ev){
  ev.preventDefault();
  let url = "services/findMesPosts.php";
  fetchFromJson(url)
  .then(processAnswer)
  .then(affichePosts,displayErrorPosts);
}

function affichePosts(answer){
  if (answer.liste[0]==null){
    let p = document.createElement('p');
    p.textContent = "Aucun post n'a été fait";
    let section = document.querySelector("section#posts");
    section.textContent='';
    section.appendChild(p);
  }
  else{
    let section = document.querySelector("div#section_posts");
    let fragment = document.createDocumentFragment();
    for (let post of answer.liste){
      let div = document.createElement('div');
      div.id='post';
      let p = document.createElement('p');
      p.textContent = post.contenu;
      div.innerHTML="<h3>Publié le "+post.date_creation+", sur la station d'id "+post.station+"<h3>\n";
      div.innerHTML+="<h4>Titre : "+post.titre+"</h4>\n";
      div.appendChild(p);
      div.innerHTML += "Likes : "+post.nblike+" Nolikes : "+post.nbnolike+"<br/><br/>";
      div.innerHTML += "<button class=\"delete_posts\" id="+post.id+">Effacer ce post</button><br/>";
      fragment.appendChild(div);
    }
    section.appendChild(fragment);
    let buttons = document.querySelectorAll("button.delete_posts")
    for (let button of buttons){
     button.addEventListener('click',effacePost);
   }
  }
}

function displayErrorPosts(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let elt = document.querySelector('section#posts>div#message');
  elt.textContent='';
  elt.appendChild(p);
}

function effacePost(ev){
  ev.preventDefault();
  let args = new FormData();
  args.append('id',this.id);
  fetchFromJson("services/deletePost.php",{method:"post",body:args,credentials:'same-origin'})
  .then(processAnswer)
  .then(refreshPosts,displayErrorPosts)
}

function refreshPosts(answer){
  let p = document.createElement('p');
  p.innerHTML = "Le post a bien été effacé.";
  let elt = document.querySelector('section#posts>div#message');
  elt.textContent='';
  elt.appendChild(p);
  let div = document.getElementById(answer);
  div.parentNode.parentNode.removeChild(div.parentNode);
  /*fetchFromJson("services/findMesPosts.php")
  .then(processAnswer)
  .then(affichePosts,displayErrorPosts);*/
}
