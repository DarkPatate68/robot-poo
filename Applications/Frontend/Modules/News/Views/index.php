<h1>Actualités</h1>
<section id="preambule">	
	Bienvenue sur le site du club robotique de l'INSA de Strasbourg ! Sur cette page vous pourrez découvrir les activités récentes du club.<br/><br/>
	
	<!-- <span style="font-size: 1.5em;">Allez voir la <a href="feuille-de-route">feuille de route</a> en premier lieu !</span> -->
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
		<h2 class="titre_news">
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
<?php
$i++;
}

echo $txtPage; // <div class="listePage"><span class="txtPage">Page :</span><span class="pageActuelle">1</span><a href="accueil-2">2</a><a href="accueil-3">3</a></div>
?>
</section>

