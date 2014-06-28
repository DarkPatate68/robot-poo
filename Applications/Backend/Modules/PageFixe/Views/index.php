<h1>Gestion des pages non-archivables</h1>
<?php
 echo '<div style="margin: 10px"><a href="page-fixe-ajouter"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter une page</div></a></div>';
 ?>
 
<table>
  <tr><th>Id</th><th>Titre</th><th>URL</th><th>D<sup>ère</sup> modification</th><th>Éditeur</th><th>Action</th></tr>
<?php
$txtEditeur = '';
$i = 0;
foreach ($listePage as $page)
{
	$i++;
	if($i > 3)
		$suppr = '<a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette page ?\'));" href="page-fixe-supprimer-'. $page['id']. '" title="Supprimer la page"><img src="../images/x-20.png" alt="Supprimer" /></a>';
	else
		$suppr = '<img title="Il est impossible de supprimer cette page" src="../images/cadenas-16.png" alt="cadenas" />';
		
	if($i%2)
		echo '<tr class="impaire">';
	else
		echo '<tr class="paire">';
	echo '<td>' . $page['id'] . '</td>
	<td class="cesure">'. $page['titre']. '</td>
	<td>'. $page['url']. '</td>
	<td>'.$page['dateModif']->format('d/m/Y à H\hi'). '</td>
	<td class="cesure">' . $page['editeur']->usuel() . '</td>
	<td class="centrer"><a href="page-fixe-modifier-'. $page['id']. '" title="Modifier la page"><img src="../images/crayon-20.png" alt="Modifier" /></a>    ' . $suppr . '
	</td></tr>'. "\n";
}
?>
</table>
