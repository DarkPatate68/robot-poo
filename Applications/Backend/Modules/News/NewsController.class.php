<?php
namespace Applications\Backend\Modules\News;
 
class NewsController extends \Library\BackController
{
	// **********************************
	// ******** Gestion des news ********
	// **********************************
	
	// Affichage de la liste des news avec pagination.
	// Permet d'avoir une vue globale.
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_news');
		
		$this->page->addVar('title', 'Gestion des news');
		$nombreNewsParPage = $this->app->config()->get('NOMBRE_NEWS_PAR_PAGE');

		$manager = $this->managers->getManagerOf('News');
		
		if($request->getExists('page'))
			$numeroPage = $request->getData('page')-1;
		else
			$numeroPage = 0;
		$numeroPage < 0 ? 0 : $numeroPage;
		
		$listeNews = $manager->getListe($numeroPage*$nombreNewsParPage, $nombreNewsParPage, true, $this->managers->getManagerOf('Membre'));
		
		
		if(empty($listeNews))
			$this->app->httpResponse()->redirect404();
		
		$this->page->addVar('nbrPage', \ceil($manager->count()/$nombreNewsParPage));
		$this->page->addVar('pageActuelle', $numeroPage);

		$this->page->addVar('listeNews', $listeNews);
		$this->page->addVar('nombreNews', $manager->count());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
	}
	
	// Permet d'ajouter une nouvelle news au site
	public function executeAjouter(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_news');
			
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Ajout d\'une news');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
	}
	
	// Permet de modifier une news
	public function executeModifier(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_news');
		
		$this->processusFormulaire($request);

		$this->page->addVar('title', 'Modification d\'une news');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'newsMembre.css');
		$this->page->addVar('categorieCSS', 'accueil');
	}
	
	// Fonction qui gère le formulaire pour l'ajout et l'édition des news ; en effet celui-ci est identique pour les deux fonctions.
	public function processusFormulaire(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_news');
		$this->page->addVar('categorieCSS', 'accueil');
		
		if ($request->method() == 'POST')
		{
			$news = new \Library\Entities\News(
											array(
											'auteur' => new \Library\Entities\Membre(array('id' => $request->postData('auteur'), 'dateInscription' => 'now')), // Le membre est vide, car lors de l'enregistrement en BDD, seul l'id est utilisée
											'titre' => $request->postData('titre'),
											'contenu' => $request->postData('contenu'),
											'privee' => $request->postExists('privee'),
											'archive' => $request->postExists('archive'),
											'editeur' => new \Library\Entities\Membre(array('id' => $request->postData('editeur'), 'dateInscription' => 'now'))
											)
											);

			if ($request->getExists('id'))
			{
				$news->setId($request->getData('id'));
			}
			else
			{
				$news->setArchive($this->app()->anneeEnCours());
			}
		}
		else
		{
			// L'identifiant de la news est transmis si on veut la modifier.
			if ($request->getExists('id'))
			{
				$news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
				$news->setEditeur($this->app()->user()->membre());
			}
			else
			{
				$news = new \Library\Entities\News;
				$news->setAuteur($this->app()->user()->membre());
				$news->setEditeur($this->app()->user()->membre());
			}
		}

		$formBuilder = new \Library\FormBuilder\NewsFormBuilder($news);
		$idTextEdit = $formBuilder->build();

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('News'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');
			$this->app->httpResponse()->redirect('news');
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('existe', $news->existe());
		
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
	}
	
	// Fonction de suppression DÉFINITIVE d'une news
	public function executeSupprimer(\Library\HTTPRequest $request)
	{
		$this->viewRight('mod_news');
		$this->page->addVar('categorieCSS', 'accueil');
		
		$this->managers->getManagerOf('News')->delete($request->getData('id'));
		$this->app->user()->setFlash('La news a bien été supprimée !');
		$this->app->httpResponse()->redirect('news');
	}
	
	// ******************************************
	// ******** Gestion des commentaires ********
	// ******************************************
	
	public function executeModifierCommentaire(\Library\HTTPRequest $request)
	{
		// Chargement des données
		$this->page->addVar('title', 'Modification d\'un commentaire');	
		$this->page->addVar('categorieCSS', 'accueil');		
		
		$commentaire = $this->managers->getManagerOf('Commentaire')->get($request->getData('id'), $this->managers->getManagerOf('Membre'));
		$commentaire->setEditeur($this->app()->user()->membre());
		
		// Gestions des droits de visualtion
		if($commentaire->supprime())
			$this->app->httpResponse()->redirect404();
		
		if(($commentaire->auteur()->id() == 0 || $commentaire->auteur()->id() != $this->app->user()->membre()->id()) && !$this->app->user()->membre()->groupeObjet()->droits('mod_news_commentaire'))
			$this->app->httpResponse()->redirect403();
		
		// Corps de la méthode
		if ($request->method() == 'POST')
		{
			$commentaire = new \Library\Entities\Commentaire(array(
			'id' => $request->getData('id'),
			'auteur' => new \Library\Entities\Membre(array('id' => $request->postData('auteur'), 'dateInscription' => 'now')),
			'contenu' => $request->postData('contenu'),
			'editeur' => new \Library\Entities\Membre(array('id' => $request->postData('editeur'), 'dateInscription' => 'now')),
			'moderation' => '',
			'supprime' => false,
			'news' => $request->postData('news')
			));
			
			$usuel = $request->postData('nom_auteur');
		}
		else
			$usuel = $commentaire->auteur()->usuel();

		$formBuilder = new \Library\FormBuilder\CommentaireFormBuilder($commentaire, array('nom_auteur' => $usuel));
		$idTextEdit = $formBuilder->build($this->app(), false, true); // Sans captcha et avec champ caché pour l'id de la news

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('Commentaire'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été modifié !');
			$this->app->httpResponse()->redirect('pre/news-'.$request->postData('news').'#C-' . $commentaire->id());
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('design', 'newsMembre.css');
		
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
	}
	
	// Supprime un commentaire NON définitivement, il n'est juste plus affiché mais présent ; afin de voir qu'un message a été posté
	public function executeSupprimerCommentaire(\Library\HTTPRequest $request)
	{
		// Chargement des données
		$this->page->addVar('title', 'Suppression d\'un commentaire');
		$this->page->addVar('categorieCSS', 'accueil');		
		$commentaire = $this->managers->getManagerOf('Commentaire')->get($request->getData('id'), $this->managers->getManagerOf('Membre'));
		$commentaire->setEditeur($this->app()->user()->membre());
		$commentaire->setSupprime(true);
		
		// Gestions des droits de visualtion
		$this->viewRight('mod_news_commentaire');
		
		// Corps de la méthode
		if ($request->method() == 'POST')
		{
			$commentaire = new \Library\Entities\Commentaire(array(
			'id' => $request->getData('id'),
			'auteur' => new \Library\Entities\Membre(array('id' => $request->postData('auteur'), 'dateInscription' => 'now')),
			'contenu' => $request->postData('contenu'),
			'editeur' => new \Library\Entities\Membre(array('id' => $request->postData('editeur'), 'dateInscription' => 'now')),
			'moderation' => $request->postData('moderation'),
			'supprime' => $request->postExists('supprime')
			));
		}

		$formBuilder = new \Library\FormBuilder\CommentaireModerationFormBuilder($commentaire); // Charge dans le formulaire le bon commentaire
		$formBuilder->build();

		$form = $formBuilder->form();

		$formHandler = new \Library\FormHandler($form, $this->managers->getManagerOf('Commentaire'), $request);

		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été ' . ($commentaire['supprime'] ? 'supprimé' : 'rétabli') . ' !');
			$this->app->httpResponse()->redirect('pre/news-'.$request->postData('news').'#C-' . $commentaire->id());
		}

		$this->page->addVar('form', $form->createView());
		$this->page->addVar('design', 'newsMembre.css');
	}
}