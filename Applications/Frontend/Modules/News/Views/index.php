
<section id="preambule">
	<h1>Actualités</h1>
	Bienvenu sur le site du club robotique de l'INSA de Strasbourg ! Sur cette page vous pourrez découvrir les activités récentes du club.
</section>

<section id="bloc_news">
<?php
$txtPage = \Library\Entities\Utilitaire::pagination($nbrPage, $pageActuelle, 'accueil-');

echo $txtPage;
$i=0;
foreach ($listeNews as $news)
{
	if($news['changement'])
	{
		$annees = explode('/', $news['archive']);
		$annees[0] = (int) $annees[0];
		$annees[0]++;
		$annees[0] = (string) $annees[0];
		
		$annees[1] = (int) $annees[1];
		$annees[1]++;
		$annees[1] = (string) $annees[1];
		
		?>
			<div class="changement_annee">
				<div style="text-align: center;"><strong><?php echo $annees[0] . '/' . $annees[1]; ?></strong></div>
				<hr/>
				<div style="text-align: center;"><strong><?php echo $news['archive']; ?></strong></div>
			</div>
		<?php
	}
?>
<div class="news">
	<div class="contenu">
		<h2>
			<a href="news-<?php echo $news['id'] . '-' . $news->titreTiret(); ?>"> <?php echo \Library\Entities\FormatageTexte::monoLigne($news['titre']);?></a>
		</h2>
		
		<?php echo \Library\Entities\FormatageTexte::multiLigne($news['contenu']); ?>
		<?php
			if($estCoupee[$i])
				echo '<div class="suite"><a href="news-' . $news['id'] . '-' . $news->titreTiret() . '">Lire la suite</a></div>';
		?>
	</div>
	<aside class="metadonnees">
		<div class="action">
			<?php if($news['privee'])
					echo '<img src="images/cadenas-16.png" alt="News privée" title="Seul les membres du club peuvent voir cette news"/>&nbsp;'; 
				  if($user->membre()->groupeObjet()->droits('mod_news'))
					echo '<a href="' . $GLOBALS['PREFIXE'] . '/membre/news-modifier-' . $news->id() . '-' . $news->titreTiret() . '" 
						  title="Modifier la news"><img src="images/crayon-20.png" alt="Modifier la news"/></a>';
			?>
		</div>
		
		<div class="auteur">
			<?php echo $news->auteur()->usuel(); ?>
		</div>
		
		<div class="date">
			<?php echo $news->dateAjout()->format('d/m/Y à H:i'); ?>
		</div>
		
		<div class="commentaire">
			<a href="news-<?php echo $news['id'] . '-' . $news->titreTiret(); ?>#commentaires"><?php echo($nombreCommentaire[$i]?$nombreCommentaire[$i]:'aucun');?><?php echo ' commentaire';?><?php echo($nombreCommentaire[$i]>1?'s':'');?></a>
		</div>
	</aside>
</div>
<!--<div class="news">	
  <h2>
	<?php if($news['privee'])echo '<img src="images/cadenas-16.png" alt"News privée" title="Seul les membres du club peuvent voir cette news"/>'; ?><a href="news-<?php echo $news['id'] . '-' . $news->titreTiret(); ?>"> <?php echo \Library\Entities\FormatageTexte::monoLigne($news['titre']);?></a>
	  <a href="news-<?php echo $news['id'] . '-' . $news->titreTiret(); ?>#commentaires" title="Il y a <?php echo $nombreCommentaire[$i];?> commentaire(s)">
		<div class="boutonTitre">
			<img src="images/bulle-20.png" alt="Nombre de commentaires"/> <span class="txtCommentaire"><?php echo $nombreCommentaire[$i];?></span>
		</div>
	  </a>
	  <?php
		if($user->membre()->groupeObjet()->droits('mod_news')){ 
			echo '<a href="' . $GLOBALS['PREFIXE'] . '/membre/news-modifier-' . $news->id() . '-' . $news->titreTiret() . '" title="Modifier la news">';
	  ?>
	  <div class="boutonTitre">
		
		<?php	echo '<img src="images/crayon-20.png" alt="Modifier la news"/>';?>
	  </div></a>
	  <?php } ?>
  </h2>
	  <div class="newsCorps">
		<?php echo \Library\Entities\FormatageTexte::multiLigne($news['contenu']); ?>
		<div class="suite"><a href="news-<?php echo $news['id'] . '-' . $news->titreTiret(); ?>">Lire la suite</a></div>
		<div class="auteurDate">Par <strong><?php echo $news->auteur()->usuel(); ?></strong> le <?php echo $news->dateAjout()->format('d/m/Y à H:i'); ?></div>
	  </div>
 </div>-->
<?php
$i++;
}

echo $txtPage; // <div class="listePage"><span class="txtPage">Page :</span><span class="pageActuelle">1</span><a href="accueil-2">2</a><a href="accueil-3">3</a></div>
?>
</section>

