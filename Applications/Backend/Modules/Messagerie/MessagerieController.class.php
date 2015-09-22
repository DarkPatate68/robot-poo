<?php
namespace Applications\Backend\Modules\Messagerie;

/**
 * @brief Controlleur de la messagerie.
 * @details Controlleur de la messagerie, avec affiche de la boite de reception, d'envoi, de la corbeille et de la liste des contacts.
 * @author Siméon
 * @date 06/06/2015
 * @version 1.0.0
 *
 */
class MessagerieController extends \Library\BackController
{	
	/**
	 * Affichage de la page dynamique de la messagerie
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('messagerie');
		
		$this->page->addVar('title', 'Messagerie');

		$managerMailreception = $this->managers->getManagerOf('MailReception');
		$managerMailEnvoi = $this->managers->getManagerOf('MailEnvoi');
		$managerContact = $this->managers->getManagerOf('MailContact');
		
		if($request->getExists("page"))
		{
			$page = $request->getData("page");
			$_SESSION['page_messagerie'] = $page;
		}		
							
		$this->page->addVar('design', 'messagerie.css');
		$this->page->addVar('script', 'messagerie.js');
	}
	
	
	/**
	 * Méthode appelée pour afficher une liste brute des messages
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeReception(\Library\HTTPRequest $request)
	{
		$this->viewRight('messagerie');
		$this->page()->setLayout("vide.php");		
		
		$managerMailreception = $this->managers->getManagerOf('MailReception');
		$managerMailEnvoi = $this->managers->getManagerOf('MailEnvoi');
		$managerContact = $this->managers->getManagerOf('MailContact');
		
		
		$listeMail = $managerMailreception->getListe(0, 50);
						
		$this->page->addVar('listeMail', $listeMail);
		$this->page->addVar('design', 'messagerie.css');
		//$this->page->addVar('script', 'messagerie-iframe.js');
	}
	
	/**
	 * Fonction appelée lors de la lecture d'un mail
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeLire(\Library\HTTPRequest $request)
	{
		$this->viewRight('messagerie');
		//$this->page()->setLayout("vide.php");		
		
		$managerMailreception = $this->managers->getManagerOf('MailReception');
		$managerMailEnvoi = $this->managers->getManagerOf('MailEnvoi');
		$managerContact = $this->managers->getManagerOf('MailContact');
		
		
		$mail = $managerMailreception->getUnique($request->getData("id"));
		if(!$mail->lu())
			$managerMailreception->lu($mail->id());
			
		$this->page->addVar('title', $mail->objet());
		$this->page->addVar('mail', $mail);
		$this->page->addVar('design', 'messagerie.css');
	}
	
	/**
	 * Fonction appelée lors de l'écriture d'un mail
	 * La différentiation se fait grâce aux variables GET qui n'existent pas si l'on modifie un groupe : des variables POST sont utilisées.
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeEcrire(\Library\HTTPRequest $request)
	{
		$this->viewRight('messagerie');
		//$this->page()->setLayout("vide.php");
	
		$managerMailreception = $this->managers->getManagerOf('MailReception');
		$managerMailEnvoi = $this->managers->getManagerOf('MailEnvoi');
		$managerContact = $this->managers->getManagerOf('MailContact');
	
		if($request->method() == 'POST')
		{
			
		}
		else 
		{
			if($request->getExists("id"))
			{	
				$mail = $managerMailreception->getUnique($request->getData("id"));
					$reponse =  '&#10;&#10;--------------------------&#10;';
					$reponse .= 'De ' . $mail->expediteur() . '&#10;';
					$reponse .= 'Envoyé le ' . $mail->date()->format('d/m/Y&\nb\sp;à&\nb\sp;H:i') . '&#10;';
					$reponse .= 'Objet : ' . $mail->objet() . '&#10;&#10;';
					$reponse .= $mail->texte();
				$mail->setTexte($reponse);
				
				if(!stripos($mail->objet(), 'RE'))
					$mail->setObjet('RE : ' . $mail->objet());
			}
			else
				$mail = new \Library\Entities\MailReception;
			
			$formBuilder = new \Library\FormBuilder\MessagerieEcrireFormBuilder($mail);
			
			$idTextEdit = $formBuilder->build();						
			$form = $formBuilder->form();	
			
			$this->page->addVar('form', $form->createView());		
			$this->page->addVar('title', $mail->objet());
			$this->page->addVar('mail', $mail);
			$this->page->addVar('design', 'messagerie.css');
		}
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
		$modifierFichierRoute = false;
		
				
		if ($request->method() == 'POST') // formulaire reçu
		{
		    $url = $request->postData('url');
		    $titre = $request->postData('titre');
		    
		    if($request->postExists('creation'))
		    {
		        $creation = true;
		        $modifierFichierRoute = true;
		       
		        /*if(empty($url) || empty($titre))
		        {
		            $this->app->user()->setFlash('Votre titre ou votre URL sont vides.', 'ERREUR');
		            $this->app->httpResponse()->redirect('page-archivable');
		        }*/
		        
		        $pageRecuperee = $this->managers->getManagerOf('PageArchivable')->getUniqueByUrl($url);
		        //$this->app->test($pageRecuperee);
		        
		        if($pageRecuperee !== false || !empty($pageRecuperee) || \Library\Entities\Utilitaire::lienRoute('/'.$url.'(?:-([0-9]{4}-[0-9]{4}))?')  || \Library\Entities\Utilitaire::lienRoute('/'.$url))
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
			    $modifierFichierRoute = true;
			}
		}

		$formBuilder = new \Library\FormBuilder\PageArchivableFormBuilder($page);
				
		$idTextEdit = $formBuilder->build(array_reverse($listeAnnees), $modifier, $selection, $creation);
		//$archive = null, $modification = false, $selectionne = false

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('PageArchivable'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash(!$modifier ? 'La page a bien été ajoutée !' : 'La page a bien été modifiée !');
			
			if($modifierFichierRoute) // Ajout de la page au fichier route
			{
				$xml = new \DOMDocument;
				$cheminFichier = __DIR__ . '/../../../Frontend/Config/routes.xml';
				$xml->load($cheminFichier);
				//<route url="/contacts" module="PageFixe" action="pageFixe" />

				$nouvellePage = $xml->createElement("route");
				$nouvellePage->setAttribute("url", '/' . $page->url().'(?:-([0-9]{4}-[0-9]{4}))?');
				$nouvellePage->setAttribute("module", "PageArchivable");
				$nouvellePage->setAttribute("action", "pageArchivable");
				$nouvellePage->setAttribute("vars", "annee");//vars="annee"
				
				$racine = $xml->getElementsByTagName("routes")->item(0);
				$racine->appendChild($nouvellePage);
				
				$xml->save($cheminFichier);
			}			
			
			$this->app->httpResponse()->redirect('page-archivable');
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('existe', $modifier);
		
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
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