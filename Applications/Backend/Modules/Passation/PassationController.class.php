<?php
namespace Applications\Backend\Modules\Passation;

/**
 * @brief Controlleur de la passation.
 * @details Permet au président de transférer les droits suprême du site à son successeur.
 * @author Siméon
 * @date 02/09/2014
 * @version 1.0.0
 *
 */
class PassationController extends \Library\BackController
{
	    	
	/**
	 * Affichage de la page de passation
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('president');		
		$this->page->addVar('title', 'Passation des pouvoirs');
		
		if($request->postExists('membre') && $request->postExists('groupe'))
		{
			$membre = (string) $request->postData('membre');
			$groupe = (int) $request->postData('groupe');
			$drapeauErreur = false;
			$membreObjet = false;
			
			
			if($groupe == 1)
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Vous ne pouvez pas choisir <em>Président</em> comme nouveau groupe !', 'ERREUR');
			}
			else if($this->managers->getManagerOf('Groupe')->existe($groupe) === false)
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce groupe n\'existe pas.', 'ERREUR');
			}
			
			if(empty($membre))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Veuillez choisir un membre', 'ERREUR');
			}
			else
				$membreObjet = $this->managers->getManagerOf('Membre')->getUniqueByPseudo($membre);
				
			if(!empty($membre) && ($membreObjet === false || $membreObjet->id() == 0))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce membre n\'existe pas.', 'ERREUR');
			}
			else if(!empty($membre) && $membreObjet->id() === $this->app->user()->membre()->id())
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Mais c\'est vous !', 'ERREUR');
			}
			
						
			if(!$drapeauErreur)
			{
				$this->managers->getManagerOf('Membre')->passation($membreObjet->id(), $this->app->user()->membre()->id(), $groupe);
				
				$this->app->user()->setFlash('Passation effectué avec succès.');
				$this->app->httpResponse()->redirect('passation-terminee');
			}
		}
		
				
		$this->page->addVar('listeGroupe', $this->managers->getManagerOf('Groupe')->getListe());
		$this->page->addVar('design', 'passation.css');		
	}
	
	/**
	 * Affiche simplement un message pour l'ancien président
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 */
	public function executePassationTerminee(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Ce n\'est qu\'un au revoir…');
		$this->page->addVar('design', 'passation.css');
	}
}