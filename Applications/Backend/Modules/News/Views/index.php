<h1>Gestion des news</h1>
<p style="text-align: center">Il y a actuellement <?php echo $nombreNews; ?> news. En voici la liste :</p>

<?php
	$txtPage = \Library\Entities\Utilitaire::pagination($nbrPage, $pageActuelle, 'news-');

	echo $txtPage;
	echo '<a href="news-ajouter"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter une news</div></a>';
?>
 
<table>
  <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>D<sup>ère</sup> modification</th><th>Éditeur</th><th>Action</th></tr>
<?php
$txtEditeur = '';
$i = 0;
foreach ($listeNews as $news)
{
	$i++;
	if($news['editeur']->id() == $news['auteur']->id())
		$txtEditeur = '-';
	else
		$txtEditeur = $news['editeur']->usuel();
	
	if($news['privee'])
		$privee = '<img src="../images/cadenas-16.png"" alt="News privée" title="News privée"/>';
	else
		$privee = '';
	
	if($i%2)
		echo '<tr class="impaire">';
	else
		echo '<tr class="paire">';
	echo '<td class="cesure">' . $news['auteur']->usuel() . '</td>
	<td class="cesure">'. $privee . ' ' . $news['titre']. '</td>
	<td>'. $news['dateAjout']->format('d/m/Y à H\hi'). '</td>
	<td>'. ($news['dateAjout'] == $news['dateModif'] ? '-' : $news['dateModif']->format('d/m/Y à H\hi')). '</td>
	<td class="cesure">' . $txtEditeur . '</td>
	<td class="centrer"><a href="news-modifier-'. $news['id']. '-' . $news->titreTiret() . '" title="Modifier la news"><img src="../images/crayon-20.png" alt="Modifier" /></a>    <a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette news ?\'));" href="news-supprimer-'. $news['id']. '-' . $news->titreTiret() . '" title="Supprimer la news"><img src="../images/x-20.png" alt="Supprimer" /></a></td></tr>'. "\n";
}
?>
</table>

<?php
	echo $txtPage;
?>