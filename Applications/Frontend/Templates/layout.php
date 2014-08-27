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
        
		<link rel="stylesheet" href="<?php echo $partieMembre; ?>css/styleGeneral.css" type="text/css" />
		<?php if(isset($design)) { ?> <link rel="stylesheet" href="<?php echo $partieMembre; ?>css/<?php echo $design ?>" type="text/css" /> <?php } // Rajoute une seconde feuille de style si besoin ?>

		 <title>
		  <?php if (!isset($title)) echo 'Club Robotique INSA Strasbourg';
				else echo $title . ' — Club Robotique INSA Strasbourg';?>
		</title>
    </head>
	
	<body>
        <div id="bloc_page">
			<!-- EN-TÊTE -->
            <header>
                
            </header>
			
			<!-- MENU -->
			<nav>
				<ul id="menu">
					<li>
						<a href="<?php echo $GLOBALS['PREFIXE']; ?>">Accueil</a>
					</li>
					
					<li>
						<a href="#">Présentation ▼</a>
						<ul>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/presentation">Qui sommes-nous ?</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/presentation-robot">Notre robot</a></li>
						</ul>
					</li>
					
					<li>
						<a href="#">Le technique ▼</a>
						<ul>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/robot-mecanique">La mécanique</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/robot-electronique">L'électronique</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/robot-programmation">La programmation</a></li>
						</ul>
					</li>
					
					<li>
						<a href="#">Le club ▼</a>
						<ul>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/equipe">L'équipe</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/partenaires">Nos partenaires</a></li>
						</ul>
					</li>
					
					<li>
						<?php if(!$ERREUR)
							{?>
							<?php if ($user->isAuthenticated()) { ?>
							<a href="<?php echo $GLOBALS['PREFIXE']; ?>/equipe-<?php echo $user->membre()->id(); ?>" title="Accéder à ma page personelle"><?php echo substr($user->membre()->usuel(), 0, 14); ?> ▼</a>
							<?php } 
							else {?>
							<a href="#">Membre ▼</a>
							<?php }} 
							?>
						<ul>
							<?php if(!$ERREUR)
							{?>
							<?php if ($user->isAuthenticated()) { ?>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/membre/deconnexion">Déconnexion</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/membre/">Espace membre</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/forum/">Forum</a></li>
							<?php } 
							else {?>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/membre/connexion">Connexion</a></li>
							<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/membre/inscription">Inscription</a></li>
							<?php }} 
							?>
						</ul>
						
					</li>						
				</ul>
            </nav>
            
			<!-- CORPS DE LA PAGE -->
			<div id="corps">
				<?php if(!$ERREUR)
				{?>
				<?php if ($user->hasFlash())
				{
					$flash = explode('_', $user->getFlash(), 2);
				?>
				<div id="flash" class="<?php echo 'flash_' . strtolower($flash[0]); ?>">
					<img src="<?php echo $GLOBALS['PREFIXE']; ?>/images/<?php echo strtolower($flash[0]); ?>_flash-32.png" alt="<?php echo strtolower($flash[0]); ?>"/> 
					<span><?php echo $flash[1]; ?></span>
				</div>
				<?php }
					}?>
					<?php echo $content; ?>				
			</div>
            
			<!-- PIED DE PAGE -->
            <footer>
				<div id="footer_gauche">
					<ul>
						<li><a href="https://www.facebook.com/clubrobotiqueinsastras?fref=ts"><img src="<?php echo $GLOBALS['PREFIXE']; ?>/images/facebook.png" alt="logo facebook"/> Retrouvez-nous sur Facebook !</a></li>
						<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/contacts"><img src="<?php echo $GLOBALS['PREFIXE']; ?>/images/contact-29.png" alt="Enveloppe" /> Contactez-nous</a></li>
					</ul>
				</div>
				<div id="footer_droit">
					<ul>
						<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/clubs-partenaires">Clubs partenaires</a></li>
						<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/liens">Liens</a></li>
						<li><a href="<?php echo $GLOBALS['PREFIXE']; ?>/mentions-legales">Mentions légales</a></li>
					</ul>
				</div>
                <div id="footer_centre">© CRIS – 1992-<?php echo date('Y'); ?></div>
            </footer>
        </div>
	<script src="<?php echo $partieMembre; ?>scripts/scriptGeneral.js" type="text/javascript"></script>
    </body>
</html>