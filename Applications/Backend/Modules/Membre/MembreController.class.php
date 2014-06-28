<?php
namespace Applications\Backend\Modules\Membre;
 
class MembreController extends \Library\BackController
{
	public function executeProfil(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Modifier votre profil');
		$this->page->addVar('categorieCSS', 'membres');
		$this->page->addVar('design', 'membre.css');
		
		if(!$this->app->user()->isAuthenticated())
		{
			$this->app->httpResponse()->redirect('connexion');
		}
		
		// Permet à un membre de modifier son profil
		if($request->method() == 'POST')
		{
			$pseudo = $request->postData('pseudo');
			$prenom = $request->postData('prenom');
			$nom = $request->postData('nom');
			$bio = $request->postData('bio');
			$section = $request->postData('section');
			$tshirt = $request->postData('tshirt');
			$telephone = $request->postData('telephone');
			$usuel = $request->postData('usuel');
			$avatar;
			
			$erreur = 'La modification de votre profil a rencontré les problèmes suivants :<br/><ul>';
			$drapeauErreur = false;
			
			if(empty($pseudo))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Veuilliez renseigner votre pseudo.</li>';
			}
			
			if(($nom == 'Da' && $prenom == 'Lamb') || $pseudo == 'Anonyme')
			{
				$drapeauErreur = true;
				$erreur .= '<li>Il n\'y a qu\'un seul M. Lamb Da ! ;-).</li>';
			}
			
			if(empty($nom) || empty($prenom))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Veuilliez renseigner votre nom et votre prénom.</li>';
			}
			
			if(!preg_match("#^(\+33|0)[1-9]([-. ]?[0-9]{2}){4}$#", $telephone) && !empty($telephone))
			{
				$drapeauErreur = true;
				$erreur .= '<li>Le numéro de téléphone est invalide (il faut un numéro français).</li>';
			}
			
			
			// --------------------------
			// --------- AVATAR ---------
			// --------------------------
			
			if(isset($_FILES['photo']))
			{
				if($_FILES['photo']['error'] == 0)
				{
					$image = \Library\Entities\Utilitaire::image($_FILES['photo'], 
																  (int) $this->app->config()->get('HAUTEUR_MINIMALE_AVATAR'), 
																  (int) $this->app->config()->get('LARGEUR_MINIMALE_AVATAR'), 
																  'membres/', 
																  $this->app->user()->membre()->id(), 
																  true); // On veut la création d'une vignette en plus (image 30×40)
					
					if($image === false)
					{
						$drapeauErreur = true;
						$erreur .= '<li>Le type de l\'avatar est invalide.</li>';
					}
					else
						$avatar = $image;
				}
				else
				{
					$avatar = $this->app->user()->membre()->avatar();
				}
			}
	
	// FIN AVATAR
	
			if($drapeauErreur)
			{
				$this->app->user()->setFlash($erreur . '</ul>', 'ERREUR');
				return;
			}
			
			$manager = $this->managers->getManagerOf('Membre');
			
			$membre = $this->app->user()->membre();
			$membre->setNom($nom);
			$membre->setPrenom($prenom);
			$membre->setPseudo($pseudo);
			$membre->setSection($section);
			if($usuel=='usuel_prenom_nom')
				$membre->setUsuel('USUEL_PRENOM_NOM');
			else
				$membre->setUsuel('USUEL_PSEUDO');
			$membre->setAvatar($avatar);
			$membre->setBiographie($bio);
			$membre->setTshirt($tshirt);
			$membre->setTelephone($telephone);
			
			if(!$this->managers->getManagerOf('Membre')->update($membre))
			{
				$this->app->user()->setFlash('Une erreur est survenue lors de la modification de votre profil.', 'ERREUR');
				return;
			}
			
			
			$this->app->user()->setMembre($membre);
			$this->app->user()->setAuthenticated($membre, true);
			
			//$membre = $this->managers->getManagerOf('Membre')->connexion($pseudo, $mdp)
					
			$this->app->user()->setFlash('Votre profil a bien été mis à jour.');
			$this->app->httpResponse()->redirect($GLOBALS['PREFIXE'] . '/membre/');
		}
	}
	
	public function executeValider(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Valider des personnes');
		$this->page->addVar('categorieCSS', 'membres');
		$this->page->addVar('design', 'membre.css');
		
		if(!$this->app->user()->isAuthenticated())
		{
			$this->app->httpResponse()->redirect('connexion');
		}
		
		if(!$this->app->user()->membre()->groupeObjet()->droits('valider_membre'))
		{
			$this->app->httpResponse()->redirect403();
		}
		
		$manager = $this->managers->getManagerOf('Membre');		
		
		if(!empty($_POST))
		{
			$listeMbr = array();
			foreach($_POST as $clef => $val)
			{
				$listeMbr[] = (int) substr($clef, 4);
			}
			
			$manager->valider($listeMbr);				
		}
		
		
		$listeMembreAValider = $manager->getNotValidate();		
		$this->page->addVar('listeMembreAValider', $listeMembreAValider);		
		
		// Afficher la liste des membres non validés et proposer de les valider (ainsi que leur définir un groupe).
	}
	
	public function executeMdp(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Changer son mot de passe');
		$this->page->addVar('categorieCSS', 'membres');
		$this->page->addVar('design', 'membre.css');
		
		if(!empty($_POST))
		{
			$ancien = \Applications\Backend\Modules\Connexion\ConnexionController::chiffrer((string) $request->postData('ancien_mdp'));
			$nouveau = (string) $request->postData('mdp');
			$nouveau2 = (string) $request->postData('mdp2');
			
			$manager = $this->managers->getManagerOf('Membre');
			
			if($nouveau !== $nouveau2)
			{
				$this->app->user()->setFlash('Les deux mots de passes ne sont pas identiques !', 'ERREUR');
				return;
			}
			
			if($ancien != $manager->getMdp($this->app->user()->membre()->id())/*$this->app->user()->membre()->mdp()*/)
			{	
				$this->app->user()->setFlash('Votre ancien mot de passe et celui entré ne correspondent pas.', 'ERREUR');
				return;
			}
			
			
			$membre = $this->app->user()->membre();
			$membre->setMdpChiffre($nouveau);
			
			if(!$manager->update($membre, true)) 
			{
				$this->app->user()->setFlash('Une erreur est survenue lors de la modification de votre mot de passe.', 'ERREUR');
				return;
			}
			
			$this->app->user()->setMembre($membre);
			$this->app->user()->setAuthenticated($membre, true);
			
			$this->app->user()->setFlash('Votre mot de passe a bien été mis à jour.');
			$this->app->httpResponse()->redirect($GLOBALS['PREFIXE'] . '/membre/');			
		}		
	}
}