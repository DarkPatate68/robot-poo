<?php
namespace Applications\Backend\Modules\Archivage;

/**
 * @brief Controlleur de l'archivage.
 * @details Permet d'archiver le site et de changer d'année, mais également d'afficher toutes les années présentes.
 * @author Siméon
 * @date 27/08/2014
 * @version 1.0.0
 *
 */
class ArchivageController extends \Library\BackController
{
	    	
	/**
	 * Affichage de la liste des équipes
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('archivage');
		
		$this->page->addVar('title', 'Archivage du site');
		
		$annees = explode('/', $this->app->anneeEnCours());
		$suivante = ((string) (((int) $annees[0])+1)) . '/' . ((string) (((int) $annees[1])+1));
		
		if($request->postExists('confirmation'))
		{
			$this->managers->getManagerOf('Archive')->add($suivante);
			$this->managers->getManagerOf('News')->archivage();
			
			$this->app->user()->setFlash('Archivage effectué.');
			$this->app->httpResponse()->redirect('archivage');
		}

		
		$this->page->addVar('listeAnnees', $this->app->listeAnnees());
		$this->page->addVar('courante', $this->app->anneeEnCours());
		
		$this->page->addVar('suivante', $suivante);
		$this->page->addVar('design', 'archivage.css');
		
	}
}