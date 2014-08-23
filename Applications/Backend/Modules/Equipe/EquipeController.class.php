<?php
namespace Applications\Backend\Modules\Equipe;

/**
 * @brief Controlleur des équipes (backend).
 * @details Controlleur des équipes, permet d'ajouter des memebres pour chaque année, d'ajouter une équipe d'une année et d'ajouter une photo de groupe.
 * @author Siméon
 * @date 21/08/2014
 * @version 1.0.0
 *
 */
class EquipeController extends \Library\BackController
{
    	
	/**
	 * Affichage de la liste des équipes
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_equipe');
		
		$this->page->addVar('title', 'Gestion des équipes');

		$manager = $this->managers->getManagerOf('Equipe');
		
		$listeEquipe = $manager->getListeAnnees();
		$nbrMembre = array();
		$photo = array();
		//$this->app->test($listeEquipe);
		foreach($listeEquipe as $equipe)
		{
			$nbrMembre[] = $manager->countAnnee($equipe[0]);
			$photo[] = (int) file_exists('images/equipe/' . str_ireplace("/", "-", $equipe[0]) . '.jpg');
		}
				
						
		$this->page->addVar('listeEquipe', $listeEquipe);
		$this->page->addVar('nbrMembre', $nbrMembre);
		$this->page->addVar('photo', $photo);
		$this->page->addVar('design', 'newsMembre.css');
		
	}
	
	
	/**
	 * Méthode appelée lors de la création d'une équipe ou l'ajout d'un membre.
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeAjouter(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_equipe');
		
		if($request->postExists('membre') && $request->postExists('annee') && $request->postExists('classe') && $request->postExists('description'))
		{
			$membre = (string) $request->postData('membre');
			$annee = (string) $request->postData('annee');
			$classe = (string) $request->postData('classe');
			$description = (string) $request->postData('description');
			
			$drapeauErreur = false;
			
			if(empty($classe))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Veuilliez renseigner la classe.', 'ERREUR');
			}
			
			if(!empty($membre) && $this->managers->getManagerOf('Equipe')->getUniqueByMembreAndArchive($membre, $annee) !== false)
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce membre fait déjà partie de cette équipe.', 'ERREUR');
			}
			
			if(!empty($membre) && $this->managers->getManagerOf('Membre')) // Condition à faire
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce membre n\'existe pas.', 'ERREUR');
			}
			
			if(!$drapeauErreur)
			{
				if(empty($membre))
					$membre = $this->managers->getManagerOf('Membre')->getUnique(0);
				else
					$membre = $this->managers->getManagerOf('Membre')->getUniqueByPseudo($membre);
				
				$equipe = new \Library\Entities\Equipe(array('archive' => $annee,
														'membre' => $membre,
														'classe' => $classe,
														'description' => $description));
			}
		}
		
		if($request->getExists('annee')) //S'il y a l'année, alors, on ajoute un membre
		{
			$annee = (string) $request->getData('annee');
			$listeMembre = $this->managers->getManagerOf('Equipe')->getListeByArchive(str_ireplace('-', '/', $annee), $this->managers->getManagerOf('Membre'));
			
			
			foreach ($listeMembre as $membre)
			{
				$noms = array();
				if($membre['membre']['id'] !== '0')
					$listeMembre2[] = $membre['membre']['prenom'] . ' ' . $membre['membre']['nom'];
				else
				{
					if(preg_match('#^\[\[([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)@([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)\]\](.*)$#', $membre->description(), $noms) !== false)
				    {
				        $listeMembre2[] = '<em>' . $noms[1] . ' ' . $noms[2] . '</em>';
				    }
				    else
				    {
				        $listeMembre2[] = '<em>Membre anonyme</em>';
				    }
				}
			}
			
			$this->page->addVar('title', 'Ajout d\'un membre');			
			$this->page->addVar('action', 'ajouter membre');
			$this->page->addVar('annee', str_ireplace('-', '/', $annee));
			$this->page->addVar('listeMembre', $listeMembre2);
		}
		else // Sinon, on ajoute une équipe
		{
		    $this->page->addVar('title', 'Création d\'une équipe');
		    $this->page->addVar('action', 'ajouter');
		    
		    $listeAnneesSite = $this->app->listeAnneesAllegee(); // Liste de toutes les années du site
		    $listeAnneesEquipe = $this->managers->getManagerOf('Equipe')->getListeAnnees(); // Liste de toutes les années possédent une équipe
		    
		    foreach ($listeAnneesEquipe as $anneeEquipe) // Supprime les années qui possèdent déjà une page
		    {
		    	if(($position = array_search($anneeEquipe[0], $listeAnneesSite)) !== false)
		    		unset($listeAnneesSite[$position]);
		    }
		    
		    if(empty($listeAnneesSite))
		    {
		    	$this->app->user()->setFlash('Toutes les années sont déjà associées à une page.', 'ERREUR');
		    	$this->app->httpResponse()->redirect('equipe');
		    }
		    
		    $this->page->addVar('listeAnnee', $listeAnneesSite);
		}
			
		//$this->processusFormulaire($request);
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'pageEquipe-membre.css');
	}
	
	/**
	 * Fonction appelée lors de la modification d'une page ou d'un groupe de page.
	 * La différentiation se fait grâce aux variables GET qui n'existent pas si l'on modifie un groupe : des variables POST sont utilisées.
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeModifier(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_archivable');
		$manager = $this->managers->getManagerOf('PageArchivable');
		
		// modification du titre et de l'url d'un groupe
		if(!$request->getExists('url') && !$request->getExists('archive'))
		{
			if(!$request->postExists('ancienUrl') || !$request->postExists('ancienTitre') || !$request->postExists('nouveauUrl') || !$request->postExists('nouveauTitre'))
				$this->app->httpResponse()->redirect404();
				
			$ancienUrl = (string) $request->postData('ancienUrl');
			$ancienTitre = (string) $request->postData('ancienTitre');
			$nouveauUrl = (string) $request->postData('nouveauUrl');
			$nouveauTitre = (string) $request->postData('nouveauTitre');	
					
			if(in_array($ancienUrl, $this->nonSupprimable))
				$this->app->httpResponse()->redirect404();
			
			if($ancienUrl == $nouveauUrl && $ancienTitre == $nouveauTitre)
			{
				$this->app->user()->setFlash('Vous n\'avez rien changé !', 'ATTENTION');
				$this->app->httpResponse()->redirect('page-archivable');
			}
			else if(empty($nouveauUrl) || empty($nouveauTitre))
			{
				$this->app->user()->setFlash('Le titre ou l\'URL sont vides !', 'ERREUR');
				$this->app->httpResponse()->redirect('page-archivable');
			}
			else if(!preg_match('#^[a-zA-Z0-9_/-]+$#', $nouveauUrl))
			{
			    $this->app->user()->setFlash('Seuls les caractères alphanumériques, la barre (/) et les tirets (- et _) sont autorisés.', 'ERREUR');
			    $this->app->httpResponse()->redirect('page-archivable');
			}
			else
			{
				$page = null;
				$page = $manager->getUniqueByUrlAndTitre($ancienUrl, $ancienTitre);
				if($page === null || $page === false) // L'ancienne page n'existe pas
					$this->app->httpResponse()->redirect404();
				
				$pageExistante = false;
				$pageExistante = $manager->getUniqueByUrl($nouveauUrl);
				if($pageExistante !== false) // Le nouvel URL est déjà utilisé
				{
					$this->app->user()->setFlash('L\'URL spécifiée est déjà attribuée à une autre page.', 'ERREUR');
				    $this->app->httpResponse()->redirect('page-archivable');
				}
							
				$manager->updateUrlAndTitre($ancienUrl, $nouveauUrl, $ancienTitre, $nouveauTitre);
				
				$xml = new \DOMDocument;
				$cheminFichier = __DIR__ . '/../../../Frontend/Config/routes.xml';
				$xml->load($cheminFichier);
				
				$routes = $xml->getElementsByTagName('route');

				// On parcourt les routes du fichier XML.
				foreach ($routes as $route)
				{
					if($route->getAttribute('url') == ('/'.$ancienUrl.'(?:-([0-9]{4}-[0-9]{4}))?'))
					{
						$route->setAttribute('url', '/'.$nouveauUrl.'(?:-([0-9]{4}-[0-9]{4}))?');
						break;
					}
				}
				
				$xml->save($cheminFichier);					
				
				$this->app->user()->setFlash('Les pages ont été modifiées avec succès');
				$this->app->httpResponse()->redirect('page-archivable');	
				
			}
		}
		
		/*
		 * 
		 * MODIFICATION D'UNE SEULE PAGE
		 * 
		 */
		
		if(!$request->getExists('url'))
		    $this->app->httpResponse()->redirect404();
		$url = (string) $request->getData('url');
		
		//S'il y a l'archive, alors, on modifie UNE page
		if($request->getExists('archive')) 
		{
			$archive = (string) $request->getData('archive');
			$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			$manager = $this->managers->getManagerOf('PageArchivable');
			
			$page = $manager->getUniqueByUrlAndArchive($url, $archive);
			
			if($page === false)
				$this->app->httpResponse()->redirect404();			
		}
		else
		    $this->app->httpResponse()->redirect404();
		
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Modification d\'une page archivable');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');

		$this->page->addVar('action', 'modifier');
	}
	
	/**
	 * Fonction qui gère le formulaire pour l'ajout et l'édition des pages archivables ; en effet celui-ci est identique 
	 * pour les deux fonctions.
	 * 
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function processusFormulaire(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_archivable');
		$listeAnnees = $this->app->listeAnneesAllegee();
		$selection = false;
		$creation = false;
				
		if ($request->method() == 'POST') // formulaire reçu
		{
		    $url = $request->postData('url');
		    $titre = $request->postData('titre');
		    
		    if($request->postExists('creation'))
		    {
		        $creation = true;
		        /*if(empty($url) || empty($titre))
		        {
		            $this->app->user()->setFlash('Votre titre ou votre URL sont vides.', 'ERREUR');
		            $this->app->httpResponse()->redirect('page-archivable');
		        }*/
		        
		        $pageRecuperee = $this->managers->getManagerOf('PageArchivable')->getUniqueByUrl($url);
		        //$this->app->test($pageRecuperee);
		        
		        if($pageRecuperee !== false || !empty($pageRecuperee) || \Library\Entities\Utilitaire::lienRoute('/'.$url))
		        {
		            $this->app->user()->setFlash('Cette URL est déjà utilisée.', 'ERREUR');
		            $this->app->httpResponse()->redirect('page-archivable');
		        }
		    }
		    
			$page = new \Library\Entities\PageArchivable(
											array(
											'url' => $url,
											'titre' => $titre,
											'archive' => $request->postData('archive'),
											'texte' => $request->postData('texte'),
											'editeur' => $this->app()->user()->membre()
											)
											);
			
			
			if ($this->managers->getManagerOf('PageArchivable')->getUniqueByUrlAndArchive($page->url(), $page->archive()) !== false)
			{
				$page->setId($this->managers->getManagerOf('PageArchivable')->getId($page->url(), $page->archive()));
				$modifier = true;
			}
			else
			{
				$page->setId(-1);
				$modifier = false;
			}
		}
		else
		{
			// L'identifiant de la page est transmis si on veut la modifier.
			if ($request->getExists('url') && $request->getExists('archive'))
			{
			    $url = $request->getData('url');
			    $archive = $request->getData('archive');
			    $archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			    			    	
				$page = $this->managers->getManagerOf('PageArchivable')->getUniqueByUrlAndArchive($url, $archive);
				
				if($page === false)
				    $this->app->httpResponse()->redirect404();
				
				$page->setEditeur($this->app()->user()->membre());
				$modifier = true;
				$creation = false;
				
				$selection = $page->archive();
			}
			else if ($request->getExists('url')) // Création d'une page dans un groupe existant
			{
			    $url = $request->getData('url');
			    $manager = $this->managers->getManagerOf('PageArchivable');
			    			    			    	
			    $pages = $manager->getListeAnnees($url);

			    $listeAnnees = $this->app->listeAnneesAllegee();
						    
			    foreach ($pages as $page) // Supprime les années qui possèdent déjà une page
			    {
			        if(($position = array_search($page[0], $listeAnnees)) !== false)			         
			            unset($listeAnnees[$position]);
			    }
			    			    
			    if(empty($listeAnnees))
			    {
			        $this->app->user()->setFlash('Toutes les années sont déjà associées à une page.', 'ERREUR');
			        $this->app->httpResponse()->redirect('page-archivable');
			    }
			    
			    $titreEtUrl = $manager->getUniqueByUrl($url);
			    			    
			    if($pages === false || empty($pages))
			        $this->app->httpResponse()->redirect404();
			    
				$page = new \Library\Entities\PageArchivable;
				$page->setTitre($titreEtUrl->titre());
				$page->setUrl($titreEtUrl->url());
				$page->setEditeur($this->app()->user()->membre());
				$modifier = false;
				$creation = false;
			}
			else // Création d'une page et d'un groupe
			{
			    $page = new \Library\Entities\PageArchivable;
			    $page->setEditeur($this->app()->user()->membre());
			    $modifier = false;
			    $creation = true;			    
			}
		}

		$formBuilder = new \Library\FormBuilder\PageArchivableFormBuilder($page);
				
		$formBuilder->build(array_reverse($listeAnnees), $modifier, $selection, $creation);
		//$archive = null, $modification = false, $selectionne = false

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('PageArchivable'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash(!$modifier ? 'La page a bien été ajoutée !' : 'La page a bien été modifiée !');
			
			if(!$modifier) // Ajout de la page au fichier route
			{
				$xml = new \DOMDocument;
				$cheminFichier = __DIR__ . '/../../../Frontend/Config/routes.xml';
				$xml->load($cheminFichier);
				//<route url="/contacts" module="PageFixe" action="pageFixe" />

				$nouvellePage = $xml->createElement("route");
				$nouvellePage->setAttribute("url", '/' . $page->url().'(?:-([0-9]{4}-[0-9]{4}))?');
				$nouvellePage->setAttribute("module", "PageArchivable");
				$nouvellePage->setAttribute("action", "pageArchivable");
				
				$racine = $xml->getElementsByTagName("routes")->item(0);
				$racine->appendChild($nouvellePage);
				
				$xml->save($cheminFichier);
			}			
			
			$this->app->httpResponse()->redirect('page-archivable');
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('existe', $modifier);
	}
	
	/*-------------------------------------------------------------
	************************ SUPPRIMER ****************************
	-------------------------------------------------------------*/
	/**
	 * Méthode appelée lors de la suppression d'une page ou d'un groupe de page.
	 * La différentitation se fait grâce à la variable <b>GET archive</b>, si elle est présente, alors on ne supprime
	 * qu'une seule page.
	 * Si l'url fait partie de la variable <b>$nonSupprimable</b> alors la fonction renvoie un erreur 404.
	 * @param \Library\HTTPRequest $request
	 * @return void
	 */
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_archivable');
		
		$url = (string) $request->getData('url');
		if($request->getExists('archive')) //S'il y a l'archive, alors, on supprimer UNE page
		{
			$archive = (string) $request->getData('archive');
			$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			$manager = $this->managers->getManagerOf('PageArchivable');
			
			if($manager->countUrl($url) <= 1)
			{
				if(in_array($url, $this->nonSupprimable))
				{
					$this->app->user()->setFlash('Il reste qu\'une page dans ce groupe, elle ne peut être supprimée.', 'ERREUR');
					$this->app->httpResponse()->redirect('page-archivable');
				}
			}
			
			
			$page = $manager->getUniqueByUrlAndArchive($url, $archive);
			
			if($page === false)
				$this->app->httpResponse()->redirect404();
				
			$manager->deleteByUrlAndArchive($url, $archive);
			
			$this->app->user()->setFlash('La page a bien été supprimée !');
			$this->app->httpResponse()->redirect('page-archivable');
		}
		else // Sinon, on supprime toutes les pages
		{
			if(in_array($url, $this->nonSupprimable))
				$this->app->httpResponse()->redirect404();
			
			
		
		// Suppresion de la référence dans le document XML route	
			$page = $this->managers->getManagerOf('PageArchivable')->getUniqueByUrl($url);
			
			if($page === false)
				$this->app->httpResponse()->redirect404();
			
			
			
			$xml = new \DOMDocument;
			$cheminFichier = __DIR__ . '/../../../Frontend/Config/routes.xml';
			$xml->load($cheminFichier);
			
			$routes = $xml->getElementsByTagName('route');
			$pageASupprimer = null;

			// On parcourt les routes du fichier XML.
			foreach ($routes as $route)
			{
				if($route->getAttribute('url') == ('/'.$page->url().'(?:-([0-9]{4}-[0-9]{4}))?'))
				{
					$pageASupprimer = $route;
					break;
				}
			}
			
			if($pageASupprimer === null)
				$this->app->httpResponse()->redirect404();
			
			

			$racine = $xml->getElementsByTagName("routes")->item(0);
			$racine->removeChild($pageASupprimer);
			
			$xml->save($cheminFichier);
		
		// Suppression dans la BDD
			$this->managers->getManagerOf('PageArchivable')->deleteByUrl($url);
			$this->app->user()->setFlash('Les pages ont bien été supprimées !');
			$this->app->httpResponse()->redirect('page-archivable');
		}
	}
}