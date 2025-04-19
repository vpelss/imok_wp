// https://codepen.io/paulobrien/pen/paNEZW
// https://stackoverflow.com/questions/4588759/how-do-you-set-a-javascript-onclick-event-to-a-class-with-css

function imok_menu_openNav() {
  document.getElementById('imok_menu_myNav').style.display = 'block';
}

function imok_menu_closeNav() {
  document.getElementById('imok_menu_myNav').style.display = 'none';
  document.getElementById('imok_spinner').style.display = 'none';
}

function imok_menu_spinner() {
  document.getElementById('imok_spinner').style.display = 'block';
  document.getElementById('imok_spinner_overlay').style.display = 'block';
  document.getElementById('imok_menu_myNav').style.display = 'none';
}

//spinner on all A link clicks
document.onclick = function(event) {
  event = event;
  var target = event.target;
  if(target.nodeName === 'A') {
      if(target.className.indexOf("no_spinner") == -1){ //except ones with class no_spinner
      imok_menu_spinner();
    }
  }

};