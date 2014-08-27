<h2><?php echo $title; ?></h2>

<div class="note">
	<ol>
		<li>Pour supprimer l'équipe, il suffit de supprimer tous ses membres.</li>
	</ol>
</div>

<form method="post" action="">
<p id="tout_cocher">
	<label for="tt_cocher">Tout (dé)cocher : </label><input id="tt_cocher" type="checkbox" onclick="cocher(this.checked)" />
</p>
<p id="supprimer_membre">
	<?php 
	$i = 0;
	foreach ($page as $membre)
	{
		$noms = array();
		$paireImpaire = ($i%2)?'impaire':'paire';
		if($membre->membre()->id() === '0')
		{
			if(preg_match('#^\[\[([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)@([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)\]\](.*)$#', $membre->description(), $noms) !== false)
			{
				$nom = '<em>' . $noms[1] . ' ' . $noms[2] . '</em>';
			}
			else
			{
				$nom = '<em>Membre anonyme</em>';
			}
		}
		else
		{
			$nom = $membre->membre()->prenom() . ' ' . $membre->membre()->nom() . ' <em>alias</em> ' . $membre->membre()->pseudo();
		}
		echo '<span class="' . $paireImpaire . '"><label for="membre_' . (string) $membre->id() . '">' . $nom . ' (' . strtolower(explode('_', $membre->fonction(), 2)[1]) . ' — ' . $membre->classe() . ')</label><input type="checkbox" id="membre_' . (string) $membre->id() . '" name="membre_' . (string) $membre->id() . '" /></span><br/>';
	$i++;
	}
	?>
	<input type="submit"/>
</p>
</form>

<script>
function cocher(etat) {
	  var inputs = document.getElementById('supprimer_membre').getElementsByTagName('input');
	  for(i = 0; i < inputs.length; i++) {
	    if(inputs[i].type == 'checkbox')
	      inputs[i].checked = etat;
	  }
	}
</script>
