

window.onload = function() {    
    let elements = document.getElementsByClassName("imok_modal-content");

    for(let i = 0; i < elements.length; i++) {
        elements[i].onclick = function () {

            alert("Clicked in an element of the class.");
        }
    }
};

/*
document.body.onclick = function(e) {   //when the document body is clicked
    if (window.event) {
        e = event.srcElement;           //assign the element clicked to e (IE 6-8)
    }
    else {
        e = e.target;                   //assign the element clicked to e
    }

    if (e.className && e.className.indexOf('imok_modal-content') != -1) {
        //if the element has a class name, and that is 'someclass' then...
        alert('hohoho');
    }
}
    */

/*
window.onload = function() {
    var anchors = document.getElementsByTagName('a');
    for(var i = 0; i < anchors.length; i++) {
        var anchor = anchors[i];
        if(/\bimok_modal-content\b/).match(anchor.className)) {
            anchor.onclick = function() {
                alert('ho ho ho');
            }
        }
    }
}
*/

/*

$(document).on("click", ".imok_menu", function() {
    var myTargetModal = $(this).attr("href");
    $("#myModal").hide();
    $(".imok_modal-content").hide();
    $("#myModal").fadeIn();
    $(myTargetModal).fadeIn();
    return false;
  });
  
  $("body").on("click", ".close", function() {
    $("#myModal").hide();
    $(".imok_modal-content").hide();
  });
*/
