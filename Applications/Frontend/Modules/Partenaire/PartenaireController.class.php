<?php
namespace Applications\Frontend\Modules\Partenaire;
 
class PartenaireController extends \Library\BackController
{
	/**************************************************
	 ************ LISTE DES PARTENAIRES ***************
	 **************************************************/
	public function executeIndex(\Library\HTTPRequest $request)
	{
		// On ajoute une définition pour le titre.
		$this->page->addVar('title', 'Nos partenaires');
		
		// Design supplémentaire pour les partenaires.
		$this->page->addVar('design', 'partenaire.css');		
		// On ajoute une catégorie pour le style de la page
		$this->page->addVar('categorieCSS', 'partenaire');

		// On récupère le manager des partenaires.
		$manager = $this->managers->getManagerOf('Partenaire');

		// Récupère la liste des partenaires
		$listePartenaire = $manager->getListe();		
		
		// On ajoute la variable $listePartenaire à la vue.
		$this->page->addVar('listePartenaire', $listePartenaire);
		$this->page->addVar('user', $this->app->user());
	}
}