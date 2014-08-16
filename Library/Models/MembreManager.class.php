<?php
namespace Library\Models;
 
abstract class MembreManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter un membre.
	* @param $membre Membre Le membre à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\Membre $membre);

	/**
	* Méthode renvoyant le nombre de membre total.
	* @return int
	*/
	abstract public function count();

	/**
	* Méthode permettant de supprimer un membre.
	* @param $id int L'identifiant du membre à supprimer
	* @return void
	*/
	abstract public function delete($id);

	/**
	* Méthode retournant une liste des membres demandés.
	* @return array La liste des membres. Chaque entrée est une instance de Membre.
	*/
	abstract public function getListe();
	

	/**
	* Méthode retournant un membre précis.
	* @param $id int L'identifiant du membre à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUnique($id, \Library\Models\GroupeManager $groupeManager = null);
	
	/**
	* Méthode retournant une liste des membres non validés.
	* @return array La liste des membres. Chaque entrée est une instance de Membre.
	*/
	abstract public function getNotValidate();
	
	/**
	* Méthode permetant de connecté l'utilisateur en vérifiant la BDD.
	* @param $pseudo string Le pseudo du membre à connecter
	* @param $mdp string Le mot de passe du membre à connecter
	* @return Membre Le membre demandé ou false en cas d'échec
	*/
	abstract public function connexion($pseudo, $mdp);

	/**
	* Méthode permettant d'enregistrer un membre.
	* @param $membre Membre le membre à enregistrer
	* @see self::add()
	* @see self::modify()
	* @return void
	*/
	public function save(\Library\Entities\Membre $membre)
	{
		if ($membre->estValide())
		{
		  $membre->existe() ? $this->update($membre) : $this->add($membre);
		}
		else
		{
		  throw new \RuntimeException('Le membre doit être valide pour être enregistré');
		}
	}

	/**
	* Méthode permettant de modifier un membre.
	* @param $membre membre le membre à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\Membre $membre, $mdp = false);
	
	/**
	* Méthode permettant de vérifier si un courriel est déjà utilisé.
	* @param $courriel courriel à vérifier
	* @return bool vrai s'il existe
	*/
	abstract public function courrielExistant($courriel);
	
	/**
	* Méthode permettant de valider un ou plusieurs membre
	* @param $liste id du/des membres à valider
	* @return void
	*/
	abstract public function valider(array $liste);
	
	/**
	* Méthode permettant de recupérer le mot de passe du membre
	* @param $id id du membre 
	* @return string mot de passe
	*/
	abstract public function getMdp($id);
}