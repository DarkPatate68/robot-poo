<?php
namespace Applications\Backend\Modules\Connexion;
 
class ConnexionController extends \Library\BackController
{
	public function executeConnexion(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Connexion');
		$this->page->addVar('design', 'connexion.css');
		
		if($this->app->user()->isAuthenticated())
		{
			$this->app->httpResponse()->redirect('deconnexion');
		}

		if ($request->postExists('pseudo'))
		{
			$pseudo = $request->postData('pseudo');
			$mdp = $request->postData('mdp');
			$mdp = self::chiffrer($mdp);

			if ($membre = $this->managers->getManagerOf('Membre')->connexion($pseudo, $mdp))
			{
				if($membre->id() == 0)
					$this->app->user()->setFlash('Impossible de se connecter en anonyme !', 'ATTENTION');
				else
				{
					$membre->setGroupeObjet($this->managers->getManagerOf('Groupe')->getUnique($membre->groupe()));
					$this->app->user()->setAuthenticated($membre, true);
					
					if($request->postExists('cookie'))
					{
						$this->app->httpResponse()->setCookie('pseudo', $pseudo, time() + 365*24*3600); // Valable pour un an
						$this->app->httpResponse()->setCookie('mdp', $mdp, time() + 365*24*3600);
					}
					else // destruction des cookies
					{
						$this->app->httpResponse()->setCookie('pseudo', '', time() - 3600); // Destruction du cookie
						$this->app->httpResponse()->setCookie('mdp', '', time() - 3600);
					}
					
					// Redirige l'utilisateur vers le lien demandé s'il est arrivé sur la page de connexion sans cliquer sur le lien.
					// S'il a cliqué sur « connexion », alors il est redirigé vers l'accueil
					$urlCoupee = explode("/", $request->requestURI());					
					if($urlCoupee[count($urlCoupee)-1] == 'connexion')
						$this->app->httpResponse()->redirect('pre/accueil-1');
					else
						$this->app->httpResponse()->redirect($request->requestURI());
				}
			}
			else
			{
				$this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.', 'ERREUR');
			}
		}	
	}
	
	public function executeDeconnexion()
	{
		$this->app->httpResponse()->setCookie('pseudo', '', time() - 3600); // Destruction du cookie
		$this->app->httpResponse()->setCookie('mdp', '', time() - 3600);
		
		$membre = $this->managers->getManagerOf('Membre')->getUnique(0);
		$membre->setGroupeObjet($this->managers->getManagerOf('Groupe')->getUnique(0));
		
		$this->app->user()->setAuthenticated($membre, false);
		$this->app->httpResponse()->redirect($GLOBALS['PREFIXE']);
	}
	
	// Cette fonction est un « vestige » car elle a été créée avant la classe Utilitaire qui regroupe les fonctions utiles de tout le site
	// Comme de nombreuses classes utilisent la fonction de ConnexionController, elle reste par souci de rétrocompatibilité.
	static public function chiffrer($mdp)
	{
		return \Library\Entities\Utilitaire::chiffrer($mdp);
	}
	
	public function executeInscription(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Inscription');
		$this->page->addVar('design', 'connexion.css');
		
		if($this->app->user()->isAuthenticated())
		{
			$this->app->httpResponse()->redirect('pre/accueil');
		}
		
		/*$this->app->user()->setFlash('Pensez que votre adresse INSA n\'est pas valable à vie !<br>
									  Il ne sera plus possible de changer le courriel de votre compte après.', 'ATTENTION');*/
		
		if($request->postExists('pseudo'))
		{
			$pseudo = $request->postData('pseudo');
			$prenom = $request->postData('prenom');
			$nom = $request->postData('nom');
			$mdp = $request->postData('mdp');
			$mdp2 = $request->postData('mdp2');
			$courriel = $request->postData('courriel');
			$section = $request->postData('section');
			
					
			$erreur = 'L\'inscription a rencontré les problèmes suivants :<br/><ul>';
			$drapeauErreur = false;
			
			if(empty($mdp))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Votre mot de passe est vide.</li>';
			}
			
			if($mdp != $mdp2)
			{
				$drapeauErreur = true;
				$erreur .= '<li>Les mots de passe ne sont pas identiques.</li>';
			}
			
			if(($nom == 'Da' && $prenom == 'Lamb') || $pseudo == 'Anonyme')
			{
				$drapeauErreur = true;
				$erreur .= '<li>Il n\'y a qu\'un seul M. Lamb Da ! ;-).</li>';
			}
			
			if(empty($nom) || empty($prenom) || empty($pseudo))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Veuilliez renseigner votre nom, votre prénom et votre pseudo.</li>';
			}
			
			if(strpos($pseudo, '|') !== false)
			{
				$drapeauErreur = true;
				$erreur .= '<li>Le caractère « | » ne peut être utilisé dans un pseudo.</li>';
			}
			
			$manager = $this->managers->getManagerOf('Membre');
			$listeMembreInscrit = $manager->getListeMinimale();
				
				foreach ($listeMembreInscrit as $membreInscrit)
				{
					$listePseudo[] = $membreInscrit['pseudo'];
				}
			
			if(in_array($pseudo, $listePseudo))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Ce pseudo est déjà utilisé par un autre membre.</li>';
			}
			
			if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $courriel))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Votre courriel est invalide.</li>';
			}
			
			if($drapeauErreur)
			{
				$this->app->user()->setFlash($erreur . '</ul>', 'ERREUR');
				return;
			}
			
			$manager = $this->managers->getManagerOf('Membre');
			
			if($manager->courrielExistant($courriel))
			{
				$this->app->user()->setFlash('Ce courriel est déjà utilisé par un membre.', 'ERREUR');
				return;
			}
						
			$membre = new \Library\Entities\Membre(array('nom' => $nom, 
														 'prenom' => $prenom, 
														 'pseudo' => $pseudo, 
														 'usuel' => 'USUEL_PSEUDO', 
														 'avatar' => '0.png', 
														 'section' => $section, 
														 'actif' => 0, 
														 'valide' => 0, 
														 'biographie' => '', 
														 'dateInscription' => date('Y-m-d H:i:s'), 
														 'courriel' => $courriel, 
														 'groupe' => 0, 
														 'groupeObjet' => new \Library\Entities\Groupe(), 
														 'mdp' => self::chiffrer($mdp)));
			
			if(!$this->managers->getManagerOf('Membre')->add($membre))
			{
				$this->app->user()->setFlash('Une erreur est survenue lors de l\'inscription.', 'ERREUR');
				return;
			}
			
			//$membre = $this->managers->getManagerOf('Membre')->connexion($pseudo, $mdp)
					
			$this->app->user()->setFlash('Vous avez bien été inscrit. Un administrateur validera votre compte le plus rapidement possible.');
			$this->app->httpResponse()->redirect('connexion');
		}
		
	}
}