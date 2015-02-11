<h1>Espace membre</h1>

<?php if(empty($fichier) || empty($version))
		$manuel = "<em>une erreur est survenue dans le chargement du fichier.</em>";
	  else 
	  	$manuel = "<a href=\"../fichiers/autre/documents/$fichier\">manuel (v $version) — PDF</a>";
	  ?>
<div class="zcode">
	Utilisation du zCode sur le site (balises de mise en forme) : <?php echo $manuel; ?>
</div>

<div class="groupe_tuile">
<?php

$titreUtilise = array();

foreach ($modules as $module)
{
	if($user->membre()->groupeObjet()->droits($module['droit']))
	{
		if(!in_array($module['groupe'], $titreUtilise))
		{
			echo '<h2>' . str_ireplace('_', ' ', ucfirst($module['groupe'])) . '</h2>';
			$titreUtilise[] = $module['groupe'];
		}
	?>
		<a href="<?php echo $module['url']; ?>">
		<div class="tuile <?php echo $module['groupe']; ?>">
			<div class="tuile_icone"><img src="<?php echo str_ireplace('.png', '-50.png', $module['image']); ?>" alt="icône"/></div>
			<div class="tuile_texte"><?php echo $module['nom']; ?></div>
		</div></a>
<?php
	}
}
?>
</div>