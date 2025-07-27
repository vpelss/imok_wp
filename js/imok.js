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

imokalarmInterval = '';

function imok_alarm_on(){
  imokalarmInterval = setInterval( imok_alarm_me , 5000 * 1 ); //update every 15 seconds
}

function imok_alarm_off(){
  clearInterval(imokalarmInterval);
}

function imok_alarm_me(){
  let imok_alarm = document.getElementById('imok_alarm');
  imok_alarm.play();
  }

  function imok_all_good(){
    let imok_all_good = document.getElementById('imok_all_good');
    imok_all_good.play();
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