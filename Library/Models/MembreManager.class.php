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
	abstract public function getListe(\Library\Models\GroupeManager $groupeManager = null);
	
	/**
	 * Méthode retournant une liste des membres, ne renvoie que leur id, leur nom, leur prénom et leur pseudo.
	 * @return array La liste des membres. Chaque entrée est une instance de Membre.
	 */
	abstract public function getListeMinimale();
	

	/**
	* Méthode retournant un membre précis.
	* @param $id int L'identifiant du membre à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUnique($id, \Library\Models\GroupeManager $groupeManager = null);
	
	/**
	 * Méthode retournant un membre précis.
	 * @param $pseudo string Le pseudo du membre à récupérer
	 * @return Membre Le membre demandé
	 */
	abstract public function getUniqueByPseudo($pseudo, \Library\Models\GroupeManager $groupeManager = null);
	
	/**
	 * Retourne l'ID du membre possédant ce pseudo
	 * @param string $pseudo Le pseudo du membre
	 * @return int ID du membre
	 */
	abstract  public  function getId($pseudo);
	
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
	
	/**
	 * Change tous les membres du groupe donné par l'id vers le groupe membre
	 * @param int $id du groupe
	 */
	abstract public function declasser($id);
	
	/**
	 * Permet de changer le groupe d'un membre
	 * @param int $id du membre
	 * @param int $groupe nouveau groupe du membre
	 */
	abstract public function updateGroupe($id, $groupe);
	
	/**
	 * Permet de changer de président
	 * @param int $nouveauPresident ID du nouveau président
	 * @param int $ancienPresident ID de l'ancien président
	 * @param int $nouveauGroupe ID du nouveau groupe de l'ancien président
	 */
	abstract public function passation($nouveauPresident, $ancienPresident, $nouveauGroupe);
}