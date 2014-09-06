<?php
namespace Library\Entities;

/**
 * Regroupe un ensemble de fonction statique qui peuvent être appelée depuis n'importe quelle autre classe.
 * @author Siméon
 *
 */
abstract class Utilitaire extends \Library\Entity
{
		
	static function pagination($nbrPage, $pageActuelle, $prefixe = '', $suffixe = '')
	{
		$txtPage = '<div class="listePage"><span class="txtPage">Page :</span>';

		for($i = 0 ; $i < $nbrPage ; $i++)
		{
			$txtPageActuelle = (string) $i+1;
			if($i == $pageActuelle)
				$txtPage .= '<span class="pageActuelle">' . $txtPageActuelle . '</span>';
			else
				$txtPage .= '<a href="' . $prefixe . $txtPageActuelle . $suffixe . '">' . $txtPageActuelle . '</a>';
		}

		$txtPage .= '</div>';
		
		return $txtPage;
	}
	
	static function image($photo, $HAUTEUR_MINIMALE_AVATAR, $LARGEUR_MINIMALE_AVATAR, $chemin, $nomImage, $vignette = false, $carre = true)
	{
		$extensions_valides = array( 'jpg' , 'jpeg' , 'png' );
		//1. strrchr renvoie l'extension avec le point (« . »).
		//2. substr(chaine,1) ignore le premier caractère de chaine.
		//3. strtolower met l'extension en minuscules.
		$extension_recue = strtolower(  substr(  strrchr($photo['name'], '.')  ,1)  );
		
		if ( in_array($extension_recue,$extensions_valides))
		{
			$taille = getimagesize($photo['tmp_name']);
			if($vignette)
				$photoVignette = $nomImage . '_vignette.' . $extension_recue;
			$nomImage .= '.' . $extension_recue;
			
			move_uploaded_file($photo['tmp_name'], 'images/' . $chemin . $nomImage);
			if($vignette)
				copy('images/' . $chemin . $nomImage, 'images/' . $chemin . $photoVignette);
			if ($taille[0] > $LARGEUR_MINIMALE_AVATAR || $taille[1] > $HAUTEUR_MINIMALE_AVATAR)
			{
				if($carre) // Image carrée
					\Library\Entities\Utilitaire::redimensionner('images/' . $chemin . $nomImage, $extension_recue, $LARGEUR_MINIMALE_AVATAR, $HAUTEUR_MINIMALE_AVATAR);
				else //Respecte les proportions
				{
					 // (Largeur > hauteur ET  largeur plus grande que la taille requise) OU (Largeur < hauteur ET hauteur plus petite que la taille requise)
					if(($taille[0] > $taille[1] && $taille[0] > $LARGEUR_MINIMALE_AVATAR) || (($taille[0] < $taille[1] && $taille[1] < $HAUTEUR_MINIMALE_AVATAR)))
					{
						$largeur = $LARGEUR_MINIMALE_AVATAR;
						$hauteur = $taille[1] * ($LARGEUR_MINIMALE_AVATAR/$taille[0]);
					}
					 // (Largeur > hauteur ET largeur plus petite que la taille requise) OU (Largeur < hauteur ET hauteur plus grande que la taille requise)
					else if(($taille[0] > $taille[1] && $taille[0] < $LARGEUR_MINIMALE_AVATAR) || ($taille[0] < $taille[1] && $taille[1] > $HAUTEUR_MINIMALE_AVATAR))
					{
						$largeur = $taille[0] * ($HAUTEUR_MINIMALE_AVATAR/$taille[1]);
						$hauteur = $HAUTEUR_MINIMALE_AVATAR;
					}
					else
					{
						$largeur = $LARGEUR_MINIMALE_AVATAR;
						$hauteur = $HAUTEUR_MINIMALE_AVATAR;
					}
					\Library\Entities\Utilitaire::redimensionner('images/' . $chemin . $nomImage, $extension_recue, $largeur, $hauteur);
				}
					
			}
			if($vignette)
				\Library\Entities\Utilitaire::redimensionner('images/' . $chemin . $photoVignette, $extension_recue, 40, 50);
			
			return $nomImage;
		}
		else
			return false;
	}
	
	static function redimensionner($chemin, $extension, $LARGEUR_MINIMALE, $HAUTEUR_MINIMALE)
	{
		if($extension == 'jpg' || $extension == 'jpeg')
			$source = imagecreatefromjpeg($chemin);
		else if($extension == 'png')
			$source = imagecreatefrompng($chemin);
		else
			return false;
		
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);
			
		if($LARGEUR_MINIMALE < 0 && $HAUTEUR_MINIMALE > 0) // On impose la hauteur mais on garde les proportions
		{
			$LARGEUR_MINIMALE = ceil($largeur_source*($HAUTEUR_MINIMALE/$hauteur_source));
		}
		else if($HAUTEUR_MINIMALE < 0 && $LARGEUR_MINIMALE > 0) // On impose la largeur mais on garde les proportions
		{
			$HAUTEUR_MINIMALE = ceil($hauteur_source*($LARGEUR_MINIMALE/$largeur_source));
		}
		else if($LARGEUR_MINIMALE < 0 && $HAUTEUR_MINIMALE < 0)
			return false;
					
		$destination = imagecreatetruecolor($LARGEUR_MINIMALE, $HAUTEUR_MINIMALE); // On crée la miniature vide
		imagealphablending($destination, false);
		imagesavealpha($destination, true);
	 
		// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
		
		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);
		
		if($largeur_source == $LARGEUR_MINIMALE && $hauteur_source == $HAUTEUR_MINIMALE)
			return true;
		
		
		 
		// On crée la miniature
		imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
			
		//On enregistre
		if($extension == 'jpg' || $extension == 'jpeg')
			imagejpeg($destination, $chemin);
		else if($extension == 'png')
			imagepng($destination, $chemin);
		
		return true;
	}
	
	static public function chiffrer($mdp)
	{
		return sha1('GlOuBiboulga$' . sha1($mdp));
	}
	
	/**
	 * Renvoie un boléen qui indique si le lien fourni rempli une des routes du site.
	 * @param string $lien Le lien à analyser (sans préfixe, par exemple : /news et non cris/news)
	 * @param string $partie Le côté du site à analyser
	 * @param string $exception Il est possible d'accepter une exception
	 * @return bool Si le lien répond à une route
	 */
	static public function lienRoute($lien, $partie = 'Frontend', $exception = false)
	{
		$partie = ucfirst(strtolower((string) $partie));
		if($partie != 'Frontend' || $partie != 'Backend')
			$partie = 'Frontend';
		
		$xml = new \DOMDocument;
		$cheminFichier = __DIR__ . '/../../Applications/' . $partie . '/Config/routes.xml';
		
		$xml->load($cheminFichier);
		
		$routes = $xml->getElementsByTagName('route');
		
		// On parcourt les routes du fichier XML.
		foreach ($routes as $route)
		{
			if(preg_match('#^' . preg_quote ($route->getAttribute('url')) . '$#', (string) $lien))
			{
				if($exception !== false && preg_match('#^' . preg_quote ($route->getAttribute('url')) . '$#', (string) $exception))
					continue;
				else
					return true;
			}
		}
		return false;
	}

	/**
	 * Traite un fichier reçu par formulaire, se charge de vérifier qu'il est bon (conforme aux extensions) et de le placer au bon endroit.
	 * Si le fichier est une image jpeg ou png, le paramètre hauteurLargeur permet de redimensionner l'image (image carrée uniquement).
	 * @param unknown $fichier Le fichier
	 * @param array $extension La liste des extensions acceptées
	 * @param string $cible Le dossier de destination ainsi que le nom du fichier, sans l'extension.
	 * @param int $poids Le poids maximal (en octet) du fichier
	 * @param string $hauteurLargeur La taille de l'image
	 * @return string/bool Le succès ou non du traitement
	 */
	static public function traitementFichier($fichier, $extension, $cible, $poids, $largeur = false, $hauteur = false)
	{
		$cible = (string) $cible;
		$poids = (int) $poids;
		
		if ($fichier['size'] > $poids)
        	return 'ERR_POIDS';
        
        // Testons si l'extension est autorisée
        $infosfichier = pathinfo($fichier['name']);
        $extension_upload = strtolower($infosfichier['extension']);
        
        if($extension_upload === 'jpeg')
        	$extension_upload = 'jpg';
     
        if (!in_array($extension_upload, $extension))
        	return 'ERR_EXTENSION';
        
        move_uploaded_file($fichier['tmp_name'], $cible . '.' . $extension_upload);
        
        if($largeur !== false)
        {
        	if($hauteur === false)
        		$hauteur = $largeur;
        	
        	if(!\Library\Entities\Utilitaire::redimensionner($cible . '.' . $extension_upload, $extension_upload, $largeur, $hauteur))
        		return 'ERR_REDIM';
        }
        	
        return $extension_upload;
	}
	
	static function convertImage($originalImage, $outputImage, $quality)
	{
	    // jpg, png, gif or bmp?
	    $exploded = explode('.',$originalImage);
	    $ext = $exploded[count($exploded) - 1];
	    $jpeg = false; 
	
	    if (preg_match('/jpg|jpeg/i',$ext))
	    {
	        $imageTmp=imagecreatefromjpeg($originalImage);
	        $jpeg = true;
	    }
	    else if (preg_match('/png/i',$ext))
	        $imageTmp=imagecreatefrompng($originalImage);
	    else if (preg_match('/gif/i',$ext))
	        $imageTmp=imagecreatefromgif($originalImage);
	    else if (preg_match('/bmp/i',$ext))
	        $imageTmp=imagecreatefrombmp($originalImage);
	    else
	        return false;
	
	    // quality is a value from 0 (worst) to 100 (best)
	    imagejpeg($imageTmp, $outputImage, $quality);
	    imagedestroy($imageTmp);
	    
	    if(!$jpeg)
	    	unlink($originalImage);
	
	    return true;
	}
	
	static function codeErreurFichier($code)
	{
		switch ($code) {
			case UPLOAD_ERR_INI_SIZE:
				$message = "La taille du fichier excède la taille permise par la configuration du serveur.";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = "La taille du fichier excède la taille permise par ce formulaire.";
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = "Le téléchargement a été partiellement effectué.";
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = "Aucun fichier.";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = "Missing a temporary folder";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = "Impossible d'écrire sur le disque.";
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = "L'extension du fichier à empêché le transfert.";
				break;
	
			default:
				$message = "Erreur inconnue.";
				break;
		}
		return $message;
	}
}