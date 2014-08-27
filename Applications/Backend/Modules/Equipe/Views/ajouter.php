<h2><?php echo $title; ?></h2>

<div class="note">
	<ol>
	<li>Pour ajouter une personne à l'équipe qui ne possède pas de compte, veuilliez ne rien renseigner dans le champ <em>Membre (pseudo)</em>. Faites également commencer
	le début de la description par <strong>[[PRÉNOM@NOM]]</strong> (cractère alphabétique, ainsi que le tiret et l'espace uniquement – pour le nom et le prénom).</li>
	<li>La liste déroulante <em>Membre (pseudo)</em> ne propose que les membres (enregistrés) qui ne sont pas encore intégrés à l'équipe.</li>
	</ol>
</div>

<form method="post" action="" enctype="multipart/form-data">
<table>
	<tr><td><label for="annee">Année :</label></td>
	<td><?php if($action == 'ajouter membre')
	{
		?>
			<select id="annee" name="annee" readonly>
				<option><?php echo $annee ?></option>
			</select>
		<?php
	}
	else if($action == 'ajouter')
	{
		echo '<select id="annee" name="annee">';
		foreach ($listeAnnee as $annee)
			echo '<option>' . $annee . '</option>';
		echo '</select';
	}
	else
		$this->app->httpResponse()->redirect404();
	?>
	</td>
	<?php if($action == 'ajouter membre'){ ?>
		<td rowspan="6" style="vertical-align: top;">Liste des membres de cette équipe :<br>
		<ul>
			<?php 
				foreach ($listeMembre as $membre)
				{
					echo '<li>' . $membre . '</li>';
				}
			?>
		</ul>
	</td>
	<?php } ?>
	</tr>
	
	<tr><td><label for="membre" id="label_membre">Membre (pseudo) :</label></td>
	<td><div id="autocompletion">
	<input id="membre" name="membre" type="text" autocomplete="off" />
    <div id="results"></div></div>
    </td>
    </tr>
    
	<tr><td><label for="classe">Classe :</label></td><td><input type="text" id="classe" name="classe"/></td></tr>
	<tr><td><label for="fonction">Fonction :</label></td>
		<td>
			<select name="fonction" id="fonction">
				<option value="president">Président</option>
				<option value="vice-president">Vice-Président</option>
				<option value="tresorier">Trésorier</option>
				<option value="secretaire">Secrétaire</option>
				<option value="webmaster">Webmaster</option>
				<option value="membre">Membre</option>
			</select>
		</td>
	</tr>
	<tr><td><label for="description">Description :</label></td><td><textarea id="description" name="description" rows="10" cols="70"><?php if(isset($description)) echo $description;?></textarea></td></tr>
	<tr><td><label for="photo">Photo :</label></td><td><input type="file" name="photo" id="photo"/></td></tr>
</table>
	<div><input type="submit"/></div>
</form>

<script>
(function() {

    var searchElement = document.getElementById('membre'),
        results = document.getElementById('results'),
        selectedResult = -1, // Permet de savoir quel résultat est sélectionné : -1 signifie « aucune sélection »
        previousRequest, // On stocke notre précédente requête dans cette variable
        previousValue = searchElement.value, // On fait de même avec la précédente valeur
        archive = '<?php echo ($action == 'ajouter membre')?'&archive=' . str_ireplace('/', '-', (string) $annee):''; ?>';



    function getResults(keywords) { // Effectue une requête et récupère les résultats
      
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../search.php?s='+ encodeURIComponent(keywords) + archive);

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