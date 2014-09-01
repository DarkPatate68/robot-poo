 <h1><?php echo $title; ?></h1>
 
 <div class="note">
 <ol>
 	<li>Le site considérera toujours le groupe <em>Invité</em> et <em>Membre</em> comme tel. C'est-à-dire que si vous leur donné tous les droits,
 	alors les nouveaux membres <strong>auront</strong> tous ces droits. Veuilliez donc garder une certaine cohérence avec ces deux groupes.</li>
 	<li>La classe <em>Président</em> possède <strong>tous</strong> les droits et ne peut-être changé. De plus, on ne peut attribuer ce groupe à un 
 	membre de façon classique. En effet, il ne peut y avoir <strong>qu'un seul et unique</strong> président sur le site. Le changement se fait par
 	passation dans l'espace membre du président actuel.</li>
 </ol>
 </div>
 
 <div><a style="cursor:  pointer;" onclick="redirection();"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter un groupe</div></a></div>
 
 
 <form action="" method="post" class="scrollable-table">
 
 <table class="table table-striped table-header-rotated">
 <thead>
 	<tr>
 		<th></th>
 		<th></th>
 		<?php 
 		foreach($listeChamp as $champ)
 		{
 			echo '<th class="rotate-45"><div><span>' . $champ .'</span></div></th>';
 		}
 		?>
 	</tr>
 </thead>
 <tbody>
 	<?php 
 		foreach($listeGroupe as $groupe)
 		{
 			echo '<tr><th class="row-header" id="header-' . $groupe->id() . '">' . $groupe->nom() . '</th>';
 			if($groupe->id() <= 2)
 				echo '<td></td>';
 			else
 				echo '<td style="white-space: nowrap;"><a onclick="modifier(' . $groupe->id() . ', \'' . $groupe->nom() . '\');" style="cursor:  pointer;"><img src="../images/crayon-20.png" alt="Modifier" /></a><a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer ce groupe ?\nTous les membres lui appartenant seront reclassés dans le groupe Membre.\'));" href="groupe-supprimer-' . $groupe->id() . '"><img src="../images/x-20.png" alt="Supprimer" /></a></td>';
 			
	 		foreach($listeChamp as $champ)
	 		{
	 			$checked = ($groupe['droits'][$champ])?'checked':'';
	 			$disabled = ($champ == 'president' || $groupe->id() == 1)?'disabled':'';
	 			echo '<td><input type="checkbox" name="' . $groupe->id() . '_' . $champ . '" ' . $checked . ' ' . $disabled . '/></td>';
	 		}
	 		echo '</tr>';
 		}
 	?>
</tbody> 	
 </table>
 
 <?php 
 	foreach ($listeGroupe as $groupe)
 	{
 		if($groupe->id() > 2)
 			echo '<input type="hidden" name="' . $groupe->id() . '_nom" id="' . $groupe->id() . '_nom" value="' . $groupe->nom() . '" />';
 	}
 ?>
 <input type="submit" />
 </form>
 
 <script>
 function modifier(id, nom)
 {
	 id = parseInt(id);
	 if(id <=2)
		 return;

	 var nouveauNom = prompt("Entrez le nouveau nom du groupe", nom);

	 document.getElementById(id.toString() + "_nom").value = escapeHtml(nouveauNom);
	 document.getElementById("header-" + id.toString()).innerHTML = escapeHtml(nouveauNom);

	 alert("Validez le formulaire pour effectuer les changements");
 }

 function redirection()
 {
	 var groupe = prompt('Nom du groupe');
	 if(groupe != null)
	 	document.location.href='groupe-ajouter-' + encodeURIComponent(groupe);
 }
 </script>
