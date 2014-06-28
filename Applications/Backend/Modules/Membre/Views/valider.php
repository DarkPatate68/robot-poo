<?php $nbrMbr = count($listeMembreAValider); ?>
<p>Il y a actuellement <?php echo $nbrMbr; ?> personne<?php echo(($nbrMbr>1)? 's': ''); ?> à valider :</p>

<?php if($nbrMbr != 0)
{ ?>

<form action="" method="post">
<div>
<table>
<thead>
<tr>
	<th>Nom</th>
	<th>Prénom</th>
	<th>Pseudo</th>
	<th>Section</th>
	<th>Date d'inscription</th>
	<th>Accepter</th>
</tr>
</thead>
<tbody>
<?php 
$i=0;
foreach($listeMembreAValider as $membre)
{ ?>
	<tr class="<?php echo (($i%2)? 'paire': 'impaire');?>">
		<td><?php echo $membre->nom(); ?></td>
		<td><?php echo $membre->prenom(); ?></td>
		<td><?php echo $membre->pseudo(); ?></td>
		<td><?php echo $membre->section(); ?></td>
		<td><?php echo $membre->dateInscription()->format('d/m/Y'); ?></td>
		<td><input type="checkbox" name="mbr_<?php echo $membre->id(); ?>" />
		</td>
	</tr>
<?php $i++;} ?>
</tbody>
</table><br/>
<input type="submit" value="Valider"/>
</div>
</form>
<?php } ?>