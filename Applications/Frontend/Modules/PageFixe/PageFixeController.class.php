<?php
namespace Applications\Frontend\Modules\PageFixe;
 
class PageFixeController extends \Library\BackController
{
	/*********************************************************
	 ************ RECHERCHE LA PAGE À AFFICHER ***************
	 *********************************************************/
	public function executePageFixe(\Library\HTTPRequest $request)
	{
		$urlMinimal = str_replace($GLOBALS['PREFIXE'] . '/', '', $request->requestURI());
		
		$manager = $this->managers->getManagerOf('PageFixe');
		$page = $manager->getUniqueByUrl($urlMinimal, $this->managers->getManagerOf('Membre'));
		
		if(!$page)
			$this->app->httpResponse()->redirect404();
		
		$this->page->addVar('title', \Library\Entities\FormatageTexte::monoLigne($page->titre()));
		$this->page->addVar('design', 'pageFixe.css');		
		$this->page->addVar('categorieCSS', 'accueil');

		
		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar('page', $page);
		$this->page->addVar('user', $this->app->user());		
	}
}