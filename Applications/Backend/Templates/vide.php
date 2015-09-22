<?php
	if(!isset($partieMembre))
		$partieMembre = ''; // ajoute un préfixe (../) pour la partie membre ; pour revenir en arrière dans les dossiers
	
	if(!isset($categorieCSS))
		$categorieCSS = 'accueil'; // obsolète
		
	$ERREUR = isset($ERREUR_BDD);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
		<?php if(isset($design)) { ?> <link rel="stylesheet" href="../css/<?php echo $design ?>" type="text/css" /> <?php } // Rajoute une seconde feuille de style si besoin ?>
		<link rel="icon" type="image/png" href="<?php echo $partieMembre; ?>images/favicone.png" />
		 <title>
		  <?php if (!isset($title)) echo 'Club Robotique INSA Strasbourg';
				else echo $title . ' — Club Robotique INSA Strasbourg';?>
		</title>
		
    </head>
	
	<body>
        
	<?php echo $content; ?>	

	<?php if(isset($script)) { ?> <script src="../scripts/<?php echo $script ?>" type="text/javascript"></script> <?php } // Rajoute un autre script si besoin ?>
	
    </body>
</html>