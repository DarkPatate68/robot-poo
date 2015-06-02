function insertTag(startTag, endTag, textareaId, tagType) {
    var field  = document.getElementById(textareaId); 
    var scroll = field.scrollTop;
    field.focus();
        
    if (window.ActiveXObject) { // C'est IE
        var textRange = document.selection.createRange();            
        var currentSelection = textRange.text;
                
        textRange.text = startTag + currentSelection + endTag;
        textRange.moveStart("character", -endTag.length - currentSelection.length);
        textRange.moveEnd("character", -endTag.length);
        textRange.select();     
    } else { // Ce n'est pas IE
        var startSelection   = field.value.substring(0, field.selectionStart);
        var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
        var endSelection     = field.value.substring(field.selectionEnd);
                
        field.value = startSelection + startTag + currentSelection + endTag + endSelection;
        field.focus();
        field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
    } 

    field.scrollTop = scroll; // et on redéfinit le scroll.
}

function insertText(text, textareaId) {
    var field  = document.getElementById(textareaId); 
    var scroll = field.scrollTop;
    field.focus();
        
    if (window.ActiveXObject) { // C'est IE
        var textRange = document.selection.createRange();            
        var currentSelection = textRange.text;
                
        textRange.text = text;
        //textRange.moveStart("character", -endTag.length - currentSelection.length);
        //textRange.moveEnd("character", -endTag.length);
        //textRange.select();     
    } else { // Ce n'est pas IE
        var startSelection   = field.value.substring(0, field.selectionStart);
        var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
        var endSelection     = field.value.substring(field.selectionEnd);
                
        field.value = startSelection + text + endSelection;
        field.focus();
        field.setSelectionRange(startSelection.length + text.length, startSelection.length + text.length);
    } 

    field.scrollTop = scroll; // et on redéfinit le scroll.
}

function escapeHtml(text) {
	  var map = {
	    '&': '&amp;',
	    '<': '&lt;',
	    '>': '&gt;',
	    '"': '&quot;',
	    "'": '&#039;'
	  };

	  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
	}

/*(function() {
    var pre = document.getElementsByTagName('pre'),
        pl = pre.length;
    for (var i = 0; i < pl; i++) {
        pre[i].innerHTML = '<span class="line-number"></span>' + pre[i].innerHTML + '<span class="cl"></span>';
        var num = pre[i].innerHTML.split(/\n/).length;
        for (var j = 0; j < num; j++) {
            var line_num = pre[i].getElementsByTagName('span')[0];
            line_num.innerHTML += '<span>' + (j + 1) + '</span>';
        }
    }
})();*/

function recupererSelection(idSource, idCible){
	var field  = document.getElementById(idSource);
	var fieldCible  = document.getElementById(idCible);
	fieldCible.value = "";
	var selection = "";
	
	 
	if (window.ActiveXObject) { // C'est IE
	                var textRange = document.selection.createRange();           
	                var currentSelection = textRange.text;
	                 
	                textRange.text =  currentSelection ;
	                selection = textRange.text;
	        } else { // Ce n'est pas IE
	                var startSelection   = field.value.substring(0, field.selectionStart);
	                var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
	                var endSelection     = field.value.substring(field.selectionEnd);
	                 
	                field.value = startSelection + currentSelection + endSelection;
	                selection = currentSelection;
	        }
	fieldCible.value = selection;	
	}

function bdoCitation(champ, vider){
	//id="citation-auteur-bdo"
	//id="citation-bdo"
	
	 var auteur  = document.getElementById("citation-auteur-bdo");
	 var texte  = document.getElementById("citation-bdo");
	 var champSource  = document.getElementById(champ);
	 var regex = new RegExp('\n\n', 'gim');
	 var regex2 = new RegExp('(.)\n\n$', 'gim'); //fini par un retour à la ligne
	 
	 if(vider || !texte.value){
		 // On veut simplement vider les champs
		//Remise à zéro du texte de la fenêtre
		 auteur.value = "";
		 texte.value = "";
		 //champSource.focus();
		 return;
	 }
	
	 var citation = "\n>";
	 citation += texte.value.replace(regex, "\n\n>");
	 citation = citation.replace(regex2, "$1>\n");
	 if(auteur.value)
		 citation += "\nSource: " + auteur.value;
	 citation += "\n\n";
	 
	 insertText(citation, champ);
	 
	 //Remise à zéro du texte de la fenêtre
	 auteur.value = "";
	 texte.value = "";
	 //champSource.focus();
}

function bdoLien(champ, vider){
	//id="citation-auteur-bdo"
	//id="citation-bdo"
	
	 var lien  = document.getElementById("lien-lien-bdo");
	 var texte  = document.getElementById("lien-texte-bdo");
	 var champSource  = document.getElementById(champ);
	 
	 if(vider || !texte.value){
		 // On veut simplement vider les champs
		//Remise à zéro du texte de la fenêtre
		 lien.value = "";
		 texte.value = "";
		 //champSource.focus();
		 return;
	 }
	
	 var txtLien = "[";
	 txtLien += texte.value + "](";
	 txtLien += lien.value + ")";
	 
	 insertText(txtLien, champ);
	 
	 //Remise à zéro du texte de la fenêtre
	 lien.value = "";
	 texte.value = "";
	 //champSource.focus();
}

function bdoImageLambda(champ, vider){
	//id="citation-auteur-bdo"
	//id="citation-bdo"
	
	 var lien  = document.getElementById("image-image-bdo");
	 var texte  = document.getElementById("image-alt-bdo");
	 var champSource  = document.getElementById(champ);
	 
	 if(vider || !texte.value){
		 // On veut simplement vider les champs
		//Remise à zéro du texte de la fenêtre
		 lien.value = "";
		 texte.value = "";
		 //champSource.focus();
		 return;
	 }
	
	 var txtLien = "![";
	 txtLien += texte.value + "](";
	 txtLien += lien.value + ")";
	 
	 insertText(txtLien, champ);
	 
	 //Remise à zéro du texte de la fenêtre
	 lien.value = "";
	 texte.value = "";
	 document.getElementById('overlay-image').style.display='none';
	 //champSource.focus();
}

function afficherCacher(idDiv)
{
    if(document.getElementById(idDiv).style.display=="none")
    {
        document.getElementById(idDiv).style.display="inline-block";
    }
    else
    {
        document.getElementById(idDiv).style.display="none";
    }
}

function cacher(idDiv)
{
	document.getElementById(idDiv).style.display='none';
}

function afficherBlock(idDiv)
{
	document.getElementById(idDiv).style.display='block';
}


function uploadEnd(error, markdown, idTextEdit) 
{
	
    if (error === 'OK') 
    {
        //Vider le formulaire (y compris le champs fichier)
        //Fermer la fenêtre modale
        document.getElementById('status-upload-image').innerHTML = 'Fichier reçu !';
        document.getElementById('overlay-image').style.display='none';
        insertText(markdown, idTextEdit);
        
        document.getElementById('image').value = "";
        document.getElementById('alt').value = "";
        document.getElementById('hauteur').value = "";
        document.getElementById('largeur').value = "";
        document.getElementById('status-upload-image').innerHTML = 'En attente d\'un fichier.';
    } 
    else 
    {
        document.getElementById('status-upload-image').innerHTML = error;
    }
}

function changerCaptcha()
{
	document.getElementById('image-captcha').src = "captcha.php";
}