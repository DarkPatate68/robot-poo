<h1>Nos partenaires</h1>

<div class="part_intro">
	Voici la liste des partenaires du club robotique de l'INSA de Strasbourg. Un grand merci à eux, car grâce à leur générosité, nous pouvons aller de l'avant.<br/><br/>
	<em>La liste est donnée dans l'ordre alphabétique.</em>
</div>
<?php
$i=0;
	foreach($listePartenaire as $partenaire)
	{?>
		 <div class="part_partenaire">
			<div class="part_metadonnees">
				<div class="part_nom"><strong><?php echo \Library\Entities\FormatageTexte::monoLigne($partenaire['nom']);?></strong></div>
				<div class="part_action">
					<?php
					if($user->membre()->groupeObjet()->droits('mod_partenaire')){ 
						echo '<a href="' . $GLOBALS['PREFIXE'] . '/membre/partenaire-modifier-' . $partenaire->id() . '" title="Modifier le partenaire">';
					    echo '<img src="images/crayon-20.png" alt="Modifier le partenaire"/></a>';
					} ?>
				</div>
			</div>
			<div class="part_contenu <?php if($i%2 != 0) echo 'paire'; ?>">
				<aside class="part_img"><img src="images/partenaire/<?php echo $partenaire['image']; ?>" alt="Logo du partenaire" /></aside>
				<div class="part_texte">
					<?php echo \Library\Entities\FormatageTexte::multiLigne($partenaire['description']); ?>
				</div>
			</div>
		</div>
<?php $i++;
} ?>


