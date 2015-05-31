var button = document.getElementById("bdo-titre");
button.addEventListener('click', function(e) {
    afficherCacher('bdo-titre-menu');
}, false);
button.addEventListener('blur', function(e) {
    setTimeout(function(){
		document.getElementById('bdo-titre-menu').style.display="none";
	}, 200);
}, false);

var button = document.getElementById("bdo-code");
button.addEventListener('click', function(e) {
    afficherCacher('bdo-code-menu');
}, false);
button.addEventListener('blur', function(e) {
    setTimeout(function(){
		document.getElementById('bdo-code-menu').style.display="none";
	}, 200);
}, false);

/*document.getElementById('form-upload-image').addEventListener('submit', function() {
    document.getElementById('status-upload-image').innerHTML = 'Envoi en cours...';
}, true);*/

document.getElementById('checkDialogueImage-label').addEventListener('click', function() {
    afficherBlock('overlay-image');
}, true);

document.getElementById('annuler-image').addEventListener('click', function() {
    cacher('overlay-image');
}, true);