<?php
	session_start();
		
	$nbr = $_SESSION['nbr'];
	$nomImage = $_SESSION['nomImage'];
	
	$img     = imagecreatefrompng($nomImage);
	$chiffre      = imagecreatetruecolor(10, 10);
	$rouge = imagecolorallocate($chiffre, 192, 57, 43);
	$blanc=imagecolorallocate($chiffre,255,255,255);
	$font = 'polices/Ubuntu-R.ttf';
	imagettftext($chiffre, 8, 0, 0,0, $blanc, $font, $nbr);
	 
	//Position du WM sur l'image finale
	$positionx = 10;
	$positiony = 0;
	 
	//Calcul de la taille de l'image sur laquelle, on va placer le watermark (ou autre image)
	$largeurSrc = imagesx($img);
	$hauteurSrc = imagesy($img);
	 
	//Calcul de l'image du watermark
	$largeurW = imagesx($chiffre);
	$hauteurW = imagesy($chiffre);
	 
	//Si l'image source est plus large que celle du WM, on effectue la mise en place du WM
	if($largeurSrc > $largeurW)
	{
	  // Création de l'image finale aux dimensions de l'image qui va accueillir le WM
	  $final     = imagecreatetruecolor($largeurSrc,$hauteurSrc);
	 
	  // configuration du canal alpha pour le WM
	  imagealphablending($chiffre,false);
	  imagesavealpha($chiffre,true);
	 
	  // Création de l'image finale
	  imagecopyresampled($final,$img,0,0,0,0,$largeurSrc,$hauteurSrc,$largeurSrc,$hauteurSrc);
	  // Ajout du watermark
	  imagecopyresampled($final,$chiffre,$positionx,$positiony,0,0,$largeurW,$hauteurW,$largeurW,$hauteurW);
	  // Pour ajouter d'autre photo par dessus, procéder de la meme façon
	 
	  // affichage de l'image finale
	  header("Content-type: image/png");
	  imagepng($final);
	}
?>
