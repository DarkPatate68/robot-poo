<div class="page_archivable">
	<aside>	
		<div id="choix_annee">
			<form method="post" action="">
				<p><label for="annee">Choisissez l'archive : </label>
					<select name="annee" id="annee" onchange="javascript: submit(this)">
						<?php
						foreach($annees as $annee) {
							if($annee == $page->archive())
								$selectionne = 'selected="selected"';
							else
								$selectionne = '';?>
							<option value="<?php echo $annee; ?>" <?php echo $selectionne; ?>><?php echo $annee; ?></option>
						<?php } ?>
					</select>
					<input type="hidden" value="<?php echo $url; ?>" name="url" id="url"/>
				</p>
			</form>
		</div>
		<div><img src="images/fleche-orange-20.png" alt="Page actuelle" title="Page actuelle"/> <strong>Page de l'année <?php echo $page->archive(); ?></strong></div>
	</aside>
	
	<h1><?php echo $page->titre(); ?></h1>
	   <?php if($anneEnCours != $page->archive())
	   {?>
	   <div class="attention">
			<img src="<?php echo $GLOBALS['PREFIXE']; ?>/images/attention_flash-32.png" alt="attention"/> 
			<span>Cette page est une archive.</span>
		</div>
	   <?php } ?>
	<div class="pa_texte">
		<?php echo \Library\Entities\FormatageTexte::multiLigne($page->texte()); ?>
	</div>
	<div class="pa_maj">
		Dernière mise-à-jour par <?php echo \Library\Entities\FormatageTexte::monoLigne($page->editeur()->usuel()); ?>, le <?php echo $page->dateModif()->format('d/m/Y à H:i:s'); ?>
	</div>
</div>

