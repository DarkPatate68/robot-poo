<?php
namespace Library\Models;
 
abstract class GroupeManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter un groupe.
	* @param $groupe Groupe Le groupe à ajouter
	* @return void
	*/
	abstract protected function add(Library\Entities\Groupe $groupe);

	/**
	* Méthode renvoyant le nombre de groupe total.
	* @return int
	*/
	abstract public function count();

	/**
	* Méthode permettant de supprimer un groupe.
	* @param $id int L'identifiant du groupe à supprimer
	* @return void
	*/
	abstract public function delete($id);

	/**
	* Méthode retournant une liste des groupes demandés.
	* @return array La liste des groupes. Chaque entrée est une instance de Groupe.
	*/
	abstract public function getListe();

	/**
	* Méthode retournant un groupe précis.
	* @param $id int L'identifiant du groupe à récupérer
	* @return Groupe Le groupe demandé
	*/
	abstract public function getUnique($id);

	/**
	* Méthode permettant d'enregistrer un groupe.
	* @param $groupe Groupe le groupe à enregistrer
	* @see self::add()
	* @see self::modify()
	* @return void
	*/
	public function save(Library\Entities\Groupe $groupe)
	{
		if ($groupe->estValide())
		{
		  $groupe->existe() ? $this->update($groupe) : $this->add($groupe);
		}
		else
		{
		  throw new \RuntimeException('Le groupe doit être valide pour être enregistré');
		}
	}

	/**
	* Méthode permettant de modifier un groupe.
	* @param $groupe groupe le groupe à modifier
	* @return void
	*/
	abstract protected function update(Library\Entities\Groupe $groupe);
}