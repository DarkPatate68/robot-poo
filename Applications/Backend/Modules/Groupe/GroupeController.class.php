<?php
namespace Applications\Backend\Modules\Groupe;

/**
 * @brief Controlleur de l'archivage.
 * @details Permet d'archiver le site et de changer d'année, mais également d'afficher toutes les années présentes.
 * @author Siméon
 * @date 27/08/2014
 * @version 1.0.0
 *
 */
class GroupeController extends \Library\BackController
{
	    	
	/**
	 * Affichage de la liste des équipes
	 * @param \Library\HTTPRequest $request Objet de requête HTTP nécessaire pour récupérer les variables POST/GET
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_groupe');
		
		$this->page->addVar('title', 'Gestion des groupes du site');
		$manager = $this->managers->getManagerOf('Groupe');
		$listeGroupe = $manager->getListe();
		$listeChampBrut = $manager->getListeChamp();
		$listeChamp = array();
		
		$i=-1;
		foreach($listeChampBrut as $champ)
		{
			$i++;
			if($i < 2) // On ne prend pas les champs *id* et *nom*
				continue;
				
			$listeChamp[] = $champ[0];
		}
		
		if($request->method() == 'POST')
		{
			$listePost = $_POST;
			ksort($listePost);
			
			$listeChampEtendue = $listeChamp;
			array_unshift($listeChampEtendue, "id", "nom");
			
						
			$listeRecue = array();
			
			$idPrecedent = -1;
			
			//$this->app->test($listePost);
			
			foreach ($listePost as $post => $val)
			{
				$id = explode('_', $post, 2);
					$id = $id[0];
				$champ = explode('_', $post, 2);
					$champ = $champ[1];
				
				if($id != $idPrecedent)
				{
					$idPrecedent = $id;
					foreach($listeChampEtendue as $champ2)
						$listeRecue[$id][$champ2] = 0;
					$listeRecue[$id]['id'] = $id;
				}
				
				if($champ == 'nom')
				{
					$listeRecue[$id][$champ] = (string) $val;
				}
				else if($champ == 'president')
					continue;
				else
					$listeRecue[$id][$champ] = 1;
			}
			
			unset($listeRecue['1']); // Supprime le groupe Président qui ne peut être modifié
			//$this->app->test($listeRecue);
			
			$manager->miseAJour($listeRecue, $listeChamp);
			
			$this->app->user()->setFlash('Les groupes ont été mis-à-jour.');
			$this->app->httpResponse()->redirect('groupe');
		}

				
		$this->page->addVar('listeGroupe', $listeGroupe);
		$this->page->addVar('listeChamp', $listeChamp);
		$this->page->addVar('design', 'groupe.css');		
	}
	
	public function executeAjouter(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_groupe');
		
		if(!$request->getExists('nom'))
			$this->app->httpResponse()->redirect404();
		
		$nom = htmlspecialchars((string) $request->getData('nom'));		
		$this->managers->getManagerOf('Groupe')->add($nom);
		
		$this->app->user()->setFlash('Le groupe a été ajouté.');
		$this->app->httpResponse()->redirect('groupe');
	}
	
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_groupe');
	
		if(!$request->getExists('id'))
			$this->app->httpResponse()->redirect404();
	
		$id = (int) $request->getData('id');
		if($id <= 2)
			$this->app->httpResponse()->redirect404();
		
		$this->managers->getManagerOf('Groupe')->delete($id);
		
		$this->managers->getManagerOf('Membre')->declasser($id);
	
		$this->app->user()->setFlash('Le groupe a été supprimé.');
		$this->app->httpResponse()->redirect('groupe');
	}
	
	public function executeChangerMembre(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_groupe');
		$manager = $this->managers->getManagerOf('Membre');
		
		if($request->postExists('groupe') && $request->postExists('id'))
		{
			$groupe = (int) $request->postData('groupe');
			$id = (int) $request->postData('id');
			
			if($groupe == 1 && $this->app->user()->membre()->groupe() != 1)
			{
				$this->app->user()->setFlash('Seul le président peut se changer de groupe.', 'ERREUR');
				$this->app->httpResponse()->redirect403();
			}
			else if($groupe == 1 && $this->app->user()->membre()->groupe() == 1)
			{
				$this->app->user()->setFlash('Le changement de président se fait uniquement par passation.', 'ATTENTION');
				$this->app->httpResponse()->redirect('passation');
			}
			
			$membre = $manager->getUnique($id);
			
			if($membre === false)
				$this->app->httpResponse()->redirect404();
			
			if($membre->groupe() == 1 && $this->app->user()->membre()->groupe() != 1)
			{
				$this->app->user()->setFlash('Seul le président peut se changer de groupe.', 'ERREUR');
				$this->app->httpResponse()->redirect403();
			}
			else if($membre->groupe() == 1 && $this->app->user()->membre()->groupe() == 1)
			{
				$this->app->user()->setFlash('Le changement de président se fait uniquement par passation.', 'ATTENTION');
				$this->app->httpResponse()->redirect('passation');
			}

			$manager->updateGroupe($id, $groupe);
			$this->app->user()->setFlash('Changement effectué avec succès.');
			$this->app->httpResponse()->redirect('membre-liste');
		}
		
		if(!$request->getExists('id'))
			$this->app->httpResponse()->redirect404();
		
		$id = (int) $request->getData('id');
		
		if($id == 0)
			$this->app->httpResponse()->redirect404();
		
		
		$membre = $manager->getUnique($id);
		
		if($membre === false)
			$this->app->httpResponse()->redirect404();
		
		if($membre->groupe() == 1 && $this->app->user()->membre()->groupe() != 1)
		{
			$this->app->user()->setFlash('Seul le président peut se changer de groupe.', 'ERREUR');
			$this->app->httpResponse()->redirect403();
		}
		else if($membre->groupe() == 1 && $this->app->user()->membre()->groupe() == 1)
		{
			$this->app->user()->setFlash('Le changement de président se fait uniquement par passation.', 'ATTENTION');
			$this->app->httpResponse()->redirect('passation');
		}
		
		$this->page->addVar('title', 'Changement de groupe de ' . $membre['pseudo']);
		$this->page->addVar('groupeMbr', $membre['groupe']);
		$this->page->addVar('idMbr', $membre['id']);
		$this->page->addVar('listeGroupe', $this->managers->getManagerOf('Groupe')->getListe());
	}
}