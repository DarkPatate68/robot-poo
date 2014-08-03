<?php
namespace Applications\Frontend\Modules\Equipe;
 
class EquipeController extends \Library\BackController
{
	/*********************************************************
	 ************ RECHERCHE LA PAGE À AFFICHER ***************
	 *********************************************************/
    /**
     * Accueil de la page des membres. Sans valeurs, elle affiche la liste des membres de l'année courante.
     * Si elle recoit une année, elle affiche la liste correspondante.
     * @param \Library\HTTPRequest $request
     * @return void
     */
	public function executeEquipe(\Library\HTTPRequest $request)
	{
		$manager = $this->managers->getManagerOf('Equipe');
		
		if($request->method() == 'POST') // Changement d'année demandée grâce à la liste déroulante
		{
			$annee = (string) $request->postData('annee');
						
			$annee = str_ireplace("/", "-", $annee);
			$this->app->httpResponse()->redirect('equipe-'.$annee);
		}
		else if($request->getExists('annee')) // Affichage de l'équipe d'une certaine année
		{
			$annee = str_ireplace("-", "/", (string) $request->getData('annee'));
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
	
	/**
	 * Fonction affichant la page de présentation d'un membre
	 * @param \Library\HTTPRequest $request
	 * @return void
	 */
	public function executeMembre(\Library\HTTPRequest $request)
	{
	    
	}
}