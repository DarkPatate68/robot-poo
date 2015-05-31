<?php
require_once '../Library/ApplicationComponent.class.php';
require_once '../Library/BackController.class.php';
require_once '../Applications/Backend/Modules/ImportImage/ImportImageController.class.php';

require_once '../Library/Entity.class.php';
require_once '../Library/Entities/Utilitaire.class.php';
//exit();
//$this->viewRight('importer_image');
		$error = 'OK';
		$filename = '';
		
		if(!isset($_POST['mdp']) || $_POST['mdp'] != 'akIU$2as85B')
		{
			$error = 'Vous n\'avez pas le droit d\'importer d\'images';
		}
		else
		{
			if(!isset($_FILES['image']))
			{
			   $error = 'Aucun fichier transféré.';
			}
			if(!isset($_POST['domaine']))
			{
			    $error = 'Votre formulaire à rencontré une erreur.';
			}
			if(!isset($_POST['idTextEdit']))
			{
				$error = 'Votre formulaire à rencontré une erreur.';
			}
			
			
			    if($_FILES['image']['error'] == 0)
			    {
			        $domaine = $_POST['domaine'];
			        if(!array_key_exists($domaine, \Applications\Backend\Modules\ImportImage\ImportImageController::$DOMAINES))
			        {
			            $error = 'Votre formulaire est corrompu.';
			        }
			        
			        $idImage = (string) uniqid();
			        
			        if(!isset($_POST['hauteur']) || $_POST['hauteur'] <= 0)
			            $hauteur = false;
			        else
			            $hauteur = (int) $_POST['hauteur'];
			        
			        if(!isset($_POST['largeur']) || $_POST['largeur'] <= 0)
			            $largeur = false;
			        else
			            $largeur = (int) $_POST['largeur'];
			        
			        if(!isset($_POST['alt']))
			            $alt = '';
			        else
			        {
			            $txtAlt = (string) $_POST['alt'];
			            if(empty($txtAlt))
			                $alt = '';
			            else 
			                $alt = htmlspecialchars($txtAlt);
			        }
			        
			        $image = \Library\Entities\Utilitaire::traitementFichier(
			                  $_FILES['image'], 
			                  array('jpeg', 'jpg', 'png', 'gif', 'bmp'), 
		                      'fichiers/' . $domaine . '/images/' . $idImage, 
			                  3145728, // Taille maximale en octet
			                  $largeur, 
			                  $hauteur);
			        	
			        if($image == 'ERR_POIDS')
			        {
			            $error = 'Votre image est trop grosse (taille maximale 3&nbsp;Mio).';
			        }
			        else if($image == 'ERR_EXTENSION')
			        {
			            $error = 'Extension de l\'image incorrecte (JPEG, PNG, GIF ou BMP uniquement).';
			        }
			        else if($image == 'ERR_REDIM')
			        {
			            $error = 'Une erreur est survenue lors du redimensionnement de l\'image.';
			        }
			        else if($image === false)
			        {
			            $error = 'Une erreur inconnue est survenue.';
			        }
			        else
		            {
		                $filename = '![' . $alt . '](fichiers/' . $domaine . '/images/' . $idImage . '.' . $image . ')';
		            }
			            
			    }
			    else
			        $error = 'Une erreur est survenue lors du transfert de l\'image : ' . \Library\Entities\Utilitaire::codeErreurFichier($_FILES['image']['error']);
		}
		    ?>
		    
		    <script>
		        window.top.window.uploadEnd("<?php echo $error; ?>", "<?php echo $filename; ?>", "<?php echo $_POST['idTextEdit']; ?>");
		    </script>