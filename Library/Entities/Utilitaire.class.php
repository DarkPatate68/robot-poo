<?php
namespace Library\Entities;

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
			
		$destination = imagecreatetruecolor($LARGEUR_MINIMALE, $HAUTEUR_MINIMALE); // On crée la miniature vide
		imagealphablending($destination, false);
		imagesavealpha($destination, true);
	 
		// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);
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
	}
	
	static public function chiffrer($mdp)
	{
		return sha1('GlOuBiboulga$' . sha1($mdp));
	}
}