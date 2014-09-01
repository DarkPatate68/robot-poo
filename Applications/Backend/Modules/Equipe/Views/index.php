 <h1>Gestion des équipes</h1>
 
 <?php
 echo '<div style="margin: 10px"><a href="equipe-ajouter"><div class="bouton"><img src="../images/plus-16.png"/> Ajouter une équipe</div></a></div>';
 ?>
 
<table>
  <tr>
	<th>Année</th>
	<th>Nombre de membre</th>
	<th>Photo de groupe</th>
	<th>Action photo</span></th>
	<th>Action membre</span></th>
</tr>
<?php
$i=0;
foreach ($listeEquipe as $equipe)
{

	if($i%2)
		echo '<tr class="impaire">';
	else
		echo '<tr class="paire">';
	?>
	<td><?php echo \Library\Entities\FormatageTexte::monoLigne($equipe['archive']); ?></td>
	<td><?php echo $nbrMembre[$i]; ?></td>
	<td><?php echo $photo[$i]?'Oui':'Non'; ?></td>
	<td class="centrer">
		<?php if($photo[$i]){ ?>
					<a id="ajout_photo_<?php echo str_ireplace('/', '_', $equipe['archive']); ?>" href="equipe-ajouter-photo-<?php echo str_ireplace('/', '-', $equipe['archive']); ?>" title="Modifier la photo de groupe"><img src="../images/crayon-20.png" alt="Modifier" /></a>    
					<a id="suppr_photo_<?php echo str_ireplace('/', '_', $equipe['archive']); ?>" onclick="return(confirm('Êtes-vous sûr de vouloir supprimer la photo de groupe ?'));" href="equipe-supprimer-photo-<?php echo str_ireplace('/', '-', $equipe['archive']); ?>" title="Supprimer la photo de groupe"><img src="../images/x-20.png" alt="Supprimer" /></a>
		<?php }
			  else{ ?>
					<a id="ajout_photo_<?php echo str_ireplace('/', '_', $equipe['archive']); ?>" href="equipe-ajouter-photo-<?php echo str_ireplace('/', '-', $equipe['archive']); ?>" title="Ajouter une photo de groupe"><img src="../images/ajouter-page-20.png" alt="Ajouter" /></a>    
			<?php } ?>
	</td>
	<td class="centrer">
		<a id="ajout_membre_<?php echo str_ireplace('/', '_', $equipe['archive']); ?>" href="equipe-ajouter-membre-<?php echo str_ireplace('/', '-', $equipe['archive']); ?>" title="Ajouter une membre"><img src="../images/ajouter-page-20.png" alt="Ajouter" /></a>    
		<a id="suppr_membre_<?php echo str_ireplace('/', '_', $equipe['archive']); ?>" href="equipe-supprimer-membre-<?php echo str_ireplace('/', '-', $equipe['archive']); ?>" title="Supprimer un membre"><img src="../images/x-20.png" alt="Supprimer" /></a>
	</td>
</tr>
<?php
$i++;
}
?>
</table>

