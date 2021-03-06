<?php
namespace Applications\Backend\Modules\PageFixe;
 
class PageFixeController extends \Library\BackController
{
	// *****************************************
	// ******** Gestion des pages fixes ********
	// *****************************************
	
	// Affichage de la liste des news avec pagination.
	// Permet d'avoir une vue globale.
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_fixe');
		
		$this->page->addVar('title', 'Gestion des pages non-archivables');

		$manager = $this->managers->getManagerOf('PageFixe');
		
		$listePage = $manager->getListe($this->managers->getManagerOf('Membre'));
		
		
		if(empty($listePage))
			$this->app->httpResponse()->redirect404();
		
		$this->page->addVar('listePage', $listePage);
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
	}
	
	// Permet d'ajouter une nouvelle page fixe au site
	public function executeAjouter(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_fixe');
			
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Ajout d\'une page non-archivable');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
		$this->page->addVar('action', 'ajouter');
	}
	
	// Permet de modifier une news
	public function executeModifier(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_fixe');
		
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Modification d\'une page non-archivable');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
		$this->page->addVar('action', 'modifier');
	}
	
	// Fonction qui gère le formulaire pour l'ajout et l'édition des news ; en effet celui-ci est identique pour les deux fonctions.
	public function processusFormulaire(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_fixe');
		$this->page->addVar('categorieCSS', 'accueil');
		$ancienUrl = '';
		
		if ($request->method() == 'POST')
		{
			
			
			$page = new \Library\Entities\PageFixe(
											array(
											'url' => $request->postData('url'),
											'titre' => $request->postData('titre'),
											'texte' => $request->postData('texte'),
											'editeur' => $this->app()->user()->membre()
											)
											);

			if ($request->getExists('id'))
			{
				if($request->getData('id') == 4 && $this->app->user()->membre()->groupe() != 1)
					$this->app->httpResponse()->redirect403();
				
				$page->setId($request->getData('id'));
				$ancienUrl = $this->managers->getManagerOf('PageFixe')->getUnique($request->getData('id'))->url();
				if(\Library\Entities\Utilitaire::lienRoute('/'.$page->url(), 'Frontend', '/'.$ancienUrl) || in_array($page->url(), $this->managers->getManagerOf('PageArchivable')->getListeUrl()))
					$page->setUrl('');
				$modifier = true;
			}
			else
			{
				$page->setId(-1);
				$modifier = false;
				if(\Library\Entities\Utilitaire::lienRoute('/'.$page->url()) || in_array($page->url(), $this->managers->getManagerOf('PageArchivable')->getListeUrl()))
					$page->setUrl('');
			}
		}
		else
		{
			// L'identifiant de la page est transmis si on veut la modifier.
			if ($request->getExists('id'))
			{
				if($request->getData('id') == 4 && $this->app->user()->membre()->groupe() != 1)
					$this->app->httpResponse()->redirect403();
				$page = $this->managers->getManagerOf('PageFixe')->getUnique($request->getData('id'));
				$page->setEditeur($this->app()->user()->membre());
				$ancienUrl = $page->url();
				$modifier = true;
			}
			else
			{
				$page = new \Library\Entities\PageFixe;
				$page->setEditeur($this->app()->user()->membre());
				$modifier = false;
			}
		}

		$formBuilder = new \Library\FormBuilder\PageFixeFormBuilder($page);
		$idTextEdit = $formBuilder->build(/*$modifier*/);

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('PageFixe'), $request);

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
				$nouvellePage->setAttribute("url", '/' . $page->url());
				$nouvellePage->setAttribute("module", "PageFixe");
				$nouvellePage->setAttribute("action", "pageFixe");
				
				$racine = $xml->getElementsByTagName("routes")->item(0);
				$racine->appendChild($nouvellePage);
				
				$xml->save($cheminFichier);
			}
			else
			{
				$xml = new \DOMDocument;
				$cheminFichier = __DIR__ . '/../../../Frontend/Config/routes.xml';
				$xml->load($cheminFichier);
				
				$routes = $xml->getElementsByTagName('route');
				
				// On parcourt les routes du fichier XML.
				foreach ($routes as $route)
				{
					if($route->getAttribute('url') == ('/'.$ancienUrl))
					{
						$route->setAttribute('url', '/'.$page->url());
						break;
					}
				}
				
				$xml->save($cheminFichier);
			}			
			
			$this->app->httpResponse()->redirect('page-fixe');
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('existe', $modifier);
		
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
	}
	
	// Fonction de suppression DÉFINITIVE d'une page
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_page_fixe');
		$this->page->addVar('categorieCSS', 'accueil');
		
		$id = (int) $request->getData('id');
		
		if($id <= 3) // Les trois premières pages ne peuvent pas être supprimées
			$this->app->httpResponse()->redirect404();
			
		// Suppresion de la référence dans le document XML route	
			$page = $this->managers->getManagerOf('PageFixe')->getUnique($id);
			
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
				if($route->getAttribute('url') == ('/'.$page->url()))
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
			$this->managers->getManagerOf('PageFixe')->delete($id);
			$this->app->user()->setFlash('La page a bien été supprimée !');
			$this->app->httpResponse()->redirect('page-fixe');
	}
}