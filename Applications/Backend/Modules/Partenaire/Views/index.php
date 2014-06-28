<h1>Gestion des partenaires</h1>
<?php
 echo '<div style="margin: 10px"><a href="partenaire-ajouter"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter un partenaire</div></a></div>';
 ?>
 
<table>
  <tr><th>Nom</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
<?php

$i = 0;
foreach ($listePartenaire as $partenaire)
{
	$i++;
	$suppr = '<a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer ce partenaire ?\'));" href="partenaire-supprimer-'. $partenaire['id']. '" title="Supprimer le partenaire"><img src="../images/x-20.png" alt="Supprimer" /></a>';
			
	if($i%2)
		echo '<tr class="impaire">';
	else
		echo '<tr class="paire">';
	echo '<td class="cesure">' . $partenaire['nom'] . '</td>
	<td>'.$partenaire['dateAjout']->format('d/m/Y à H\hi'). '</td>
	<td>'.$partenaire['dateModif']->format('d/m/Y à H\hi'). '</td>
	<td class="centrer"><a href="partenaire-modifier-'. $partenaire['id']. '" title="Modifier le partenaire"><img src="../images/crayon-20.png" alt="Modifier" /></a>    ' . $suppr . '
	</td></tr>'. "\n";
}
?>
</table>
