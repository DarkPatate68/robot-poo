<?php
namespace Applications\Backend\Modules\ImportImage;
 
class ImportImageController extends \Library\BackController
{
	// *************************************************
	// ******** Importer des images sur le site ********
	// *************************************************
	
	public static $DOMAINES = array("news" => "News",
	                                 "commentaire" => "Commentaires",
                                      "page-fixe" => "Pages non-archivables",
                                      "page-archivable" => "Pages archivables",
                                      "partenaire" => "Partenaires",
                                      "equipe" => "Équipe",
                                      "autre" => "Autre"
                                      );
	
	
	/**
	 * Affiche le formulaire pour importer des images sur le site. Appelle la fonction de traitement des images.
	 * @param \Library\HTTPRequest $request
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('importer_image');
		
		$this->page->addVar('title', 'Formulaire d\'importation d\'images');

		if($request->method() == 'POST')
		  $this->processusFormulaire($request);
		
		$this->page->addVar('design', 'importer.css');
		$this->page->addVar('domaines', self::$DOMAINES);
		$this->page->addVar('user', $this->app->user());
	}
	
		
	/**
	 * Traite les données reçues par le formulaire.
	 * @param \Library\HTTPRequest $request
	 */
	public function processusFormulaire(\Library\HTTPRequest $request)
	{
		$this->viewRight('importer_image');
		
		if(!isset($_FILES['image']))
		{
		    $this->app->user()->setFlash('Il faut choisir une image !', 'ERREUR');
		    $this->app->httpResponse()->redirect('importer-image');
		}
		if(!$request->postExists('domaine'))
		{
		    $this->app->user()->setFlash('Votre formulaire à rencontré une erreur.', 'ERREUR');
		    $this->app->httpResponse()->redirect('importer-image');
		}
		
		
		    if($_FILES['image']['error'] == 0)
		    {
		        $domaine = $request->postData('domaine');
		        if(!array_key_exists($domaine, self::$DOMAINES))
		        {
		            $this->app->user()->setFlash('Votre formulaire est corrompu.', 'ERREUR');
		            $this->app->httpResponse()->redirect('importer-image');
		        }
		        
		        $idImage = (string) uniqid();
		        
		        if(!$request->postExists('hauteur') || $request->postData('hauteur') <= 0)
		            $hauteur = false;
		        else
		            $hauteur = (int) $request->postData('hauteur');
		        
		        if(!$request->postExists('largeur') || $request->postData('largeur') <= 0)
		            $largeur = false;
		        else
		            $largeur = (int) $request->postData('largeur');
		        
		        if(!$request->postExists('alt'))
		            $alt = '';
		        else
		        {
		            $txtAlt = (string) $request->postData('alt');
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
		            $this->app->user()->setFlash('Votre image est trop grosse (taille maximale 3&nbsp;Mio).', 'ERREUR');
		            $this->app->httpResponse()->redirect('importer-image');
		        }
		        else if($image == 'ERR_EXTENSION')
		        {
		            $this->app->user()->setFlash('Extension de l\'image incorrecte (JPEG, PNG, GIF ou BMP uniquement).', 'ERREUR');
		            $this->app->httpResponse()->redirect('importer-image');
		        }
		        else if($image == 'ERR_REDIM')
		        {
		            $this->app->user()->setFlash('Une erreur est survenue lors du redimensionnement de l\'image.', 'ERREUR');
		            $this->app->httpResponse()->redirect('importer-image');
		        }
		        else if($image === false)
		        {
		            $this->app->user()->setFlash('Une erreur inconnue est survenue.', 'ERREUR');
		            $this->app->httpResponse()->redirect('importer-image');
		        }
		        else
	            {
	                $this->app->user()->setFlash('Image importée avec succès, voici le MarkDown à utiliser pour l\'insérer dans un formulaire :<br/>
	                                             <strong>![' . $alt . '](fichiers/' . $domaine . '/images/' . $idImage . '.' . $image . ')</strong>');
	                $this->app->httpResponse()->redirect('importer-image');
	            }
		            
		    }
		    else
		        $this->app->user()->setFlash('Une erreur est survenue lors du transfert de l\'image : ' . \Library\Entities\Utilitaire::codeErreurFichier($_FILES['image']['error']), 'ERREUR');
		    
		    	
	}
}