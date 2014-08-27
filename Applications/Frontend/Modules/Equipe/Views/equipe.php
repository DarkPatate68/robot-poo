<div class="equipe">
	<aside>	
		<div id="choix_annee">
			<form method="post" action="">
				<p><label for="annee">Choisissez l'archive : </label>
					<select name="annee" id="annee" onchange="javascript: submit(this)">
						<?php
						foreach($annees as $annee) {
							if($annee == $page[0]->archive())
								$selectionne = 'selected="selected"';
							else
								$selectionne = '';?>
							<option value="<?php echo $annee; ?>" <?php echo $selectionne; ?>><?php echo $annee; ?></option>
						<?php } ?>
					</select>
				</p>
			</form>
		</div>
		<div><img src="images/fleche-orange-20.png" alt="Page actuelle" title="Page actuelle"/> <strong>Équipe de l'année <?php echo $page[0]->archive(); ?></strong></div>
	</aside>
	
	<h2>Présentation de l'équipe</h2>
	   <?php if($anneEnCours != $page[0]->archive())
	   {?>
	   <div class="attention">
			<img src="<?php echo $GLOBALS['PREFIXE']; ?>/images/attention_flash-32.png" alt="attention"/> 
			<span>C'est une ancienne équipe.</span>
		</div>
	   <?php } 
	   if(file_exists('images/equipe/' . str_ireplace("/", "-", $page[0]->archive()) . '.jpg'))
	   {
	   ?>	   
	<div class="equipe_photo_gal">
		<img src="<?php echo 'images/equipe/' . str_ireplace("/", "-", $page[0]->archive()) . '.jpg'; ?>" alt="Équipe au complet" width="800px"/>
	</div>
	<?php } ?>
	<div class="equipe_liste">
		<?php 
			foreach ($page as $membre)
			{
			    $noms = array();
				if($membre->membre()->id() == 0)
				{
				    if(preg_match('#^\[\[([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)@([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)\]\](.*)$#', $membre->description(), $noms) !== false)
				    {
				        $nom = '<em>' . $noms[1] . ' ' . $noms[2] . '</em>';
				        $description = $noms[3];
				    }
				    else
				    {
				        $nom = '<em>Membre anonyme</em>';
				        $description = $membre->description();
				    }
				}
				else
				{
				    $nom = '<a href="equipe-' . $membre->membre()->id() . '-' . \Library\Entities\FormatageTexte::monoLigne($membre->membre()->prenom()) .'-' . \Library\Entities\FormatageTexte::monoLigne($membre->membre()->nom()) . '">' . \Library\Entities\FormatageTexte::monoLigne($membre->membre()->prenom()) . ' ' . \Library\Entities\FormatageTexte::monoLigne($membre->membre()->nom()) . '</a>';
				    $description = $membre->description();
				}
				
				if($droit)
					$modifier = '<a href="membre/equipe-modifier-' . $membre->id() . '"><img src="images/crayon-20.png" alt="Modifier" /></a> ';
				else
					$modifier = '';
			    ?>
					<table class="equipe_membre">
					<tr>
						<td rowspan="2" class="equipe_photo">
							<?php 
							if($membre->photo() != '0')
								echo '<img src="images/equipe/' . $membre->photo() . '" alt="photo" />';
							else
								echo '<img src="images/equipe/membre.png" alt="photo" />';
							?>
						</td>
						
						<td class="equipe_nom">
						   <?php echo $modifier; ?><strong><?php echo $nom; ?></strong>
						</td>
					</tr>
					<tr>						
						<td class="equipe_description">
						<strong>Fonction :</strong> <?php echo strtolower(explode('_', $membre->fonction(), 2)[1]); ?><br/>
						<strong>Classe :</strong> <?php echo $membre->classe(); ?><br/>
						<?php echo \Library\Entities\FormatageTexte::multiLigne($description); ?>
						</td>
					</tr>
					</table>
				<?php
			}
		
		?>
		
	</div>
</div>

