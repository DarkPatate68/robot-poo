<aside class="info_news">
	<strong>Informations</strong>
	<ul>
		<li><img src="images/bonhomme-orange-20.png" alt="Auteur" title="Auteur"/> <?php echo \Library\Entities\FormatageTexte::monoLigneSansZCode($news->auteur()->usuel()); ?></li>
		<li><img src="images/calendrier-orange-20.png" alt="Date" title="Date de création"/> <?php echo $news['dateAjout']->format('d/m/Y à H\hi'); ?></li>
	</ul>
	<?php 
		if ($news['dateAjout'] != $news['dateModif']) 
		{ ?>
			<hr/>
			<strong>Modification</strong>
			<ul>
				<li><img src="images/calendrier-orange-20.png" alt="Date" title="Date de modification"/> <?php echo $news['dateModif']->format('d/m/Y à H\hi'); ?></li> 
			<?php if($news->auteur()->id() != $news->editeur()->id())
			{?> 
				<li><img src="images/bonhomme-orange-20.png" alt="Éditeur" title="Éditeur"/> <?php echo $news->editeur()->usuel(); ?></li>
	  <?php } ?>
	  		</ul>
	<?php } ?>
	<?php
		if($user->membre()->groupeObjet()->droits('mod_news')){ 
			echo '<a href="' . $GLOBALS['PREFIXE'] . '/membre/news-modifier-' . $news->id() . '-' . $news->titreTiret() . '" title="Modifier la news."><img src="images/crayon-20.png" alt="Modifier la news"/>Modifier</a>';
		 } ?>
</aside>
<h1><?php echo \Library\Entities\FormatageTexte::monoLigne($news->titre()); ?></h1>
<div class="corps_news"><?php echo \Library\Entities\FormatageTexte::multiLigne($news['contenu']); ?></div>


<hr id="separation"/>
<!-- COMMENTAIRES : -->
<div id="commentaires">
	<?php
	$txtAjoutCom = '<a href="commenter-' . $news['id'] . '-' . $news->titreTiret() . '"><div class="bouton"><img src="images/plus-16.png"/> Ajouter un commentaire</div></a>';
	if (empty($commentaires))
	{
		echo $txtAjoutCom;
		$txtAjoutCom = '';
		?>
		<p class="aucun">Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
		<?php
		$txtPage = '';
	}
	else
	{
		$txtPage = \Library\Entities\Utilitaire::pagination($nbrPage, $pageActuelle, 'news-' . (string) $news->id() . '-', '-' . $news->titreTiret());

		echo $txtAjoutCom;
		echo $txtPage;			
	}
	
	$i=0;
	foreach ($commentaires as $commentaire)
	{
	
		if($commentaire->auteur()->id() != 0)
		{
			$debutLien = '<a href="equipe-' . $commentaire->auteur()->id() . '">';
			$finLien = '</a>';
		}
		else
		{
			$debutLien = '';
			$finLien = '';
		}
	?>
		<div class="message" id="C-<?php echo $commentaire->id(); ?>">
        <aside>
        <img src="<?php echo 'images/membres/' . $commentaire->auteur()->avatar(); ?>" alt="avatar" />
    </aside>
    <div class="msg-bulle">
        <div class="msg-metadonnees">
            <div class="msg-pseudo"><?php echo $debutLien . \Library\Entities\FormatageTexte::monoLigneSansZCode($commentaire->auteur()->usuel()) . $finLien; ?></a></div>
            <div class="msg-date">le <?php echo $commentaire['dateAjout']->format('d/m/Y à H\hi'); ?></div>
            <div class="msg-action">
			<?php
						if ($user->isAuthenticated() && ($commentaire->auteur()->id() == $user->membre()->id() || $user->membre()->groupeObjet()->droits('mod_news_commentaire')) && !$commentaire['supprime']) 
						{ 
							echo '<a href="' . $GLOBALS['PREFIXE'] . '/membre/commentaire-modifier-' . $commentaire['id'] . '" title="Modifier le commentaire">
							<img src="images/crayon-20.png" alt="Modifier le commentaire"/></a>';
						}
						if ($user->isAuthenticated() && ($user->membre()->groupeObjet()->droits('mod_news_commentaire'))) 
						{
							echo '<a href="membre/commentaire-supprimer-' . $commentaire['id'] . '">';
							echo (!$commentaire['supprime'] ? '<img src="images/x-20.png" alt="Supprimer" title="Supprimer le commentaire"/>' : '<img src="images/v-20.png" alt="Rétablir" title="Rétablir le commentaire"/>');
							echo '</a>';
						} 
					?>			
			</div>
        </div>
        <div class="msg-contenu">
			<?php
					if($commentaire['supprime'])
					{
						?>
						<div class="moderation"><strong>Message supprimé par <em><?php echo $commentaire['editeur']->usuel(); ?></em> pour la raison suivante : </strong><br/>
						<?php echo $commentaire['moderation'] . '</div>';
					}
					else
						echo \Library\Entities\FormatageTexte::multiLigne($commentaire['contenu']);
				?>
				<?php
				if(!$commentaire['supprime'] && ($commentaire['dateAjout'] != $commentaire['dateModif']))
				{?>
				<div class="com_modif">
					<?php
						$txtCom = 'Dernière modification le ' . $commentaire['dateModif']->format('d/m/Y à H\hi');
						if($commentaire->auteur()->id() != $commentaire->editeur()->id())
							$txtCom .= ' par ' . $commentaire->editeur()->usuel();
						echo $txtCom;
					?>
				</div>
				<?php } ?>
		</div>
    </div>
</div>
	<?php	
		$i++;
	}
	
	$i=0;
	
	echo $txtPage;
	echo $txtAjoutCom;
	?>
</div>
