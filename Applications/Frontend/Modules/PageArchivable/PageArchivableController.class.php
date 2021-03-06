<?php
namespace Applications\Frontend\Modules\PageArchivable;
 
class PageArchivableController extends \Library\BackController
{
	/*********************************************************
	 ************ RECHERCHE LA PAGE À AFFICHER ***************
	 *********************************************************/
    /**
     * Accueil des pages archivables. Affiche la bonne page en fonction de la variable GET d'archive.
     * Si elle n'existe pas, le site affiche la page la plus récente
     * @param \Library\HTTPRequest $request
     * @return void
     */
	public function executePageArchivable(\Library\HTTPRequest $request)
	{
		$urlMinimal = str_replace($GLOBALS['PREFIXE'] . '/', '', $request->requestURI()); // Url de la page enregistrée en BDD auquel il faut enlever l'année
		$manager = $this->managers->getManagerOf('PageArchivable');
		
		if($request->method() == 'POST')
		{
			$annee = (string) $request->postData('annee');
			$url = (string) $request->postData('url');
			
			$annee = str_ireplace("/", "-", $annee);
			$this->app->httpResponse()->redirect($url.'-'.$annee);
		}
		else if($request->getExists('annee'))
		{
			$annee = str_ireplace("-", "/", (string) $request->getData('annee'));
			$urlMinimal = str_replace('-'.(string) $request->getData('annee'), '', $urlMinimal);
		}
		else
		{
		    $listeAnnees = $manager->getListeAnnees($urlMinimal);
		    if(!$listeAnnees) // Si vide
		        $this->app->httpResponse()->redirect404();
		    $annee = $listeAnnees[0][0]; // Prend la page la plus récente
		}
						
		$page = $manager->getUniqueByUrlAndArchive($urlMinimal, $annee, $this->managers->getManagerOf('Membre'));
		
		$ans = $manager->getListeAnnees($urlMinimal);
		$annees = array();
		for($i = 0 ; $i < count($ans) ; $i++)
		{
			$annees[] = $ans[$i]['archive'];
		}
		
		$this->page->addVar('annees', $annees);
		$this->page->addVar('url', $urlMinimal);
		$this->page->addVar('anneEnCours', $this->app()->anneeEnCours());
		
			
		if(!$page)
			$this->app->httpResponse()->redirect404();
		
		$this->page->addVar('title', \Library\Entities\FormatageTexte::monoLigne($page->titre()));
		$this->page->addVar('design', 'pageArchivable.css');		
		$this->page->addVar('categorieCSS', 'accueil');

		
		// On ajoute les variables $page et $user à la vue.
		$this->page->addVar('page', $page);
		$this->page->addVar('user', $this->app->user());		
	}
}