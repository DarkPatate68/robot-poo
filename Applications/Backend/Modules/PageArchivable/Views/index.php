 <h1>Gestion des pages archivables</h1>
 
 <?php
 echo '<div style="margin: 10px"><a href="page-archivable-ajouter"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter un groupe de pages archivables</div></a></div>';
 ?>
 
<table>
  <tr>
	<th>Titre</th>
	<th>URL</th>
	<th>D<sup>ère</sup> modification</th>
	<th>Éditeur</th>
	<th>Archive</th>
	<th><span class="abbr_info" title="Modifier uniquement la page sélectionnée dans la colonne archive">A<sup>o</sup> page</span></th>
	<th><span class="abbr_info" title="Action sur le groupe de page">A<sup>o</sup> groupe</span></th>
</tr>
<?php
$txtEditeur = '';
$i = 0;
$k = 0;
$nbrArchive = 0;
$urlPrecendente = '';


foreach ($listePage as $page)
{
	$i++;
	$nbrArchive = 0;
	if($urlPrecendente == $page['url'])
		continue;
	$k++;
		
	if(!in_array($page['url'], array('presentation-robot', 'robot-mecanique', 'robot-electronique', 'robot-programmation')))
	{
		$suppr = '<a id="suppr_tout_' . $page['url'] . '" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer ce groupe de page ?\nATTENTION : cette action est irréversible et supprimera TOUTES les pages du groupe concerné !\'));" href="page-archivable-supprimer-tout-' . $page['url'] . '" title="Supprimer le groupe de page"><img src="../images/x-20.png" alt="Supprimer" /></a>';
		$mod = '<a onclick="changerGroupe(\''.$page['url'].'\', \''.$page['titre'].'\')" id="mod_titre_' . $page['url'] . '" href="#"'./*href="page-archivable-modifier-tout-' . $page['url']*/'' . '" title="Modifier le titre et l\'url du groupe"><img src="../images/crayon-20.png" alt="Modifier" /></a>';
	}
	else
	{
		$suppr = '<img title="Il est impossible de supprimer ce groupe de page" src="../images/cadenas-16.png" alt="cadenas" />';
		$mod = '<img title="Il est impossible de modifier l\'url et le titre ce groupe de page" src="../images/cadenas-16.png" alt="cadenas" />';
	}	
	if($k%2)
		echo '<tr class="impaire">';
	else
		echo '<tr class="paire">';
	?>
	<td><span <?php echo((substr($page['titre'], 0, $nbrCaractereTitre) != $page['titre'])?'title="'.$page['titre'].'"':''); ?>><?php echo substr($page['titre'], 0, $nbrCaractereTitre); echo((substr($page['titre'], 0, $nbrCaractereTitre) != $page['titre'])?'...':''); ?></span></td>
	<td><span <?php echo((substr($page['url'], 0, $nbrCaractereUrl) != $page['url'])?'title="'.$page['url'].'"':''); ?>><?php echo substr($page['url'], 0, $nbrCaractereUrl); echo((substr($page['url'], 0, $nbrCaractereUrl) != $page['url'])?'...':''); ?></span></td>
	<td><?php echo $page['dateModif']->format('d/m/Y à H\hi'); ?></td>
	<td><?php echo $page['editeur']->usuel(); ?></td>
	<td><select name="archive_<?php echo $page['url']; ?>" id="archive_<?php echo $page['url']; ?>" onchange="changerLien('<?php echo $page['url']; ?>');">
		<?php
			for($j = $i-1 ; $j < count($listePage) &&  $listePage[$j]['url'] == $page['url'] ; $j++)
			{
				echo '<option value="' . $listePage[$j]['archive'] . '">' . $listePage[$j]['archive'] . '</option>';
				$nbrArchive++;
			}
			$urlPrecendente = $page['url'];
		?>
		</select>
	</td>
	<td class="centrer">
		<a id="mod_<?php echo $page['url']; ?>" href="page-archivable-modifier-<?php echo str_ireplace('/', '-', $page['archive']) . '-' . $page['url']; ?>" title="Modifier la page sélectionnée"><img src="../images/crayon-20.png" alt="Modifier" /></a>    
		<a id="suppr_<?php echo $page['url']; ?>" onclick="return(confirm('Êtes-vous sûr de vouloir supprimer cette page ? <?php if($nbrArchive <= 1) echo '\nAttention, la suppression de cette page entrainera la suppression du groupe de page.';?>'));" href="page-archivable-supprimer-<?php echo str_ireplace('/', '-', $page['archive']) . '-' . $page['url']; ?>" title="Supprimer la page sélectionnée"><img src="../images/x-20.png" alt="Supprimer" /></a>
	</td>
	<td class="centrer">
		<?php echo $mod; ?>
		<a id="ajout_<?php echo $page['url']; ?>" href="page-archivable-ajouter-<?php echo $page['url']; ?>" title="Ajouter une page au groupe"><img src="../images/ajouter-page-20.png" alt="Ajouter" /></a>    
		<?php echo $suppr; ?>
	</td>
</tr>
<?php
}
?>
</table>

<form action="page-archivable-modifier" method="post" name="formulaire" class="cache">
<div>
	<input type="hidden" value="" id="ancienUrl" name="ancienUrl"/>
	<input type="hidden" value="" id="ancienTitre" name="ancienTitre"/>
	<input type="hidden" value="" id="nouveauUrl" name="nouveauUrl"/>
	<input type="hidden" value="" id="nouveauTitre" name="nouveauTitre"/>
	
</div>
</form>

<script>
<!--
	function changerLien(url)
	{
		var modifier = document.getElementById("mod_"+url);
		var supprimer = document.getElementById("suppr_"+url);
		
		var index = document.getElementById("archive_"+url).selectedIndex;
		var liste = document.getElementById("archive_"+url).options;
		var archive = liste[index].text;
		
		modifier.href = "page-archivable-modifier-" + archive.replace("/", "-") + "-" + url;
		supprimer.href = "page-archivable-supprimer-" + archive.replace("/", "-") + "-" + url;
	}
	
	function changerGroupe(url, titre)
	{
		var nvUrl = prompt("Indiquez le nouvel URL", url);
		var nvTitre = prompt("Indiquez le nouveau titre", titre);
		
		if(nvTitre == null || nvUrl == null)
			return;
			
		document.getElementById("ancienUrl").value = url;
		document.getElementById("ancienTitre").value = titre;
		document.getElementById("nouveauUrl").value = nvUrl;
		document.getElementById("nouveauTitre").value = nvTitre;
		
		document.formulaire.submit();
	}
//-->
</script>
