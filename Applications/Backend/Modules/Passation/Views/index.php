 <h1><?php echo $title; ?></h1>
 
<div class="note">
Vous êtes le point de transférer vos pouvoirs de président à un autre membre, cette action est <strong><span style="color: red;">IRRÉVERSIBLE</span></strong>
et seul lui pourra attribuer ce groupe à une autre personne.
</div>

Si vous êtes sûr de vous, choisissez le futur président ainsi que votre nouveau grade :

<form action="" method="post">
<table>
<tr><td><label for="membre" id="label_membre">Membre (pseudo) :</label></td>
	<td><div id="autocompletion">
	<input id="membre" name="membre" type="text" autocomplete="off" />
    <div id="results"></div></div>
    </td>
</tr>
    
<tr><td><label for="groupe">Groupe :</label></td>
	<td><select name="groupe" id="groupe">
		<?php 
			foreach ($listeGroupe as $groupe)
			{
				if($groupe['id'] == 1)
					continue;
				echo '<option value="' . $groupe->id() . '">' . $groupe['nom'] . '</option>';
			}
		?>
	</select>
    </td>
</tr>
</table>
<input type="submit" />
</form>

<script>
(function() {

    var searchElement = document.getElementById('membre'),
        results = document.getElementById('results'),
        selectedResult = -1, // Permet de savoir quel résultat est sélectionné : -1 signifie « aucune sélection »
        previousRequest, // On stocke notre précédente requête dans cette variable
        previousValue = searchElement.value; // On fait de même avec la précédente valeur
        

    function getResults(keywords) { // Effectue une requête et récupère les résultats
      
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../search.php?s='+ encodeURIComponent(keywords));

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                
                displayResults(xhr.responseText);

            }
        };

        xhr.send(null);

        return xhr;

    }

    function displayResults(response) { // Affiche les résultats d'une requête
      
        results.style.display = response.length ? 'block' : 'none'; // On cache le conteneur si on n'a pas de résultats

        if (response.length) { // On ne modifie les résultats que si on en a obtenu

            response = response.split('|');
            var responseLen = response.length;

            results.innerHTML = ''; // On vide les résultats

            for (var i = 0, div ; i < responseLen ; i++) {

                div = results.appendChild(document.createElement('div'));
                div.innerHTML = response[i];
                
                div.onclick = function() {
                    chooseResult(this);
                };

            }

        }

    }

    function chooseResult(result) { // Choisit un des résultats d'une requête et gère tout ce qui y est attaché
      
        searchElement.value = previousValue = result.innerHTML; // On change le contenu du champ de recherche et on enregistre en tant que précédente valeur
        results.style.display = 'none'; // On cache les résultats
        result.className = ''; // On supprime l'effet de focus
        selectedResult = -1; // On remet la sélection à zéro
        searchElement.focus(); // Si le résultat a été choisi par le biais d'un clic, alors le focus est perdu, donc on le réattribue

    }



    searchElement.onkeyup = function(e) {
      
        e = e || window.event; // On n'oublie pas la compatibilité pour IE

        var divs = results.getElementsByTagName('div');

        if (e.keyCode == 38 && selectedResult > -1) { // Si la touche pressée est la flèche « haut »
          
            divs[selectedResult--].className = '';
            
            if (selectedResult > -1) { // Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
                divs[selectedResult].className = 'result_focus';
            }

        }

        else if (e.keyCode == 40 && selectedResult < divs.length - 1) { // Si la touche pressée est la flèche « bas »
          
            results.style.display = 'block'; // On affiche les résultats

            if (selectedResult > -1) { // Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
                divs[selectedResult].className = '';
            }

            divs[++selectedResult].className = 'result_focus';

        }

        else if (e.keyCode == 13 && selectedResult > -1) { // Si la touche pressée est « Entrée »
          
            chooseResult(divs[selectedResult]);

        }

        else if (searchElement.value != previousValue) { // Si le contenu du champ de recherche a changé

            previousValue = searchElement.value;

            if (previousRequest && previousRequest.readyState < 4) {
                previousRequest.abort(); // Si on a toujours une requête en cours, on l'arrête
            }

            previousRequest = getResults(previousValue); // On stocke la nouvelle requête

            selectedResult = -1; // On remet la sélection à zéro à chaque caractère écrit

        }

    };

})();
</script>