<h1><?php echo $title; ?></h1>

<?php $nbrMbr = count($listeMembreAValider); ?>

<div class="note">
Si vous rendez un membre <em>actif</em>, il sera forcément accepté.
</div>

<p>Il y a actuellement <strong><?php echo $nbrMbr; ?></strong> personne<?php echo(($nbrMbr>1)? 's': ''); ?> à valider :</p>

<?php if($nbrMbr != 0)
{ 

$groupeTxt = '';

foreach($listeGroupe as $groupe)
{
	if($groupe->id() == 1) // Président
		continue;
	if($groupe->id() == '2' || $groupe->id() == 2) // Membre sélectionné par défaut
		$groupeTxt .= '<option value="' . $groupe->id() . '" selected>' . $groupe->nom() . '</option>';
	else
		$groupeTxt .= '<option value="' . $groupe->id() . '">' . $groupe->nom() . '</option>';
}
$groupeTxt .= '</select>';
?>
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
	<th>Courriel</th>
	<th>Accepter</th>
	<th><span class="acronyme" title="Un membre actif est un membre qui est présent au club">Actif</span></th>
	<th>Groupe</th>
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
		<td><?php echo $membre->courriel(); ?></td>
		<td><input type="checkbox" name="mbr_<?php echo $membre->id(); ?>" />
		<td><input type="checkbox" name="mbr_actif_<?php echo $membre->id(); ?>" />
		<td><select name="mbr_groupe_<?php echo $membre->id() . '">' . $groupeTxt; ?>
		</td>
	</tr>
<?php $i++;} ?>
</tbody>
</table><br/>
<input type="submit" value="Valider"/>
</div>
</form>
<?php } ?>