<?php
namespace Applications\Backend\Modules\Partenaire;
 
class PartenaireController extends \Library\BackController
{
	// *****************************************
	// ******** Gestion des pages fixes ********
	// *****************************************
	
	// Affichage de la liste des news avec pagination.
	// Permet d'avoir une vue globale.
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_partenaire');
		
		$this->page->addVar('title', 'Gestion des partenaires');

		$manager = $this->managers->getManagerOf('Partenaire');
		
		$listePartenaire = $manager->getListe();
		
		
		/*if(empty($listePartenaire))
			$this->app->httpResponse()->redirect404();*/
		
		$this->page->addVar('listePartenaire', $listePartenaire);
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
	}
	
	// Permet d'ajouter une nouvelle news au site
	public function executeAjouter(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_partenaire');
			
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Ajout d\'un partenaire');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
		$this->page->addVar('action', 'ajouter');
	}
	
	// Permet de modifier une news
	public function executeModifier(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_partenaire');
		
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Modification d\'un partenaire');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
		$this->page->addVar('action', 'modifier');
	}
	
	// Fonction qui gère le formulaire pour l'ajout et l'édition des partenaires ; en effet celui-ci est identique pour les deux fonctions.
	public function processusFormulaire(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_partenaire');
		$this->page->addVar('categorieCSS', 'accueil');
		$erreurImage = '';
		
		if ($request->method() == 'POST')
		{
			$partenaire = new \Library\Entities\Partenaire(
											array(
											'nom' => $request->postData('nom'),
											'description' => $request->postData('description'),
											'dateAjout' => new \DateTime(),
											'dateModif' => new \DateTime(),
											'image' => $request->postData('image') // On reçoit l'ancienne image du formulaire
											)
											);

			if ($request->getExists('id'))
			{
				$partenaire->setId($request->getData('id'));
			}
			
			if(isset($_FILES['nouvelleImage'])) // Si on reçoit une image, on la change
			{
				if($_FILES['nouvelleImage']['error'] == 0)
				{
					$image = \Library\Entities\Utilitaire::image($_FILES['nouvelleImage'], 
																  (int) $this->app->config()->get('HAUTEUR_MINIMALE_PARTENAIRE'), 
																  (int) $this->app->config()->get('LARGEUR_MINIMALE_PARTENAIRE'), 
																  'partenaire/', 
																  $partenaire->id(),
																  false, // Pas de vignette
																  false); // Image non carrée
					
					if($image === false)
						$erreurImage = '<br/><strong>Le type de l\'avatar est invalide, elle n\'a pas été changée.</strong>';
					else
						$partenaire->setImage($image);
				}				
			}
		}
		else
		{
			// L'identifiant du partenaire est transmis si on veut le modifier.
			if ($request->getExists('id'))
			{
				$partenaire = $this->managers->getManagerOf('Partenaire')->getUnique($request->getData('id'));
			}
			else
			{
				$partenaire = new \Library\Entities\Partenaire;
			}
		}

		$formBuilder = new \Library\FormBuilder\PartenaireFormBuilder($partenaire);
		$idTextEdit = $formBuilder->build();

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('Partenaire'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash($partenaire->isNew() ? 'Le partenaire a bien été ajouté !' . $erreurImage : 'Le partenaire a bien été modifié !' . $erreurImage);
			$this->app->httpResponse()->redirect('partenaire');
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('existe', $partenaire->existe());
		
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
	}
	
	// Fonction de suppression DÉFINITIVE d'un partenaire
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_partenaire');
		$this->page->addVar('categorieCSS', 'accueil');
		
		$id = (int) $request->getData('id');		
		// Suppression dans la BDD
			$this->managers->getManagerOf('Partenaire')->delete($id);
			$this->app->user()->setFlash('Le partenaire a bien été supprimé !');
			$this->app->httpResponse()->redirect('partenaire');
	}
}