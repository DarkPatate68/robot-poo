<?php
namespace Applications\Frontend\Modules\News;
 
class NewsController extends \Library\BackController
{
	/***************************************************
	 ************ PAGE D'ACCUEIL DU SITE ***************
	 **************************************************/
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$nombreNewsParPage = $this->app->config()->get('nombre_news_par_page');
		$nombreCaracteres = $this->app->config()->get('nombre_caracteres');
		$utilisateurPrivee = $this->app->user()->membre()->groupeObjet()->droits('privee');

		// On ajoute une définition pour le titre.
		$this->page->addVar('title', 'Accueil');
		
		// Design supplémentaire pour les news.
		$this->page->addVar('design', 'news.css');		
		// On ajoute une catégorie pour le style de la page
		$this->page->addVar('categorieCSS', 'accueil');

		// On récupère le manager des news.
		$manager = $this->managers->getManagerOf('News');

		// Récupère la liste des news
		
		if($request->getExists('page'))
			$numeroPage = $request->getData('page')-1;
		else
			$numeroPage = 0;
		$numeroPage < 0 ? 0 : $numeroPage;
		
		$listeNews = $manager->getListe($numeroPage*$nombreNewsParPage, $nombreNewsParPage, $utilisateurPrivee, $this->managers->getManagerOf('Membre'));
		foreach($listeNews as $news)
		{
			$nbrCom[] = $this->managers->getManagerOf('Commentaire')->count($news->id());
		}
		
		
		if(empty($listeNews))
			$this->app->httpResponse()->redirect404();
		
		$this->page->addVar('nbrPage', \ceil($manager->count($utilisateurPrivee)/$nombreNewsParPage));
		$this->page->addVar('pageActuelle', $numeroPage);

		$estCoupee = array();
		foreach ($listeNews as $news)
		{
			if (strlen($news->contenu()) > $nombreCaracteres && $nombreCaracteres > 0)
			{
				$debut = substr($news->contenu(), 0, $nombreCaracteres);
				$debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

				$news->setContenu($debut);
				$estCoupee[] = true;
			}
			else
				$estCoupee[] = false;
		}

		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar('listeNews', $listeNews);
		$this->page->addVar('estCoupee', $estCoupee);
		$this->page->addVar('nombreCommentaire', $nbrCom);
		$this->page->addVar('user', $this->app->user());
	}
	
	/***************************************************
	 ************* AFFICHAGE D'UNE NEWS ****************
	 **************************************************/
	public function executeNews(\Library\HTTPRequest $request)
	{
		$news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'), $this->managers->getManagerOf('Membre'));
		$nombreComParPage = $this->app->config()->get('nombre_commentaire_news_par_page');
		
		// Nombre de page
		if($request->getExists('page'))
			$numeroPage = $request->getData('page')-1;
		else
			$numeroPage = 0;
		$numeroPage < 0 ? 0 : $numeroPage;
		//---------------------------------
		 
		if (empty($news))
		{
			$this->app->httpResponse()->redirect404();
		}
		if($news->privee() && !$this->app->user()->isAuthenticated())
		{
			$this->app->httpResponse()->redirect401();
		}
		
		if($news->privee() && !$this->app->user()->membre()->groupeObjet()->droits('privee'))
		{
			$this->app->httpResponse()->redirect403();
		}
		
		$this->page->addVar('title', $news->titre());
		$this->page->addVar('news', $news);
		$this->page->addVar('design', 'news.css');
		// On ajoute une catégorie pour le style de la page
		$this->page->addVar('categorieCSS', 'accueil');
		
		$managerCommentaire = $this->managers->getManagerOf('Commentaire');
		$commentaires = $managerCommentaire->getListe($news->id(), $this->managers->getManagerOf('Membre'), $numeroPage*$nombreComParPage, $nombreComParPage);
		
		$this->page->addVar('commentaires', $commentaires);
		$this->page->addVar('nbrPage', \ceil($managerCommentaire->count($news->id())/$nombreComParPage));
		$this->page->addVar('pageActuelle', $numeroPage);
		
		$this->page->addVar('user', $this->app->user());
	}
	
	/***************************************************
	 ************ AJOUTER UN COMMENTAIRE ***************
	 **************************************************/
	public function executeInsererCommentaire(\Library\HTTPRequest $request)
	{
		$this->page->addVar('design', 'news.css');
		// On ajoute une catégorie pour le style de la page
		$this->page->addVar('categorieCSS', 'news');
		$this->page->addVar('title', 'Ajout d\'un commentaire');
		$news = $this->managers->getManagerOf('News')->getUnique($request->getData('news'));
		$commentaire;

		if ($request->method() == 'POST')
		{
			if(!$request->postExists('captcha') || $request->postData('captcha') == $this->app->user()->getAttribute('captcha'))
			{
				if(!$news['privee'] || ($news['privee'] && $this->app->user()->membre()->groupeObjet()->droits('privee')))
				{
					$commentaire = new \Library\Entities\Commentaire(array(
					'news' => $request->getData('news'),
					'auteur' => $this->app->user()->membre(),
					'contenu' => $request->postData('contenu')
					));

					if ($commentaire->estValide())
					{
						$this->managers->getManagerOf('Commentaire')->save($commentaire);
						$this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
						$this->app->httpResponse()->redirect('news-'.$request->getData('news') . '-' . $news->titreTiret());
					}
					else
					{
						$this->page->addVar('erreurs', $commentaire->erreurs());
					}

					$this->page->addVar('commentaire', $commentaire);
				}
				else
					$this->app->httpResponse()->redirect403();
			}
			else
			{
				$this->app->user()->setFlash('Les chiffres rentrés ne correspondent pas.');
				if(!(!$news['privee'] || ($news['privee'] && $this->app->user()->membre()->groupeObjet()->droits('privee'))))
				$this->app->httpResponse()->redirect403();
			
				$this->page->addVar('pseudo', $this->app->user()->membre()->usuel());
				$this->page->addVar('connecte', $this->app->user()->isAuthenticated());
				
				// On remet le commentaire dans la zone de texte
				$commentaire = new \Library\Entities\Commentaire(array(
					'news' => $request->getData('news'),
					'auteur' => $this->app->user()->membre(),
					'contenu' => $request->postData('contenu')
					));
			}
		}
		else
		{
			if(!(!$news['privee'] || ($news['privee'] && $this->app->user()->membre()->groupeObjet()->droits('privee'))))
				$this->app->httpResponse()->redirect403();
				
				$commentaire = new \Library\Entities\Commentaire(array(
					'auteur' => $this->app->user()->membre()
					));
		}
		
		$formBuilder = new \Library\FormBuilder\CommentaireFormBuilder($commentaire);
		$idTextEdit = '';
		if($this->app()->user()->membre()->id() == 0)
			$idTextEdit = $formBuilder->build($this->app, true); // Mise en place du captcha
		else
			$idTextEdit = $formBuilder->build($this->app);
		
		$form = $formBuilder->form();
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('idTextEdit', $idTextEdit);
		$this->page->addVar('droitImport', $this->viewRightCode('importer_image'));
	}
	
	public function executeRedirection(\Library\HTTPRequest $request)
	{
		$this->app->httpResponse()->redirect('accueil');
	}
}