<h2><?php echo \Library\Entities\FormatageTexte::multiLigne($page->titre()); ?></h2>

<div class="page_fixe">	
	<?php echo \Library\Entities\FormatageTexte::multiLigne($page->texte()); ?><br>
	<div class="pf_maj">
		Dernière mise-à-jour par <?php echo \Library\Entities\FormatageTexte::monoLigne($page->editeur()->usuel()); ?>, le <?php echo $page->dateModif()->format('d/m/Y à H:i:s'); ?>
	</div>
</div>

