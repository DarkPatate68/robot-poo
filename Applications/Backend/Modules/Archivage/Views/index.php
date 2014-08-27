 <h1><?php echo $title; ?></h1>
 
 <h2>Liste de toutes les années</h2>
<table>
	<tr><th></th><th>Année</th><th>Date de début</th></tr>
	<?php
		$i=1; 
		foreach($listeAnnees as $annee)
		{
			echo '<tr><td>' . $i . '</td><td>' . $annee['anneeScolaire'] . '</td><td>' . $annee['timeMySql'] . '</td></tr>';
			$i++;
		}
	?>
</table>

<h2>Effectuer un archivage</h2>

<div class="note_attention">
	Attention, cette action entrainne le changement du site en passant de l'année scolaire <strong><?php echo $courante; ?></strong> à l'année <strong><?php echo $suivante; ?></strong>. Cette
	action est <strong><span style="color: red;">IRRÉVERSIBLE</span></strong>. Toutes les futures news du site seront publiées sous cette année la.
</div>

Si vous êtes sûr de vous, appuyez ici :
<form action="" method="post">
<div>
	<label for="confiramtion">Je confirme l'archivage </label><input type="checkbox" id="confirmation" name="confirmation"/><br/>
	<input type="submit" value="ARCHIVER" />
</div>
</form>
