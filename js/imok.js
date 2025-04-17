// https://codepen.io/paulobrien/pen/paNEZW
// https://stackoverflow.com/questions/4588759/how-do-you-set-a-javascript-onclick-event-to-a-class-with-css

function imok_menu_openNav() {
  document.getElementById('imok_menu_myNav').style.display = 'block';
}

function imok_menu_closeNav() {
  document.getElementById('imok_menu_myNav').style.display = 'none';
}

function imok_menu_spinner() {
  document.getElementById('imok_spinner').style.display = 'block';
  document.getElementById('imok_spinner_overlay').style.display = 'block';
  document.getElementById('imok_menu_myNav').style.display = 'none';
}