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
	protected $fonction = array(
							'president' => '1_Président',
							'vice-president' => '2_Vice-président',
							'tresorier' => '3_Trésorier',
							'secretaire' => '4_Secrétaire',
							'webmaster' => '5_Webmaster',
							'membre' => '99_Membre'
							);
    	
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
		
		if($request->postExists('membre') && $request->postExists('annee') && $request->postExists('classe') && $request->postExists('description') && $request->postExists('fonction'))
		{
			$membre = (string) $request->postData('membre');
			$annee = (string) $request->postData('annee');
			$classe = (string) $request->postData('classe');
			$description = (string) $request->postData('description');
			$fonction = (string) $request->postData('fonction');
			$photo = '0'; // 0 = photo de base
			
			if(array_key_exists($fonction, $this->fonction))
				$fonction = $this->fonction[$fonction];
			else
				$fonction = $this->fonction['membre'];
			
			$drapeauErreur = false;
			
			if(empty($classe))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Veuilliez renseigner la classe.', 'ERREUR');
			}
			
			if(!empty($membre))
				$membreObjet = $this->managers->getManagerOf('Membre')->getUniqueByPseudo($membre);
			
			if(!empty($membre) && $membreObjet === false)
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce membre n\'existe pas.', 'ERREUR');
			}
			else if(!empty($membre) && $this->managers->getManagerOf('Equipe')->getUniqueByMembreAndArchive($membreObjet['id'], $annee) !== false)
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Ce membre fait déjà partie de cette équipe.', 'ERREUR');
			}
			
			if(isset($_FILES['photo']) AND ($_FILES['photo']['error'] != 0 && $_FILES['photo']['error'] != 4))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Erreur dans l\'envoie de l\'image : ' . \Library\Entities\Utilitaire::codeErreurFichier($_FILES['photo']['error']), 'ERREUR');
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
														'fonction' => $fonction,
														'description' => $description,
														'photo' => $photo));
				
				$id = $this->managers->getManagerOf('Equipe')->add($equipe);
								
				if (isset($_FILES['photo']) AND $_FILES['photo']['error'] == 0)
				{
					$retourFichier = \Library\Entities\Utilitaire::traitementFichier($_FILES['photo'], array('jpeg', 'jpg', 'png'), 'images/equipe/' . (string) $id, 1048576, 150); // Taille de l'image
					if(in_array($retourFichier, array('jpeg', 'jpg', 'png')))
					{
						$this->managers->getManagerOf('Equipe')->updatePhoto($id, ((string) $id) . '.' . $retourFichier);
						
						$this->app->user()->setFlash('Le membre a été ajouté avec succès à l\'équipe.');
						$this->app->httpResponse()->redirect('equipe');
					}
					else
					{
						switch ($retourFichier)
						{
							case 'ERR_POIDS':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car elle était trop lourde (> 1 Mio).', 'ATTENTION');
								break;
							case 'ERR_EXTENSION':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car l\'extension n\'était pas bonne (uniquement jpeg et png).', 'ATTENTION');
								break;
							case 'ERR_REDIM':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée, une erreur est survenue lors du redimensionnement.', 'ATTENTION');
								break;
							default:
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée pour une raison inconnue (' . (string) $retourFichier . ').', 'ATTENTION');
								break;
						}
						$this->app->httpResponse()->redirect('equipe');
					}
						
				}
				else
				{
					$this->app->user()->setFlash('Le membre a été ajouté avec succès à l\'équipe.');
					$this->app->httpResponse()->redirect('equipe');
				}
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
		    	$this->app->user()->setFlash('Toutes les années sont déjà associées à une équipe.', 'ERREUR');
		    	$this->app->httpResponse()->redirect('equipe');
		    }
		    
		    $this->page->addVar('listeAnnee', $listeAnneesSite);
		}
			
		//$this->processusFormulaire($request);
		if(isset($description))
			$this->page->addVar('description', $description);
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
		$this->viewRight('mod_equipe');
		$manager = $this->managers->getManagerOf('Equipe');
		
		if($request->postExists('membre') && $request->postExists('annee') && $request->postExists('classe') && $request->postExists('description') && $request->postExists('fonction') && $request->postExists('id'))
		{
			$membre = (string) $request->postData('membre');
			$annee = (string) $request->postData('annee');
			$classe = (string) $request->postData('classe');
			$description = (string) $request->postData('description');
			$fonction = (string) $request->postData('fonction');
			$id = (int) $request->postData('id');
			$photo = '0'; // 0 = photo de base
			
							
			if(array_key_exists($fonction, $this->fonction))
				$fonction = $this->fonction[$fonction];
			else
				$fonction = $this->fonction['membre'];
			
				
			$drapeauErreur = false;
				
			if(empty($classe))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Veuilliez renseigner la classe.', 'ERREUR');
			}
				
			if(isset($_FILES['photo']) AND ($_FILES['photo']['error'] != 0 && $_FILES['photo']['error'] != 4))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Erreur dans l\'envoie de l\'image : ' . \Library\Entities\Utilitaire::codeErreurFichier($_FILES['photo']['error']), 'ERREUR');
			}
				
				
			if(!$drapeauErreur)
			{
				$equipe = $manager->getUnique($id, $this->managers->getManagerOf('Membre'));
				
				if($equipe->membre()->id() != '0') // Si le patronyme du champ ne correspond pas à celui de la BDD, c'est que qqn à forcé le formulaire
				{
					$patronyme = $equipe['membre']['prenom'] . ' ' . $equipe['membre']['nom'];
					if($patronyme != $membre)
					{
						$this->app->user()->setFlash('Une erreur est survenue.', 'ERREUR');
						$this->app->httpResponse()->redirect('equipe');
					}
				}
			
				$equipe->setClasse($classe);
				$equipe->setFonction($fonction);
				$equipe->setDescription($description);
				
				if($request->postExists('suppr_photo') && $equipe->photo() != '0') // Si la case supprimé la photo est coché ET qu'il y avait une photo avant, alors on la supprime.
				{
					unlink('images/equipe/' . $equipe->photo());
					$equipe->setPhoto('0');
					
					$manager->update($equipe);
					$this->app->user()->setFlash('Le membre a été modifié avec succès.');
					$this->app->httpResponse()->redirect('equipe');
				}				
				else if (isset($_FILES['photo']) AND $_FILES['photo']['error'] == 0 && !$request->postExists('suppr_photo'))
				{
					$retourFichier = \Library\Entities\Utilitaire::traitementFichier($_FILES['photo'], array('jpeg', 'jpg', 'png'), 'images/equipe/' . (string) $id, 1048576, 150); // Taille de l'image
					if(in_array($retourFichier, array('jpeg', 'jpg', 'png')))
					{
						$equipe->setPhoto(((string) $id) . '.' . $retourFichier);
						$manager->update($equipe);
			
						$this->app->user()->setFlash('Le membre a été ajouté avec succès à l\'équipe.');
						$this->app->httpResponse()->redirect('equipe');
					}
					else
					{
						$equipe->setPhoto('0');
						$manager->update($equipe);
						
						switch ($retourFichier)
						{
							case 'ERR_POIDS':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car elle était trop lourde (> 1 Mio).', 'ATTENTION');
								break;
							case 'ERR_EXTENSION':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car l\'extension n\'était pas bonne (uniquement jpeg et png).', 'ATTENTION');
								break;
							case 'ERR_REDIM':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée, une erreur est survenue lors du redimensionnement.', 'ATTENTION');
								break;
							default:
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée pour une raison inconnue (' . (string) $retourFichier . ').', 'ATTENTION');
								break;
						}
						$this->app->httpResponse()->redirect('equipe');
					}
			
				}
				else
				{
					$manager->update($equipe);					
					$this->app->user()->setFlash('Le membre a été modifié avec succès.');
					$this->app->httpResponse()->redirect('equipe');
				}
			}
		}
		
		if(!$request->getExists('id')) //S'il y a l'id alors on modifie le membre
			$this->app->httpResponse()->redirect404();
		
		$id = (int) $request->getData('id');
		$membre = $this->managers->getManagerOf('Equipe')->getUnique($id, $this->managers->getManagerOf('Membre'));
		
		if($membre === false || $membre === null)
			$this->app->httpResponse()->redirect404();
			
			$noms = array();
			if($membre['membre']['id'] !== '0')
				$nom = $membre['membre']['prenom'] . ' ' . $membre['membre']['nom'];
			else
			{
				if(preg_match('#^\[\[([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)@([ a-zA-ZéèêëàâäôöùûüœæòóîïìíÿýáñÉÈÊËÀÂÄÔÖÙÛÜŒÆÒÓÎÏÍÌŸÝÁÑçÇ-]+)\]\](.*)$#', $membre->description(), $noms) !== false)
				{
					$nom = $noms[1] . ' ' . $noms[2];
				}
				else
				{
					$nom = 'Membre anonyme';
				}
			}
						
		$this->page->addVar('title', 'Modification d\'un membre');
		$this->page->addVar('membre', $membre);
		$this->page->addVar('nom', $nom);		
		$this->page->addVar('fonction', str_ireplace('é', 'e', strtolower(explode('_', $membre->fonction(), 2)[1])));
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'pageEquipe-membre.css');
		
		
	}
	
	
	/*-------------------------------------------------------------
	************************ SUPPRIMER ****************************
	-------------------------------------------------------------*/
	/**
	 * Méthode appelée lors de la suppression d'un (des) membre(s) d'une équipe.
	 * @param \Library\HTTPRequest $request
	 * @return void
	 */
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_equipe');
		
		if($request->method() == 'POST')
		{
			$idASuppr = array();
			foreach($_POST as $key => $val)
			{
				if(preg_match("#^membre_[0-9]+$#", $key))
				{
					$idASuppr[] = (int) explode('_', $key)[1];
				}
			}
			
			if(empty($idASuppr))
			{
				$this->app->user()->setFlash('Aucune personne n\'a été sélectionnée.', 'ATTENTION');
				$this->app->httpResponse()->redirect('equipe');
			}
			
			$manager = $this->managers->getManagerOf('Equipe');
			
			foreach ($idASuppr as $id)
			{
				$i++;
				if(!$manager->delete($id))
				{
					$this->app->user()->setFlash('Une erreur est survenue lors de la suppression d\'un membre.', 'ERREUR');
					$this->app->httpResponse()->redirect('equipe');
				}
			}
			$s = ($i>1)?'s':'';
			$this->app->user()->setFlash('Membre' . $s . ' supprimé' . $s . ' avec succès.');
			$this->app->httpResponse()->redirect('equipe');
		}
				
		if($request->getExists('annee'))
		{
			$archive = (string) $request->getData('annee');
			$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			$manager = $this->managers->getManagerOf('Equipe');
			
					
			$page = $manager->getListeByArchive($archive, $this->managers->getManagerOf('Membre'));
			
			if($page === false)
				$this->app->httpResponse()->redirect404();

			$this->page->addVar('title', 'Suppression d\'un (de) membre(s) de l\'année ' . $archive);
			$this->page->addVar('user', $this->app->user());
			$this->page->addVar('design', 'pageEquipe-membre.css');
			$this->page->addVar('page', $page);
			
		}
		else // On ne peut pas appeler supprimer sans l'année
			$this->app->httpResponse()->redirect404();
	}
	
	/**
	 * Permet d'ajouter (remplacer) une photo de groupe
	 * @param \Library\HTTPRequest $request
	 */
	public function executeAjouterPhoto(\Library\HTTPRequest $request)
	{
		if($request->method() == 'POST')
		{
			$drapeauErreur = false;
			
			if(!$request->postExists('archive'))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Erreur est survenue.', 'ERREUR');
			}
			
			$archive = (string) $request->postData('archive');
			$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			
			if(isset($_FILES['photo']) AND ($_FILES['photo']['error'] != 0 && $_FILES['photo']['error'] != 4))
			{
				$drapeauErreur = true;
				$this->app->user()->setFlash('Erreur dans l\'envoie de l\'image : ' . \Library\Entities\Utilitaire::codeErreurFichier($_FILES['photo']['error']), 'ERREUR');
			}
				
				
			if(!$drapeauErreur)
			{
				if (isset($_FILES['photo']) AND $_FILES['photo']['error'] == 0)
				{
					$cible = 'images/equipe/' . str_ireplace('/', '-', $archive);
					$retourFichier = \Library\Entities\Utilitaire::traitementFichier($_FILES['photo'], array('jpeg', 'jpg', 'png'), $cible, 1048576, 800, -1); // Taille de l'image
					if(in_array($retourFichier, array('jpeg', 'jpg', 'png')))
					{
						\Library\Entities\Utilitaire::convertImage($cible . '.' . $retourFichier, $cible . '.jpg', 100);
			
						$this->app->user()->setFlash('La photo a été ajoutée avec succès à l\'équipe.');
						$this->app->httpResponse()->redirect('equipe');
					}
					else
					{
						switch ($retourFichier)
						{
							case 'ERR_POIDS':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car elle était trop lourde (> 1 Mio).', 'ATTENTION');
								break;
							case 'ERR_EXTENSION':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée car l\'extension n\'était pas bonne (uniquement jpeg et png).', 'ATTENTION');
								break;
							case 'ERR_REDIM':
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée, une erreur est survenue lors du redimensionnement.', 'ATTENTION');
								break;
							default:
								$this->app->user()->setFlash('Le membre a été ajouté, mais la photo n\'a pu être chargée pour une raison inconnue (' . (string) $retourFichier . ').', 'ATTENTION');
								break;
						}
						$this->app->httpResponse()->redirect('equipe');
					}
			
				}
				else
				{
					$this->app->user()->setFlash('La photo n\'a pas été envoyée.', 'ERREUR');
					$this->app->httpResponse()->redirect('equipe');
				}
			}
		}
		
		if($request->getExists('annee'))
		{
			$archive = (string) $request->getData('annee');
			$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			$listeAnneesSite = $this->app->listeAnneesAllegee();
			
			if(!in_array($archive, $listeAnneesSite))
				$this->app->httpResponse()->redirect404();
			
			$this->page->addVar('title', 'Photo de groupe de l\'année ' . $archive);
			$this->page->addVar('user', $this->app->user());
			$this->page->addVar('design', 'pageEquipe-membre.css');
			$this->page->addVar('archive', $archive);
		}
		else // On ne peut pas appeler sans l'année
			$this->app->httpResponse()->redirect404();
	}
	
	/**
	 * Permet de supprimer la photo de groupe
	 * @param \Library\HTTPRequest $request
	 */
	public function executeSupprimerPhoto(\Library\HTTPRequest $request)
	{
		if($request->getExists('annee'))
		{
			$archive = (string) $request->getData('annee');
			//$archive = str_ireplace('-', '/', $archive); // remplace le tiret de l'url par le slash de la BdD
			$listeAnneesSite = $this->app->listeAnneesAllegee();
				
			if(!in_array(str_ireplace('-', '/', $archive), $listeAnneesSite))
				$this->app->httpResponse()->redirect404();
			
			unlink('images/equipe/' . $archive . '.jpg');
			
			$this->app->user()->setFlash('La photo a été supprimée.');
			$this->app->httpResponse()->redirect('equipe');
		}
		else // On ne peut pas appeler sans l'année
			$this->app->httpResponse()->redirect404();
	}
}