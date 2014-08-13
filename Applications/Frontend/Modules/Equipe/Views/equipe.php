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
		<img src="<?php echo 'images/equipe/' . str_ireplace("/", "-", $page->archive()) . '.jpg'; ?>" alt="Équipe au complet" />
	</div>
	<?php } ?>
	<div class="equipe_liste">
		<?php 
			foreach ($page as $membre)
			{
				?>
					<div class="equipe_membre">
						<div class="equipe_photo">
							<?php 
							if($membre->photo() != '0')
								echo '<img src="images/equipe/' . $membre->photo() . '" alt="photo" />';
							else
								echo '<img src="images/equipe/membre.jpg" alt="photo" />';
							?>
						</div>
						
						<div class="equipe_nom">
						</div>
						
						<div class="equipe_description">
						<?php echo \Library\Entities\FormatageTexte::multiLigne($membre->description()); ?>
						</div>
					</div>
				<?php
			}
		
		?>
		
	</div>
</div>

