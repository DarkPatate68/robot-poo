<h1><?php echo $title; ?></h1>

<div class="note">Légende :<br/>
<strong>A.</strong> = membre actif ;
<strong>V.</strong> = membre validé ;
<strong>T-S.</strong> = T-Shirt.
<br/><br/>
Astuces :<br/>
Pensez à utiliser la combinaison de touche <span class="touche">Ctrl</span> + <span class="touche">F</span> pour rechercher un membre.<br/>
Utilisez le clique-molette pour vous déplacer horizontalement dans le tableau.
</div>

Il y a <?php echo $nbrMbr;?> membres sur le site.<br/>

<div id="liste_membre">
<table>
  <tr>
    <th>Id</th>
    <th>Pseudo</th>
    <th>Prénom</th>
    <th>Nom</th>
    <th>Courriel</th>
    <th>Date insc.</th>
    <th>A.</th>
    <th>V.</th>
    <th>Groupe</th>
    <th>Classe</th>
    <th>Tél.</th>
    <th>T-S.</th>
  </tr>
<?php 
$i=0;
foreach ($listeMbr as $membre)
{
	if($membre['id'] == 0)
		continue;
	if($i%2 == 0)
		$impaire = '';
	else
		$impaire = ' class="impaire"';
	
	$actif = ($membre['actif'])?'vrai':'faux';
	$valide = ($membre['valide'])?'vrai':'faux';
	
	if(strlen($membre['courriel']) > 7)
		$courrielCourt = substr($membre['courriel'], 0, 3) . '…' . substr($membre['courriel'], -3, 3);
	else
		$courrielCourt = $membre['courriel'];
	
	echo '<tr ' . $impaire . '>
			<td>' . $membre['id'] . '</td>
			<td><a href="pre/equipe-' . $membre['id'] . '">' . $membre['pseudo'] . '</a></td>
			<td>' . $membre['prenom'] . '</td>
			<td>' . $membre['nom'] . '</td>
			<td title="' . $membre['courriel'] . '">' . $membre['courriel'] . '</td>
    		<td>' . $membre['dateInscription']->format('d/m/Y') . '</td>
    		<td class="' . $actif . '">' . $membre['actif'] . '</td>
	    	<td class="' . $valide . '">' . $membre['valide'] . '</td>
	    	<td>' . $membre['groupeObjet']['nom'] . '</td>
	    	<td>' . $membre['section'] . '</td>
	    	<td>' . $membre['telephone'] . '</td>
	    	<td>' . $membre['tshirt'] . '</td>
		</tr>';
		$i++;
}
?>
</table>
</div>

